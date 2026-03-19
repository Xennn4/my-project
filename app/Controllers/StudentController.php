<?php

namespace App\Controllers;

use App\Models\UserModel;

class StudentController extends BaseController
{
    public function dashboard()
    {
        $sessionUser = session('user');

        if (! $sessionUser || empty($sessionUser['email'])) {
            return redirect()->to('/login');
        }

        $userModel = new UserModel();
        $user = $userModel->where('email', $sessionUser['email'])->first();

        if (! $user) {
            session()->destroy();
            return redirect()->to('/login');
        }

        return view('student/dashboard', ['user' => $user]);
    }
}