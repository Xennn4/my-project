<?php

namespace App\Controllers;

use App\Models\UserModel;

class Home extends BaseController
{
    public function index()
    {
        $data = array_merge($this->data, [
            'title' => 'Dashboard Page'
        ]);

        return view('dashboard', $data);
    }

    public function dashboardV2()
    {
        $data = array_merge($this->data, [
            'title' => 'Dashboard v2 Page'
        ]);

        return view('dashboard', $data);
    }

    public function dashboardV3()
    {
        $data = array_merge($this->data, [
            'title' => 'Dashboard v3 Page'
        ]);

        return view('dashboard', $data);
    }

    public function profile()
    {
        $username = session()->get('username');

        if (!$username) {
            return redirect()->to('/login');
        }

        $userModel = new UserModel();
        $user = $userModel->where('email', $username)->first();

        if (!$user) {
            session()->destroy();
            return redirect()->to('/login');
        }

        $data = array_merge($this->data, [
            'title' => 'My Profile',
            'user'  => $user
        ]);

        return view('profile/show', $data);
    }

    public function editProfile()
    {
        $username = session()->get('username');

        if (!$username) {
            return redirect()->to('/login');
        }

        $userModel = new UserModel();
        $user = $userModel->where('email', $username)->first();

        if (!$user) {
            session()->destroy();
            return redirect()->to('/login');
        }

        $data = array_merge($this->data, [
            'title' => 'Edit Profile',
            'user'  => $user
        ]);

        return view('profile/edit', $data);
    }

    public function updateProfile()
    {
        $username = session()->get('username');

        if (!$username) {
            return redirect()->to('/login');
        }

        $userModel = new UserModel();
        $user = $userModel->where('email', $username)->first();

        if (!$user) {
            session()->destroy();
            return redirect()->to('/login');
        }

        $userId = $user['id'];

        $rules = [
            'name'          => 'required|min_length[3]',
            'email'         => "required|valid_email|is_unique[users.email,id,{$userId}]",
            'student_id'    => 'permit_empty|max_length[20]',
            'course'        => 'permit_empty|max_length[100]',
            'year_level'    => 'permit_empty|integer',
            'section'       => 'permit_empty|max_length[50]',
            'phone'         => 'permit_empty|max_length[20]',
            'address'       => 'permit_empty',
            'profile_image' => 'if_exist|is_image[profile_image]|mime_in[profile_image,image/jpg,image/jpeg,image/png,image/webp]|max_size[profile_image,2048]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $updateData = [
            'name'       => $this->request->getPost('name'),
            'email'      => $this->request->getPost('email'),
            'student_id' => $this->request->getPost('student_id'),
            'course'     => $this->request->getPost('course'),
            'year_level' => $this->request->getPost('year_level'),
            'section'    => $this->request->getPost('section'),
            'phone'      => $this->request->getPost('phone'),
            'address'    => $this->request->getPost('address'),
        ];

        $file = $this->request->getFile('profile_image');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $uploadPath = FCPATH . 'uploads/profiles/';

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            if (!empty($user['profile_image'])) {
                $oldPath = $uploadPath . $user['profile_image'];
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $newName = 'avatar_' . $userId . '_' . time() . '.' . $file->getExtension();
            $file->move($uploadPath, $newName);
            $updateData['profile_image'] = $newName;
        }

        $userModel->update($userId, $updateData);

        session()->set([
            'user_id'    => $userId,
            'username'   => $updateData['email'],
            'user_name'  => $updateData['name'],
            'isLoggedIn' => true
        ]);

        return redirect()->to('/dashboard/profile')->with('success', 'Profile updated successfully.');
    }
}