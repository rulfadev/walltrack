<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use App\Models\WalletModel;

class Wallet extends BaseController
{
    protected WalletModel $walletModel;
    protected TransactionModel $transactionModel;

    public function __construct()
    {
        $this->walletModel = new WalletModel();
        $this->transactionModel = new TransactionModel();
    }

    private function userId(): int
    {
        $user = session()->get('user');

        if (is_array($user) && isset($user['id'])) {
            return (int) $user['id'];
        }

        return (int) session()->get('user.id');
    }

    public function index()
    {
        $userId = $this->userId();

        $wallets = $this->walletModel
            ->where('user_id', $userId)
            ->orderBy('is_default', 'DESC')
            ->orderBy('name', 'ASC')
            ->findAll();

        return view('wallets/index', [
            'title' => 'Walltrack | Wallet',
            'wallets' => $wallets,
        ]);
    }

    public function create()
    {
        return view('wallets/create', [
            'title' => 'Walltrack | Tambah Wallet',
        ]);
    }

    public function store()
    {
        $rules = [
            'name' => 'required|min_length[2]|max_length[100]',
            'type' => 'required|in_list[cash,bank,ewallet,saving]',
            'initial_balance' => 'permit_empty|numeric|greater_than_equal_to[0]',
            'color' => 'permit_empty|max_length[20]',
            'icon' => 'permit_empty|max_length[50]',
        ];

        if (!$this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $userId = $this->userId();

        $hasWallet = $this->walletModel
            ->where('user_id', $userId)
            ->first();

        $initialBalance = (float) ($this->request->getPost('initial_balance') ?: 0);

        $this->walletModel->insert([
            'user_id' => $userId,
            'name' => trim((string) $this->request->getPost('name')),
            'type' => $this->request->getPost('type'),
            'initial_balance' => $initialBalance,
            'current_balance' => $initialBalance,
            'color' => $this->request->getPost('color') ?: '#134686',
            'icon' => $this->request->getPost('icon') ?: 'bi-wallet2',
            'is_default' => $hasWallet ? 0 : 1,
        ]);

        return redirect()
            ->to('/wallets')
            ->with('success', 'Wallet berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $userId = $this->userId();

        $wallet = $this->walletModel
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$wallet) {
            return redirect()
                ->to('/wallets')
                ->with('error', 'Wallet tidak ditemukan.');
        }

        return view('wallets/edit', [
            'title' => 'Walltrack | Edit Wallet',
            'wallet' => $wallet,
        ]);
    }

    public function update($id)
    {
        $userId = $this->userId();

        $wallet = $this->walletModel
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$wallet) {
            return redirect()
                ->to('/wallets')
                ->with('error', 'Wallet tidak ditemukan.');
        }

        $rules = [
            'name' => 'required|min_length[2]|max_length[100]',
            'type' => 'required|in_list[cash,bank,ewallet,saving]',
            'color' => 'permit_empty|max_length[20]',
            'icon' => 'permit_empty|max_length[50]',
        ];

        if (!$this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->walletModel->update((int) $id, [
            'name' => trim((string) $this->request->getPost('name')),
            'type' => $this->request->getPost('type'),
            'color' => $this->request->getPost('color') ?: '#134686',
            'icon' => $this->request->getPost('icon') ?: 'bi-wallet2',
        ]);

        return redirect()
            ->to('/wallets')
            ->with('success', 'Wallet berhasil diperbarui.');
    }

    public function setDefault($id)
    {
        $userId = $this->userId();

        $wallet = $this->walletModel
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$wallet) {
            return redirect()
                ->to('/wallets')
                ->with('error', 'Wallet tidak ditemukan.');
        }

        $this->walletModel
            ->where('user_id', $userId)
            ->set(['is_default' => 0])
            ->update();

        $this->walletModel->update((int) $id, [
            'is_default' => 1,
        ]);

        return redirect()
            ->to('/wallets')
            ->with('success', 'Wallet utama berhasil diperbarui.');
    }

    public function delete()
    {
        $json = $this->request->getJSON();
        $walletId = $json->id ?? null;
        $userId = $this->userId();

        if (!$walletId) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Wallet tidak valid.',
                'csrfHash' => csrf_hash(),
            ]);
        }

        $wallet = $this->walletModel
            ->where('id', $walletId)
            ->where('user_id', $userId)
            ->first();

        if (!$wallet) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Wallet tidak ditemukan.',
                'csrfHash' => csrf_hash(),
            ]);
        }

        $hasTransaction = $this->transactionModel
            ->where('user_id', $userId)
            ->groupStart()
            ->where('wallet_id', $walletId)
            ->orWhere('transfer_to_wallet_id', $walletId)
            ->groupEnd()
            ->first();

        if ($hasTransaction) {
            return $this->response->setStatusCode(422)->setJSON([
                'success' => false,
                'message' => 'Wallet tidak bisa dihapus karena sudah memiliki transaksi.',
                'csrfHash' => csrf_hash(),
            ]);
        }

        $this->walletModel->delete((int) $walletId);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Wallet berhasil dihapus.',
            'csrfHash' => csrf_hash(),
        ]);
    }
}