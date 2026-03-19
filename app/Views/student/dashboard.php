<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h3 class="fw-bold mb-0 page-title">
            <i class="bi bi-speedometer2 me-2 text-primary"></i>Student Dashboard
        </h3>
        <p class="text-muted small mt-1 mb-0">
            Welcome back, <strong><?= esc($user['name']) ?></strong>!
            <span class="badge bg-primary ms-1">Student</span>
        </p>
    </div>
    <a href="<?= base_url('/profile/edit') ?>" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-pencil me-1"></i>Edit Profile
    </a>
</div>

<div class="row g-4">
    <!-- Profile card -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm text-center h-100">
            <div class="card-body py-4">
                <?php if (! empty($user['profile_image'])): ?>
                    <img src="<?= base_url('uploads/profiles/' . esc($user['profile_image'])) ?>"
                         class="rounded-circle border border-4 border-primary mb-3"
                         style="width:110px;height:110px;object-fit:cover;" alt="Avatar">
                <?php else: ?>
                    <div class="rounded-circle bg-primary bg-opacity-10 border border-4 border-primary
                                d-inline-flex align-items-center justify-content-center mb-3"
                         style="width:110px;height:110px;">
                        <i class="bi bi-person-fill text-primary" style="font-size:3rem;"></i>
                    </div>
                <?php endif; ?>
                <h5 class="fw-bold mb-1"><?= esc($user['name']) ?></h5>
                <p class="text-muted small mb-2"><?= esc($user['email']) ?></p>
                <?php if ($user['course']): ?>
                <span class="badge bg-primary bg-opacity-10 text-primary px-3">
                    <?= esc($user['course']) ?> <?= $user['year_level'] ? '— Year ' . $user['year_level'] : '' ?>
                </span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Info card -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-id-card me-2 text-primary"></i>My Information</h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <?php foreach ([
                        ['bi-hash',        'Student ID',  $user['student_id'] ?? null],
                        ['bi-mortarboard', 'Course',      $user['course'] ?? null],
                        ['bi-layers',      'Year & Section', trim(($user['year_level'] ? 'Year '.$user['year_level'] : '') . ' ' . ($user['section'] ?? '')) ?: null],
                        ['bi-telephone',   'Phone',       $user['phone'] ?? null],
                        ['bi-geo-alt',     'Address',     $user['address'] ?? null],
                    ] as [$icon, $label, $val]): ?>
                    <div class="col-sm-6">
                        <p class="text-muted small mb-1"><i class="bi <?= $icon ?> me-1"></i><?= $label ?></p>
                        <p class="fw-semibold mb-0">
                            <?= $val ? esc($val) : '<span class="text-muted fst-italic small">Not set</span>' ?>
                        </p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Access notice -->
    <div class="col-12">
        <div class="alert alert-info border-0 d-flex align-items-center gap-3 mb-0">
            <i class="bi bi-info-circle-fill fs-5"></i>
            <div>
                <strong>Your Access Level — Student</strong><br>
                <small>You can view your dashboard and manage your profile. Other sections are not accessible with your current role.</small>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
