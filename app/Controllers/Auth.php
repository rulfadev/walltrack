<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;
    public function __construct()
    {
        $this->userModel = new UserModel();
    }
    public function register()
    {
        return view('auths/register');
    }

    public function processRegister()
    {
        $rules = [
            'username' => [
                'rules' => 'required|min_length[3]|is_unique[users.username]',
                'errors' => [
                    'required' => '{field} harus diisi.',
                    'min_length' => '{field} minimal 3 karakter.',
                    'is_unique' => '{field} sudah ada.',
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email|is_unique[users.email]',
                'errors' => [
                    'required' => '{field} harus diisi.',
                    'valid_email' => '{field} tidak valid.',
                    'is_unique' => '{field} sudah ada.',
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[5]',
                'errors' => [
                    'required' => 'kata sandi harus diisi.',
                    'min_length' => 'kata sandi minimal 5 karakter.',
                ]
            ],
            'pass_confirm' => [
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => 'konfirmasi kata sandi harus diisi.',
                    'matches' => 'kata sandi yang diulang tidak sama.',
                ]
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->userModel->save([
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
        ]);

        return redirect()->to('/login')->with('success', 'Registrasi berhasil!');
    }

    public function login()
    {
        return view('auths/login');
    }

    public function processLogin()
    {
        $rules = [
            'password' => 'required',
            'email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => '{field} harus diisi.',
                    'valid_email' => '{field} tidak valid.',
                ]
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Validasi Input
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        // Cari User Berdasarkan Email
        $user = $this->userModel->where('email', $email)->first();
        if ($user) {
            if (password_verify($password, $user['password'])) {
                // Set Session
                session()->set([
                    'isLoggedIn' => true,
                    'user' => $user,
                ]);

                return redirect()->to('/dashboard');
            }
            return redirect()->back()->with('error', 'Password salah.');
        }
        return redirect()->back()->with('error', 'Email tersebut tidak terdaftar.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
