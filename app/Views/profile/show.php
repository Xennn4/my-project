<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-body">
            <h2 class="mb-4">My Profile</h2>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-4 text-center mb-3">
                    <?php if (!empty($user['profile_image'])): ?>
                        <img src="<?= base_url('uploads/profiles/' . $user['profile_image']) ?>"
                             alt="Profile Image"
                             class="img-fluid rounded-circle border"
                             style="width: 220px; height: 220px; object-fit: cover;">
                    <?php else: ?>
                        <div class="border rounded-circle d-flex align-items-center justify-content-center mx-auto"
                             style="width: 220px; height: 220px; font-size: 20px;">
                            No Image
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-8">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 180px;">Full Name</th>
                            <td><?= esc($user['name'] ?? '') ?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?= esc($user['email'] ?? '') ?></td>
                        </tr>
                        <tr>
                            <th>Student ID</th>
                            <td><?= esc($user['student_id'] ?? '') ?></td>
                        </tr>
                        <tr>
                            <th>Course</th>
                            <td><?= esc($user['course'] ?? '') ?></td>
                        </tr>
                        <tr>
                            <th>Year Level</th>
                            <td><?= esc($user['year_level'] ?? '') ?></td>
                        </tr>
                        <tr>
                            <th>Section</th>
                            <td><?= esc($user['section'] ?? '') ?></td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td><?= esc($user['phone'] ?? '') ?></td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td><?= esc($user['address'] ?? '') ?></td>
                        </tr>
                    </table>

                    <a href="<?= base_url('profile/edit') ?>" class="btn btn-primary">Edit Profile</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>