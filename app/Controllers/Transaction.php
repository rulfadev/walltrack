<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use App\Models\CategoryModel;
use CodeIgniter\Controller;

class Transaction extends Controller
{
    public function index()
    {
        $transactionModel = new TransactionModel();
        $transactions = $transactionModel
            ->where('user_id', session()->get('user.id'))
            ->findAll();

        $data = [
            "title" => 'Walltrack | Transaksi',
            "transactions" => $transactions
        ];

        return view('transactions/index', $data);
    }

    public function create()
    {
        $categoryModel = new CategoryModel();
        $categories = $categoryModel->where('user_id', session()->get('user.id'))->findAll();

        $data = [
            "title" => 'Walltrack | Tambah Transaksi',
            "categories" => $categories
        ];

        return view('transactions/create', $data);
    }

    public function store()
    {
        $transactionModel = new TransactionModel();

        $transactionModel->insert([
            'user_id' => session()->get('user.id'),
            'category_id' => $this->request->getPost('category_id'),
            'amount' => $this->request->getPost('amount'),
            'description' => $this->request->getPost('description'),
            'transaction_date' => $this->request->getPost('transaction_date'),
        ]);

        return redirect()->to('/transactions')->with('success', 'Transaksi berhasil ditambahkan.');
    }
}
