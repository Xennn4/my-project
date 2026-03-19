<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function index()
    {
        // If already logged in
        if (session()->has('user')) {
            return redirect()->to(base_url('dashboard'));
        }

        // If GET request → show login page
        if (!$this->request->is('post')) {
            return view('auth/login');
        }

        $inputEmail    = trim((string) $this->request->getPost('inputEmail'));
        $inputPassword = trim((string) $this->request->getPost('inputPassword'));

        $db = \Config\Database::connect();

        // JOIN users + roles
        $builder = $db->table('users');
        $builder->select('users.*, roles.name as role_name');
        $builder->join('roles', 'roles.id = users.role_id', 'left');
        $builder->where('users.email', $inputEmail);

        $user = $builder->get()->getRowArray();

        if (!$user) {
            session()->setFlashdata('notif_error', 'User not found.');
            return redirect()->to(base_url('login'));
        }

        if (!password_verify($inputPassword, $user['password'])) {
            session()->setFlashdata('notif_error', 'Wrong password.');
            return redirect()->to(base_url('login'));
        }

        // STORE ROLE IN SESSION (VERY IMPORTANT)
        session()->set([
            'user' => [
                'id'    => $user['id'],
                'name'  => $user['name'],
                'email' => $user['email'],
                'role'  => $user['role_name'], // ← REQUIRED FOR FILTERS
            ],
        ]);

        //REDIRECT BASED ON ROLE
        $role = $user['role_name'];

        return match ($role) {
            'admin'   => redirect()->to('/dashboard'),
            'teacher' => redirect()->to('/dashboard'),
            'student' => redirect()->to('/student/dashboard'),
            default   => redirect()->to('/dashboard'),
        };
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'));
    }

    public function register()
    {
        return view('auth/register');
    }

    public function registration()
    {
        $inputName      = trim((string) $this->request->getPost('inputFullname'));
        $inputEmail     = trim((string) $this->request->getPost('inputEmail'));
        $inputPassword  = trim((string) $this->request->getPost('inputPassword'));
        $inputPassword2 = trim((string) $this->request->getPost('inputPassword2'));

        if ($inputName === '' || $inputEmail === '' || $inputPassword === '' || $inputPassword2 === '') {
            session()->setFlashdata('notif_error', 'All fields are required.');
            return redirect()->to(base_url('register'));
        }

        if ($inputPassword !== $inputPassword2) {
            session()->setFlashdata('notif_error', 'Passwords do not match.');
            return redirect()->to(base_url('register'));
        }

        $userModel = new UserModel();

        $existing = $userModel->where('email', $inputEmail)->first();
        if ($existing) {
            session()->setFlashdata('notif_error', 'Email already exists.');
            return redirect()->to(base_url('register'));
        }

        // Default role = student (role_id = 3)
        $saved = $userModel->insert([
            'name'     => $inputName,
            'email'    => $inputEmail,
            'password' => password_hash($inputPassword, PASSWORD_DEFAULT),
            'role_id'  => 3 // student
        ]);

        if (!$saved) {
            session()->setFlashdata('notif_error', 'Registration failed.');
            return redirect()->to(base_url('register'));
        }

        session()->setFlashdata('notif_success', 'Registration successful. Please login.');
        return redirect()->to(base_url('login'));
    }

    // Unauthorized Page
    public function unauthorized()
    {
        return view('errors/unauthorized');
    }
}