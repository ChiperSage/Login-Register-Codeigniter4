<?php

namespace App\Controllers;

use App\Models\AuthModel;
use Config\Services;
use CodeIgniter\Controller;

class AuthController extends Controller
{
    protected $maxLoginAttempts = 5;
    protected $lockoutTime = 900; // 15 minutes
    protected $session;
    protected $validation;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
    }

    public function login()
    {
        return view('auth/login_form');
    }

    public function authenticate()
    {
        $model = new AuthModel();
        $rules = [
            'identity' => 'required',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->redirectBackWithErrors();
        }

        $identity = $this->request->getPost('identity');
        $password = $this->request->getPost('password');

        $user = $model->where('username', $identity)->first();

        if ($user && password_verify($password, $user['password'])) {
            // $this->setUserSession($user);

            $this->session->set([
                'user_id' => $user['user_id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'isLoggedIn' => true,
            ]);

            return redirect()->to('/user');
        } else {
            $this->session->setFlashdata('error', 'Invalid login credentials');
            return redirect()->back();
        }
    }

    public function register()
    {
        return view('auth/register_form');
    }

    public function store()
    {
        $model = new AuthModel();

        $rules = [
            'username' => 'required|alpha_numeric_punct|min_length[5]|max_length[20]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username' => $this->request->getVar('username'),
            'email' => $this->request->getVar('email'),
            'password' => password_hash($this->request->getVar('password'), PASSWORD_BCRYPT),
        ];

        $model->save($data);
        $session->setFlashdata('success', 'Registration successful! You can now login.');
        return redirect()->to('/login');
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/login');
    }

    private function setUserSession($user)
    {
        $this->session->set([
            'user_id' => $user['user_id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'isLoggedIn' => true,
        ]);
    }

    private function redirectBackWithErrors()
    {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }
}
