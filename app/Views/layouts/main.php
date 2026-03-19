<!DOCTYPE html>
<html>
<head>
    <title>CI4 CRUD Exam</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php
    $sessionUser = session('user');
    $isLoggedIn  = ! empty($sessionUser);
    $role        = $sessionUser['role'] ?? null;

    $navbarClass = match ($role) {
        'admin'   => 'bg-danger',
        'teacher' => 'bg-success',
        'student' => 'bg-primary',
        default   => 'bg-dark',
    };

    // ONE PROFILE URL FOR ALL
    $profileUrl = site_url('profile');

    $backDashboard = match ($role) {
        'admin'   => site_url('dashboard'),
        'teacher' => site_url('dashboard'),
        'student' => site_url('student/dashboard'),
        default   => site_url('dashboard'),
    };

    $user = null;

    if ($isLoggedIn) {
        $userModel = new \App\Models\UserModel();
        $user = $userModel->where('email', $sessionUser['email'])->first();
    }
?>

<!-- NAVBAR -->
<nav class="navbar navbar-dark <?= $navbarClass ?> navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="<?= $backDashboard ?>">CI4 CRUD Exam</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto align-items-center">

                <?php if ($isLoggedIn): ?>

                    <?php if ($role === 'admin'): ?>
                        <li class="nav-item me-2">
                            <a href="<?= site_url('dashboard') ?>" class="nav-link text-white">Dashboard</a>
                        </li>
                        <li class="nav-item me-2">
                            <a href="<?= site_url('students') ?>" class="nav-link text-white">Students</a>
                        </li>
                        <li class="nav-item me-2">
                            <a href="<?= site_url('admin/roles') ?>" class="nav-link text-white">Roles</a>
                        </li>
                        <li class="nav-item me-2">
                            <a href="<?= site_url('admin/users') ?>" class="nav-link text-white">Users</a>
                        </li>

                    <?php elseif ($role === 'teacher'): ?>
                        <li class="nav-item me-2">
                            <a href="<?= site_url('dashboard') ?>" class="nav-link text-white">Dashboard</a>
                        </li>
                        <li class="nav-item me-2">
                            <a href="<?= site_url('students') ?>" class="nav-link text-white">Students</a>
                        </li>
                        <li class="nav-item me-2">
                            <a href="<?= site_url('records') ?>" class="nav-link text-white">Records</a>
                        </li>

                    <?php elseif ($role === 'student'): ?>
                        <li class="nav-item me-2">
                            <a href="<?= site_url('student/dashboard') ?>" class="nav-link text-white">Dashboard</a>
                        </li>
                    <?php endif; ?>

                    <!-- PROFILE IMAGE -->
                    <li class="nav-item me-2">
                        <?php if (!empty($user['profile_image'])): ?>
                            <img src="<?= base_url('uploads/profiles/' . $user['profile_image']) ?>"
                                 style="width:40px;height:40px;border-radius:50%;object-fit:cover;border:2px solid white;">
                        <?php else: ?>
                            <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png"
                                 style="width:40px;height:40px;border-radius:50%;object-fit:cover;border:2px solid white;">
                        <?php endif; ?>
                    </li>

                    <!-- ONLY MY PROFILE -->
                    <li class="nav-item me-2">
                        <a href="<?= $profileUrl ?>" class="btn btn-light btn-sm">
                            My Profile
                        </a>
                    </li>

                    <li class="nav-item me-2">
                        <span class="badge bg-warning text-dark text-uppercase">
                            <?= esc($role) ?>
                        </span>
                    </li>

                    <li class="nav-item me-3">
                        <span class="navbar-text text-white">
                            Welcome <?= esc($sessionUser['name']) ?>
                        </span>
                    </li>

                    <li class="nav-item">
                        <a href="<?= site_url('logout') ?>" class="btn btn-dark btn-sm border border-light">
                            Logout
                        </a>
                    </li>

                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>

<!-- MAIN CONTENT -->
<div class="container mt-4">

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('notif_success')): ?>
        <div class="alert alert-success">
            <?= esc(session()->getFlashdata('notif_success')) ?>
        </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('notif_error')): ?>
        <div class="alert alert-danger">
            <?= esc(session()->getFlashdata('notif_error')) ?>
        </div>
    <?php endif; ?>

    <?= $this->renderSection('content') ?>

</div>

<!-- FOOTER -->
<footer class="text-center mt-5 mb-3 text-muted">
    CI4 CRUD Exam
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>