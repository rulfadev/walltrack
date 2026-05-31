<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\TransactionModel;
use App\Models\WalletModel;

class Transaction extends BaseController
{
    protected TransactionModel $transactionModel;
    protected CategoryModel $categoryModel;
    protected WalletModel $walletModel;
    protected $db;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
        $this->categoryModel = new CategoryModel();
        $this->walletModel = new WalletModel();
        $this->db = db_connect();
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

        $walletId = $this->request->getGet('wallet_id');
        $categoryId = $this->request->getGet('category_id');
        $type = $this->request->getGet('type');
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        $builder = $this->transactionModel
            ->select('
                transactions.*,
                categories.name as category_name,
                wallets.name as wallet_name,
                target_wallet.name as target_wallet_name
            ')
            ->join('categories', 'categories.id = transactions.category_id', 'left')
            ->join('wallets', 'wallets.id = transactions.wallet_id', 'left')
            ->join('wallets as target_wallet', 'target_wallet.id = transactions.transfer_to_wallet_id', 'left')
            ->where('transactions.user_id', $userId);

        if (!empty($walletId)) {
            $builder->groupStart()
                ->where('transactions.wallet_id', $walletId)
                ->orWhere('transactions.transfer_to_wallet_id', $walletId)
                ->groupEnd();
        }

        if (!empty($categoryId)) {
            $builder->where('transactions.category_id', $categoryId);
        }

        if (!empty($type)) {
            $builder->where('transactions.type', $type);
        }

        if (!empty($startDate)) {
            $builder->where('transactions.transaction_date >=', $startDate);
        }

        if (!empty($endDate)) {
            $builder->where('transactions.transaction_date <=', $endDate);
        }

        $transactions = $builder
            ->orderBy('transactions.transaction_date', 'DESC')
            ->orderBy('transactions.id', 'DESC')
            ->paginate(10, 'transactions');

        $wallets = $this->walletModel
            ->where('user_id', $userId)
            ->orderBy('is_default', 'DESC')
            ->orderBy('name', 'ASC')
            ->findAll();

        $categories = $this->categoryModel
            ->where('user_id', $userId)
            ->orderBy('name', 'ASC')
            ->findAll();

        return view('transactions/index', [
            'title' => 'Walltrack | Transaksi',
            'transactions' => $transactions,
            'pager' => $this->transactionModel->pager,
            'wallets' => $wallets,
            'categories' => $categories,
            'filters' => [
                'wallet_id' => $walletId,
                'category_id' => $categoryId,
                'type' => $type,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
        ]);
    }

    public function create()
    {
        $userId = $this->userId();

        $wallets = $this->walletModel
            ->where('user_id', $userId)
            ->orderBy('is_default', 'DESC')
            ->orderBy('name', 'ASC')
            ->findAll();

        if (empty($wallets)) {
            return redirect()
                ->to('/wallets/create')
                ->with('error', 'Buat wallet terlebih dahulu sebelum menambahkan transaksi.');
        }

        $categories = $this->categoryModel
            ->where('user_id', $userId)
            ->orderBy('name', 'ASC')
            ->findAll();

        return view('transactions/create', [
            'title' => 'Walltrack | Tambah Transaksi',
            'wallets' => $wallets,
            'categories' => $categories,
        ]);
    }

    public function store()
    {
        $userId = $this->userId();

        $validatedData = $this->validateTransactionRequest($userId);

        if ($validatedData['status'] === false) {
            return redirect()
                ->back()
                ->withInput()
                ->with($validatedData['flash_type'], $validatedData['message']);
        }

        $transactionData = $validatedData['data'];

        $this->db->transStart();

        $this->transactionModel->insert($transactionData);
        $this->applyTransactionEffect($transactionData, 1, $userId);

        $this->db->transComplete();

        if (!$this->db->transStatus()) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Transaksi gagal disimpan.');
        }

        return redirect()
            ->to('/transactions')
            ->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $userId = $this->userId();

        $transaction = $this->transactionModel
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$transaction) {
            return redirect()
                ->to('/transactions')
                ->with('error', 'Transaksi tidak ditemukan.');
        }

        $wallets = $this->walletModel
            ->where('user_id', $userId)
            ->orderBy('is_default', 'DESC')
            ->orderBy('name', 'ASC')
            ->findAll();

        $categories = $this->categoryModel
            ->where('user_id', $userId)
            ->orderBy('name', 'ASC')
            ->findAll();

