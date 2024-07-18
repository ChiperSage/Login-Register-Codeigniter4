<?php

namespace App\Controllers;

use App\Models\AuthModel;
use Config\Services;

class AuthController extends Controller
{
    protected $maxLoginAttempts = 5;
    protected $lockoutTime = 900; // 15 minutes

    public function login()
    {
        return view('auth/login_form');
    }

    public function login_with_captcha()
    {
        return view('auth/login_captcha_form');
    }

    public function authenticate()
    {
        $session = session();
        $model = new AuthModel();
        
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');
        $rememberMe = $this->request->getVar('remember_me');

        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $user = $model->where('username', $username)->first();
        
        if ($user) {
            if ($user['login_attempts'] >= $this->maxLoginAttempts && (time() - $user['last_login_attempt']) < $this->lockoutTime) {
                $session->setFlashdata('error', 'Your account is locked. Please try again later.');
                return redirect()->back();
            }

            if (password_verify($password, $user['password'])) {
                $sessionData = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'isLoggedIn' => true,
                ];
                $session->set($sessionData);

                if ($rememberMe) {
                    $this->setRememberMe($user);
                }

                $model->update($user['id'], ['login_attempts' => 0, 'last_login_attempt' => time()]);
                
                return redirect()->to('/dashboard');
            } else {
                $model->update($user['id'], [
                    'login_attempts' => $user['login_attempts'] + 1,
                    'last_login_attempt' => time()
                ]);
                $session->setFlashdata('error', 'Invalid login credentials');
                return redirect()->back();
            }
        } else {
            $session->setFlashdata('error', 'Invalid login credentials');
            return redirect()->back();
        }
    }

    public function authenticateWithCaptcha()
    {
        $session = session();
        $model = new AuthModel();
        
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');
        $rememberMe = $this->request->getVar('remember_me');
        $captchaResponse = $this->request->getVar('g-recaptcha-response');
        $secretKey = 'YOUR_SECRET_KEY'; // Replace with your reCAPTCHA secret key

        if (!$this->validateCaptcha($captchaResponse, $secretKey)) {
            $session->setFlashdata('error', 'Invalid CAPTCHA');
            return redirect()->back();
        }

        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $user = $model->where('username', $username)->first();
        
        if ($user) {
            if ($user['login_attempts'] >= $this->maxLoginAttempts && (time() - $user['last_login_attempt']) < $this->lockoutTime) {
                $session->setFlashdata('error', 'Your account is locked. Please try again later.');
                return redirect()->back();
            }

            if (password_verify($password, $user['password'])) {
                $sessionData = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'isLoggedIn' => true,
                ];
                $session->set($sessionData);

                if ($rememberMe) {
                    $this->setRememberMe($user);
                }

                $model->update($user['id'], ['login_attempts' => 0, 'last_login_attempt' => time()]);
                
                return redirect()->to('/dashboard');
            } else {
                $model->update($user['id'], [
                    'login_attempts' => $user['login_attempts'] + 1,
                    'last_login_attempt' => time()
                ]);
                $session->setFlashdata('error', 'Invalid login credentials');
                return redirect()->back();
            }
        } else {
            $session->setFlashdata('error', 'Invalid login credentials');
            return redirect()->back();
        }
    }

    public function register()
    {
        return view('auth/register_form');
    }

    public function store()
    {
        $session = session();
        $model = new AuthModel();

        $rules = [
            'username' => 'required|is_unique[users.username]',
            'password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username' => $this->request->getVar('username'),
            'password' => $this->request->getVar('password'),
        ];

        $model->save($data);
        $session->setFlashdata('success', 'Registration successful! You can now login.');
        return redirect()->to('/login');
    }

    public function logout()
    {
        session()->destroy();
        $this->clearRememberMe();
        return redirect()->to('/login');
    }

    private function setRememberMe($user)
    {
        $token = bin2hex(random_bytes(16));
        set_cookie('remember_me', $token, 30*24*60*60); // 30 days
        $model = new AuthModel();
        $model->update($user['id'], ['remember_me_token' => $token]);
    }

    private function clearRememberMe()
    {
        delete_cookie('remember_me');
    }

    private function validateCaptcha($response, $secret)
    {
        $recaptcha = Services::curlrequest();
        $response = $recaptcha->post('https://www.google.com/recaptcha/api/siteverify', [
            'form_params' => [
                'secret' => $secret,
                'response' => $response,
            ]
        ]);

        $result = json_decode($response->getBody(), true);
        return $result['success'];
    }
}

