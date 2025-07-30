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
                    'required' => '{field} is required.',
                    'min_length' => '{field} must be at least 3 characters.',
                    'is_unique' => '{field} already exists.',
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email|is_unique[users.email]',
                'errors' => [
                    'required' => '{field} is required.',
                    'valid_email' => '{field} is not valid.',
                    'is_unique' => '{field} already exists.',
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[5]',
                'errors' => [
                    'required' => 'Password is required.',
                    'min_length' => 'Password must be at least 5 characters.',
                ]
            ],
            'pass_confirm' => [
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => 'Password confirmation is required.',
                    'matches' => 'Passwords do not match.',
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

        return redirect()->to('/login')->with('success', 'Registration successful!');
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
                    'required' => '{field} is required.',
                    'valid_email' => '{field} is not valid.',
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
            return redirect()->back()->with('error', 'Wrong Password.');
        }
        return redirect()->back()->with('error', 'Email is not registered.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