        return view('transactions/edit', [
            'title' => 'Walltrack | Edit Transaksi',
            'transaction' => $transaction,
            'wallets' => $wallets,
            'categories' => $categories,
        ]);
    }

    public function update($id)
    {
        $userId = $this->userId();

        $oldTransaction = $this->transactionModel
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$oldTransaction) {
            return redirect()
                ->to('/transactions')
                ->with('error', 'Transaksi tidak ditemukan.');
        }

        $validatedData = $this->validateTransactionRequest($userId);

        if ($validatedData['status'] === false) {
            return redirect()
                ->back()
                ->withInput()
                ->with($validatedData['flash_type'], $validatedData['message']);
        }

        $newTransaction = $validatedData['data'];

        $this->db->transStart();

        // 1. Kembalikan efek saldo transaksi lama.
        $this->applyTransactionEffect($oldTransaction, -1, $userId);

        // 2. Update data transaksi.
        $this->transactionModel->update((int) $id, $newTransaction);

        // 3. Terapkan efek saldo transaksi baru.
        $this->applyTransactionEffect($newTransaction, 1, $userId);

        $this->db->transComplete();

        if (!$this->db->transStatus()) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Transaksi gagal diperbarui.');
        }

        return redirect()
            ->to('/transactions')
            ->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function delete()
    {
        $json = $this->request->getJSON();
        $transactionId = $json->id ?? null;
        $userId = $this->userId();

        if (!$transactionId) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Transaksi tidak valid.',
                'csrfHash' => csrf_hash(),
            ]);
        }

        $transaction = $this->transactionModel
            ->where('id', $transactionId)
            ->where('user_id', $userId)
            ->first();

        if (!$transaction) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan.',
                'csrfHash' => csrf_hash(),
            ]);
        }

        $this->db->transStart();

        $this->applyTransactionEffect($transaction, -1, $userId);
        $this->transactionModel->delete((int) $transactionId);

        $this->db->transComplete();

        if (!$this->db->transStatus()) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Transaksi gagal dihapus.',
                'csrfHash' => csrf_hash(),
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Transaksi berhasil dihapus.',
            'csrfHash' => csrf_hash(),
        ]);
    }

    private function validateTransactionRequest(int $userId): array
    {
        $type = $this->request->getPost('type');

        $rules = [
            'type' => 'required|in_list[income,expense,transfer]',
            'wallet_id' => 'required|is_natural_no_zero',
            'amount' => 'required|numeric|greater_than[0]',
            'transaction_date' => 'required|valid_date',
            'description' => 'permit_empty|max_length[500]',
        ];

        if ($type === 'transfer') {
            $rules['transfer_to_wallet_id'] = 'required|is_natural_no_zero';
        } else {
            $rules['category_id'] = 'required|is_natural_no_zero';
        }

        if (!$this->validate($rules)) {
            return [
                'status' => false,
                'flash_type' => 'errors',
                'message' => $this->validator->getErrors(),
            ];
        }

        $walletId = (int) $this->request->getPost('wallet_id');
        $targetWalletId = $this->request->getPost('transfer_to_wallet_id')
            ? (int) $this->request->getPost('transfer_to_wallet_id')
            : null;

        $wallet = $this->walletModel
            ->where('id', $walletId)
            ->where('user_id', $userId)
            ->first();

        if (!$wallet) {
            return [
                'status' => false,
                'flash_type' => 'error',
                'message' => 'Wallet asal tidak valid.',
            ];
        }

        $categoryId = null;

        if ($type === 'transfer') {
            if ($walletId === $targetWalletId) {
                return [
                    'status' => false,
                    'flash_type' => 'error',
                    'message' => 'Wallet tujuan tidak boleh sama dengan wallet asal.',
                ];
            }

            $targetWallet = $this->walletModel
                ->where('id', $targetWalletId)
                ->where('user_id', $userId)
                ->first();

            if (!$targetWallet) {
                return [
                    'status' => false,
                    'flash_type' => 'error',
                    'message' => 'Wallet tujuan tidak valid.',
                ];
            }
        } else {
            $categoryId = (int) $this->request->getPost('category_id');

            $category = $this->categoryModel
                ->where('id', $categoryId)
                ->where('user_id', $userId)
                ->first();

            if (!$category) {
                return [
                    'status' => false,
                    'flash_type' => 'error',
                    'message' => 'Kategori tidak valid.',
                ];
            }

            if (($category['type'] ?? null) !== $type) {
                $typeLabel = $type === 'income' ? 'Pemasukan' : 'Pengeluaran';

                return [
                    'status' => false,
                    'flash_type' => 'error',
                    'message' => 'Kategori yang dipilih tidak sesuai dengan tipe transaksi ' . $typeLabel . '.',
                ];
            }
        }

        return [
            'status' => true,
            'data' => [
                'user_id' => $userId,
                'wallet_id' => $walletId,
                'transfer_to_wallet_id' => $type === 'transfer' ? $targetWalletId : null,
                'category_id' => $type === 'transfer' ? null : $categoryId,
                'type' => $type,
                'amount' => (float) $this->request->getPost('amount'),
                'description' => trim((string) $this->request->getPost('description')),
                'transaction_date' => $this->request->getPost('transaction_date'),
            ],
        ];
    }

    private function applyTransactionEffect(array $transaction, int $direction, int $userId): void
    {
        $amount = (float) $transaction['amount'];

        if ($transaction['type'] === 'income') {
            $this->adjustWalletBalance((int) $transaction['wallet_id'], $amount * $direction, $userId);
            return;
        }

        if ($transaction['type'] === 'expense') {
            $this->adjustWalletBalance((int) $transaction['wallet_id'], -$amount * $direction, $userId);
            return;
        }

        if ($transaction['type'] === 'transfer') {
            $this->adjustWalletBalance((int) $transaction['wallet_id'], -$amount * $direction, $userId);

            if (!empty($transaction['transfer_to_wallet_id'])) {
                $this->adjustWalletBalance((int) $transaction['transfer_to_wallet_id'], $amount * $direction, $userId);
            }
        }
    }

    private function adjustWalletBalance(int $walletId, float $amount, int $userId): bool
    {
        if ($amount === 0.0) {
            return true;
        }

        $operatorAmount = $amount >= 0
            ? '+ ' . abs($amount)
            : '- ' . abs($amount);

        return $this->db->table('wallets')
            ->where('id', $walletId)
            ->where('user_id', $userId)
            ->set('current_balance', 'current_balance ' . $operatorAmount, false)
            ->set('updated_at', date('Y-m-d H:i:s'))
            ->update();
    }
}
