<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RoleModel;
use App\Models\GroupModel;
use CodeIgniter\Controller;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;

class UserController extends Controller
{
    protected $userModel;
    protected $roleModel;
    protected $groupModel;
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

    private function userValidationRules($isUpdate = false)
    {
        $rules = [
            'username' => 'required|alpha_numeric_punct|min_length[5]|max_length[20]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]',
        ];

        if ($isUpdate) {
            $rules['username'] .= '|permit_empty';
            $rules['email'] .= '|permit_empty';
            $rules['password'] = 'permit_empty|min_length[8]';
            $rules['confirm_password'] = 'permit_empty|matches[password]';
        }

        return $rules;
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
        $rules = $this->userValidationRules();
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validation->getErrors());
        }

        $data = [
            'username' => $this->request->getVar('username'),
            'email' => $this->request->getVar('email'),
            'password' => password_hash($this->request->getVar('password'), PASSWORD_BCRYPT),
        ];

        $this->userModel->save($data);
        $this->session->setFlashdata('success', 'User created successfully');
        return redirect()->to('/user');
    }

    public function edit($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            throw PageNotFoundException::forPageNotFound('User not found');
        }
        
        $data['user'] = $user;
        return view('user/edit', $data);
    }

    public function update($id)
    {
        $rules = $this->userValidationRules(true);
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validation->getErrors());
        }

        $data = [
            'username' => $this->request->getVar('username'),
            'email' => $this->request->getVar('email'),
        ];

        if ($this->request->getVar('password')) {
            $data['password'] = password_hash($this->request->getVar('password'), PASSWORD_BCRYPT);
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
        $user = $this->userModel->find($id);
        if (!$user) {
            throw PageNotFoundException::forPageNotFound('User not found');
        }

        $roles = $this->roleModel->findAll();
        $currentRoles = $this->groupModel->where('user_id', $id)->findAll();
        $currentRoleIds = array_column($currentRoles, 'role_id');

        $data = [
            'user' => $user,
            'roles' => $roles,
            'currentRoleIds' => $currentRoleIds,
        ];

        return view('user/assign_role', $data);
    }

    public function store_assigned_roles($id)
    {
        $userId = $this->request->getVar('user_id');
        $roleIds = $this->request->getVar('role_ids');

        if ($userId != $id) {
            throw new \Exception('User ID mismatch');
        }

        $db = \Config\Database::connect();

        $db->transBegin();

        try {
            $this->groupModel->where('user_id', $id)->delete();

            if ($roleIds) {
                foreach ($roleIds as $roleId) {
                    $this->groupModel->save([
                        'user_id' => $userId,
                        'role_id' => $roleId,
                    ]);
                }
            }

            $db->transCommit();
            return redirect()->to('/user')->with('success', 'Roles assigned successfully.');
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', $e->getMessage());
            return redirect()->back()->with('error', 'Failed to assign roles.');
        }
    }
}
