<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use CodeIgniter\Controller;

class Category extends Controller
{
    protected $categoryModel;
    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }
    public function index()
    {
        $categories = $this->categoryModel->where('user_id', session()->get('user.id'))->findAll();

        $data = [
            "modalId" => 'deleteModal',
            "modalIdLabel" => 'deleteModalLabel',
            "modalTitle" => 'Konfirmasi Hapus',
            "modalBody" => 'Apakah Anda yakin ingin menghapus kategori ini?',
            "modalConfirm" => 'confirmDelete',
            "modalConfirmText" => 'Hapus',
            "title" => 'Walltrack | Kategori Transaksi',
            "categories" => $categories
        ];

        return view('categories/index', $data);
    }

    public function create()
    {
        $data = [
            "title" => 'Walltrack | Tambah Kategori'
        ];
        return view('categories/create', $data);
    }

    public function store()
    {
        $this->categoryModel->insert([
            'user_id' => session()->get('user.id'),
            'name' => $this->request->getPost('name'),
        ]);

        return redirect()->to('/categories')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function delete()
    {
        $json = $this->request->getJSON();
        $categoryId = $json->id ?? null;

        if ($categoryId) {
            $deleted = $this->categoryModel->where('id', $categoryId)->delete();
            session()->setFlashdata('success', 'Kategori berhasil dihapus!');
            return $this->response->setJSON(['success' => (bool) $deleted]);
        }
        session()->setFlashdata('error', 'Kategori tidak bisa dihapus!');
        return $this->response->setJSON(['success' => false]);
    }
}
