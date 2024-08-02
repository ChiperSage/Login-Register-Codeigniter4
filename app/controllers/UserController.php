<?php

// File: app/Controllers/UserController.php
namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class UserController extends Controller
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {
        $data['users'] = $this->userModel->findAll();
        return view('user/index', $data);
    }

    public function create()
    {
        return view('user/create');
    }

    public function store()
    {
        $rules = [
            'username' => 'required|alpha_numeric_punct|min_length[5]|max_length[20]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validation->getErrors());
        }

        $data = [
            'username' => $this->request->getVar('username'),
            'email' => $this->request->getVar('email'),
            'password' => $this->request->getVar('password'),
        ];

        $this->userModel->save($data);
        $this->session->setFlashdata('success', 'User created successfully');
        return redirect()->to('/user');
    }

    public function edit($id)
    {
        $data['user'] = $this->userModel->find($id);
        return view('user/edit', $data);
    }

    public function update($id)
    {
        $rules = [
            'username' => 'required|alpha_numeric_punct|min_length[5]|max_length[20]',
            'email' => 'required|valid_email',
        ];

        // Only validate password fields if they are not empty
        if ($this->request->getVar('password')) {
            $rules['password'] = 'required|min_length[8]';
            $rules['confirm_password'] = 'required|matches[password]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validation->getErrors());
        }

        $data = [
            'username' => $this->request->getVar('username'),
            'email' => $this->request->getVar('email'),
        ];

        if ($this->request->getVar('password')) {
            $data['password'] = $this->request->getVar('password');
        }

        $this->userModel->update($id, $data);
        $this->session->setFlashdata('success', 'User updated successfully');
        return redirect()->to('/user');
    }

    public function delete($id)
    {
        $this->userModel->delete($id);
        $this->session->setFlashdata('success', 'User deleted successfully');
        return redirect()->to('/user');
    }
}
