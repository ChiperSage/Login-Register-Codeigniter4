<?php

// File: app/Controllers/UserController.php
namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RoleModel;
use App\Models\GroupModel;
use CodeIgniter\Controller;

class UserController extends Controller
{
    protected $userModel;
    protected $roleModel;
    protected $session;
    protected $validation;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();
        $this->groupModel = new GroupModel();

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

    public function assign_role($id)
    {
        // $data['user'] = $this->userModel->find($id);
        // $data['roles'] = $this->roleModel->findAll();
        // return view('user/assign_role', $data);

        $user = $this->userModel->find($id);

        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('User not found');
        }

        $roles = $this->roleModel->findAll();
        $currentRoles = $this->groupModel->where('user_id', $id)->findAll();
        $currentRoleIds = array_column($currentRoles, 'role_id');

        $data = [
            'user' => $user,
            'roles' => $roles,
            'currentRoleIds' => $currentRoleIds
        ];

        return view('user/assign_role', $data);
    }

    public function store_assigned_roles($id)
    {
        // Retrieve user ID and roles from the request
        $userId = $this->request->getVar('user_id');
        $roleIds = $this->request->getVar('role_ids'); // Ensure this matches the form field name

        // Ensure the user ID matches the route parameter
        if ($userId != $id) {
            throw new \Exception('User ID mismatch');
        }

        // Get the database connection
        $db = \Config\Database::connect();

        // Start a transaction
        $db->transBegin();

        try {
            // Delete existing roles for the user
            $db->table('groups')->where('user_id', $id)->delete();

            // Insert new roles if any
            if ($roleIds) {
                foreach ($roleIds as $roleId) {
                    $data = [
                        'user_id' => $userId,
                        'role_id' => $roleId,
                    ];
                    $db->table('groups')->insert($data);
                }
            }

            // Commit the transaction
            $db->transCommit();

            // Redirect with success message
            return redirect()->to('/user')->with('success', 'Roles assigned successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction if something goes wrong
            $db->transRollback();

            // Log the exception and redirect with an error message
            log_message('error', $e->getMessage());
            return redirect()->back()->with('error', 'Failed to assign roles.');
        }
    }


}
