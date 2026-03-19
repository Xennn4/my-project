<?php

// app/Controllers/Admin/RoleController.php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RoleModel;
use App\Models\UserModel;

/**
 * RoleController  (Admin\RoleController)
 *
 * Full CRUD for the roles table.
 * Protected by: auth|admin  (admin only via Routes.php)
 *
 * Namespace note:
 *   Place this file in:  app/Controllers/Admin/RoleController.php
 *   Reference in routes: 'Admin\RoleController::method'
 */
class RoleController extends BaseController
{
    protected RoleModel $roleModel;
    protected UserModel $userModel;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->roleModel = new RoleModel();
        $this->userModel = new UserModel();
    }

    // ── LIST ──────────────────────────────────────────────────
    public function index()
    {
        // Also count users per role for display
        $roles = $this->roleModel->db->table('roles r')
            ->select('r.*, COUNT(u.id) AS user_count')
            ->join('users u', 'u.role_id = r.id AND u.deleted_at IS NULL', 'left')
            ->groupBy('r.id')
            ->orderBy('r.id', 'ASC')
            ->get()->getResultArray();

        return view('admin/roles/index', ['roles' => $roles]);
    }

    // ── CREATE form ───────────────────────────────────────────
    public function create()
    {
        return view('admin/roles/create');
    }

    // ── STORE ─────────────────────────────────────────────────
    public function store()
    {
        $data = [
            'name'        => strtolower(trim($this->request->getPost('name'))),
            'label'       => trim($this->request->getPost('label')),
            'description' => trim($this->request->getPost('description') ?? ''),
        ];

        if (! $this->roleModel->insert($data)) {
            return redirect()->back()->withInput()
                             ->with('errors', $this->roleModel->errors());
        }

        session()->setFlashdata('success', 'Role "' . esc($data['label']) . '" created successfully.');
        return redirect()->to('/admin/roles');
    }

    // ── EDIT form ─────────────────────────────────────────────
    public function edit(int $id)
    {
        $role = $this->roleModel->find($id);

        if (! $role) {
            session()->setFlashdata('error', 'Role not found.');
            return redirect()->to('/admin/roles');
        }

        return view('admin/roles/edit', ['role' => $role]);
    }

    // ── UPDATE ────────────────────────────────────────────────
    public function update(int $id)
    {
        $role = $this->roleModel->find($id);

        if (! $role) {
            session()->setFlashdata('error', 'Role not found.');
            return redirect()->to('/admin/roles');
        }

        // On update: skip is_unique for this record's own slug
        $this->roleModel->skipValidation(true);

        $rules = [
            'name'  => "required|alpha_dash|min_length[2]|max_length[50]|is_unique[roles.name,id,{$id}]",
            'label' => 'required|min_length[2]|max_length[100]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        $this->roleModel->skipValidation(false);

        $this->roleModel->update($id, [
            'name'        => strtolower(trim($this->request->getPost('name'))),
            'label'       => trim($this->request->getPost('label')),
            'description' => trim($this->request->getPost('description') ?? ''),
        ]);

        session()->setFlashdata('success', 'Role updated successfully.');
        return redirect()->to('/admin/roles');
    }

    // ── DELETE ────────────────────────────────────────────────
    public function delete(int $id)
    {
        $role = $this->roleModel->find($id);

        if (! $role) {
            session()->setFlashdata('error', 'Role not found.');
            return redirect()->to('/admin/roles');
        }

        // Safety: prevent deleting the core admin role
        if ($role['name'] === 'admin') {
            session()->setFlashdata('error', 'The "admin" role cannot be deleted.');
            return redirect()->to('/admin/roles');
        }

        // Unset role_id for any users assigned to this role before deleting
        $this->userModel->db->table('users')
            ->where('role_id', $id)
            ->update(['role_id' => null]);

        $this->roleModel->delete($id);

        session()->setFlashdata('success', 'Role "' . esc($role['label']) . '" deleted. Affected users have been unassigned.');
        return redirect()->to('/admin/roles');
    }
}
