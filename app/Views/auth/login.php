<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header text-center">
                <h4>Login</h4>
            </div>

            <div class="card-body">

                <?php if (session()->getFlashdata('notif_error')): ?>
                    <div class="alert alert-danger">
                        <?= session()->getFlashdata('notif_error') ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="<?= base_url('login') ?>">
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="inputEmail" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="inputPassword" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>

                <hr>

                <p class="text-center">
                    Don't have an account?
                    <a href="<?= base_url('register') ?>">Register</a>
                </p>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>