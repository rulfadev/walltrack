<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use App\Models\CategoryModel;
use CodeIgniter\Controller;

class Transaction extends Controller
{
    protected $transactionModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $userId = session()->get('user.id');

        $transactions = $this->transactionModel
            ->select('transactions.*, categories.name as category_name')
            ->join('categories', 'categories.id = transactions.category_id')
            ->where('transactions.user_id', $userId)
            ->findAll();

        $data = [
            "modalId" => 'deleteModal',
            "modalIdLabel" => 'deleteModalLabel',
            "modalTitle" => 'Konfirmasi Hapus',
            "modalBody" => 'Apakah Anda yakin ingin menghapus transaksi ini?',
            "modalConfirm" => 'confirmDelete',
            "modalConfirmText" => 'Hapus',
            "title" => 'Walltrack | Transaksi',
            "transactions" => $transactions
        ];

        return view('transactions/index', $data);
    }

    public function create()
    {
        $categories = $this->categoryModel->where('user_id', session()->get('user.id'))->findAll();

        $data = [
            "title" => 'Walltrack | Tambah Transaksi',
            "categories" => $categories
        ];

        return view('transactions/create', $data);
    }

    public function store()
    {
        $this->transactionModel->insert([
            'user_id' => session()->get('user.id'),
            'category_id' => $this->request->getPost('category_id'),
            'type' => $this->request->getPost('type'),
            'amount' => $this->request->getPost('amount'),
            'description' => $this->request->getPost('description'),
            'transaction_date' => $this->request->getPost('transaction_date'),
        ]);

        return redirect()->to('/transactions')->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function delete()
    {
        $json = $this->request->getJSON();
        $transactionId = $json->id ?? null;

        if ($transactionId) {
            $deleted = $this->transactionModel->where('id', $transactionId)->delete();
            session()->setFlashdata('success', 'Transaksi berhasil dihapus!');
            return $this->response->setJSON(['success' => (bool) $deleted]);
        }
        session()->setFlashdata('error', 'Transaksi tidak bisa dihapus!');
        return $this->response->setJSON(['success' => false]);
    }
}
