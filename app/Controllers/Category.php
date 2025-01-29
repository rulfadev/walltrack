<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use CodeIgniter\Controller;

class Category extends Controller
{
    public function index()
    {
        $categoryModel = new CategoryModel();
        $categories = $categoryModel->where('user_id', session()->get('user.id'))->findAll();

        $data = [
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
        $categoryModel = new CategoryModel();
        $categoryModel->insert([
            'user_id' => session()->get('user.id'),
            'name' => $this->request->getPost('name'),
        ]);

        return redirect()->to('/categories')->with('success', 'Kategori berhasil ditambahkan.');
    }
}
