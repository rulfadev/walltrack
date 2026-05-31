<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\TransactionModel;

class Category extends BaseController
{
    protected CategoryModel $categoryModel;
    protected TransactionModel $transactionModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
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

        $type = $this->request->getGet('type');

        $builder = $this->categoryModel
            ->where('user_id', $userId)
            ->orderBy('type', 'ASC')
            ->orderBy('name', 'ASC');

        if (in_array($type, ['income', 'expense'], true)) {
            $builder->where('type', $type);
        }

        $categories = $builder->findAll();

        return view('categories/index', [
            'title' => 'Walltrack | Kategori Transaksi',
            'categories' => $categories,
            'filters' => [
                'type' => $type,
            ],
        ]);
    }

    public function create()
    {
        return view('categories/create', [
            'title' => 'Walltrack | Tambah Kategori',
        ]);
    }

    public function store()
    {
        $rules = [
            'name' => 'required|min_length[2]|max_length[100]',
            'type' => 'required|in_list[income,expense]',
            'icon' => 'permit_empty|max_length[50]',
            'color' => 'permit_empty|max_length[20]',
        ];

        if (!$this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->categoryModel->insert([
            'user_id' => $this->userId(),
            'name' => trim((string) $this->request->getPost('name')),
            'type' => $this->request->getPost('type'),
            'icon' => $this->request->getPost('icon') ?: 'bi-tag',
            'color' => $this->request->getPost('color') ?: '#134686',
        ]);

        return redirect()
            ->to('/categories')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $userId = $this->userId();

        $category = $this->categoryModel
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$category) {
            return redirect()
                ->to('/categories')
                ->with('error', 'Kategori tidak ditemukan.');
        }

        return view('categories/edit', [
            'title' => 'Walltrack | Edit Kategori',
            'category' => $category,
        ]);
    }

    public function update($id)
    {
        $userId = $this->userId();

        $category = $this->categoryModel
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$category) {
            return redirect()
                ->to('/categories')
                ->with('error', 'Kategori tidak ditemukan.');
        }

        $rules = [
            'name' => 'required|min_length[2]|max_length[100]',
            'type' => 'required|in_list[income,expense]',
            'icon' => 'permit_empty|max_length[50]',
            'color' => 'permit_empty|max_length[20]',
        ];

        if (!$this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $newType = $this->request->getPost('type');

        $hasTransaction = $this->transactionModel
            ->where('user_id', $userId)
            ->where('category_id', $id)
            ->first();

        if ($hasTransaction && $category['type'] !== $newType) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Tipe kategori tidak bisa diubah karena kategori ini sudah dipakai pada transaksi.');
        }

        $this->categoryModel->update((int) $id, [
            'name' => trim((string) $this->request->getPost('name')),
            'type' => $newType,
            'icon' => $this->request->getPost('icon') ?: 'bi-tag',
            'color' => $this->request->getPost('color') ?: '#134686',
        ]);

        return redirect()
            ->to('/categories')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function delete()
    {
        $json = $this->request->getJSON();
        $categoryId = $json->id ?? null;
        $userId = $this->userId();

        if (!$categoryId) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Kategori tidak valid.',
                'csrfHash' => csrf_hash(),
            ]);
        }

        $category = $this->categoryModel
            ->where('id', $categoryId)
            ->where('user_id', $userId)
            ->first();

        if (!$category) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Kategori tidak ditemukan.',
                'csrfHash' => csrf_hash(),
            ]);
        }

        $hasTransaction = $this->transactionModel
            ->where('user_id', $userId)
            ->where('category_id', $categoryId)
            ->first();

        if ($hasTransaction) {
            return $this->response->setStatusCode(422)->setJSON([
                'success' => false,
                'message' => 'Kategori tidak bisa dihapus karena sudah dipakai pada transaksi.',
                'csrfHash' => csrf_hash(),
            ]);
        }

        $this->categoryModel->delete((int) $categoryId);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Kategori berhasil dihapus.',
            'csrfHash' => csrf_hash(),
        ]);
    }
}