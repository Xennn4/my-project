<?php

namespace App\Controllers;

use App\Models\UserModel;

class ProfileController extends BaseController
{
    public function show()
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

        return view('profile/show', ['user' => $user]);
    }

    public function edit()
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

        return view('profile/edit', ['user' => $user]);
    }

    public function update()
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

        $userId = $user['id'];

        $rules = [
            'name'          => 'required|min_length[3]',
            'email'         => "required|valid_email|is_unique[users.email,id,{$userId}]",
            'student_id'    => 'permit_empty|max_length[20]',
            'course'        => 'permit_empty|max_length[100]',
            'year_level'    => 'permit_empty|integer|greater_than_equal_to[1]|less_than_equal_to[5]',
            'section'       => 'permit_empty|max_length[50]',
            'phone'         => 'permit_empty|max_length[20]',
            'address'       => 'permit_empty',
            'profile_image' => 'if_exist|is_image[profile_image]|mime_in[profile_image,image/jpg,image/jpeg,image/png,image/webp]|max_size[profile_image,2048]'
        ];

        if (! $this->validate($rules)) {
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

        if ($file && $file->isValid() && ! $file->hasMoved()) {
            if (! empty($user['profile_image'])) {
                $oldPath = FCPATH . 'uploads/profiles/' . $user['profile_image'];
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $ext = $file->getExtension();
            $newName = 'avatar_' . $userId . '_' . time() . '.' . $ext;
            $file->move(FCPATH . 'uploads/profiles/', $newName);

            $updateData['profile_image'] = $newName;
        }

        $userModel->update($userId, $updateData);

        // update session with new values
        session()->set([
            'user' => [
                'id'    => $userId,
                'name'  => $updateData['name'],
                'email' => $updateData['email'],
                'role'  => $sessionUser['role'],
            ],
        ]);

        return redirect()->to('/profile')->with('success', 'Profile updated successfully.');
    }
}