<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class UserController extends Controller
{
    public function index()
    {
        $model = new UserModel();
        $data['users'] = $model->findAll();
        
        return view('user/user_list', $data);
    }

    public function create()
    {
        return view('user/user_add');
    }

    public function store()
    {
        $model = new UserModel();

        $rules = [
            'username' => 'required|alpha_numeric_punct|min_length[3]|max_length[20]|is_unique[users.username]',
            'password' => 'required|min_length[8]|regex_match[/(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W)/]',
            'email' => 'required|valid_email|is_unique[users.email]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'password' => $this->request->getPost('password'),
            'email' => $this->request->getPost('email')
        ];

        $model->save($data);

        return redirect()->to('/user')->with('success', 'User added successfully');
    }

    public function edit($id)
    {
        $model = new UserModel();
        $data['user'] = $model->find($id);

        return view('user/user_edit', $data);
    }

    public function update($id)
    {
        $model = new UserModel();

        $rules = [
            'username' => "required|alpha_numeric_punct|min_length[3]|max_length[20]|is_unique[users.username,id,{$id}]",
            'password' => 'permit_empty|min_length[8]|regex_match[/(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W)/]',
            'email' => "required|valid_email|is_unique[users.email,id,{$id}]"
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email')
        ];

        if ($this->request->getPost('password')) {
            $data['password'] = $this->request->getPost('password');
        }

        $model->update($id, $data);

        return redirect()->to('/user')->with('success', 'User updated successfully');
    }

    public function delete($id)
    {
        $model = new UserModel();
        $model->delete($id);

        return redirect()->to('/user')->with('success', 'User deleted successfully');
    }
}
