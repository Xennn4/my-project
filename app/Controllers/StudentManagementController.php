<?php

// app/Controllers/StudentManagementController.php

namespace App\Controllers;

use App\Models\UserModel;

/**
 * StudentManagementController
 *
 * Lists all student accounts.
 * Protected by: auth|teacher  (teacher AND admin can access)
 */
class StudentManagementController extends BaseController
{
    protected UserModel $userModel;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->userModel = new UserModel();
    }

    /**
     * List all users whose role is 'student'.
     */
    public function index()
    {
        // Only show student-role users in this management view
        $students = $this->userModel->db->table('users u')
            ->select('u.id, u.name, u.email, u.student_id, u.course, u.year_level, u.section, u.created_at, u.profile_image')
            ->join('roles r', 'r.id = u.role_id', 'left')
            ->where('r.name', 'student')
            ->where('u.deleted_at IS NULL')
            ->orderBy('u.name', 'ASC')
            ->get()->getResultArray();

        return view('teacher/students', ['students' => $students]);
    }

    /**
     * Show a single student's profile (read-only view for teachers).
     */
    public function show(int $id)
    {
        $student = $this->userModel->findWithRole($id);

        if (! $student || $student['role_name'] !== 'student') {
            session()->setFlashdata('error', 'Student not found.');
            return redirect()->to('/students');
        }

        return view('teacher/student_show', ['student' => $student]);
    }
}
