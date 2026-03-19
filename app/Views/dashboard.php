<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card">

<div class="card-body">

<h3>Dashboard</h3>

<p>
Welcome <strong><?= session()->get('user_name') ?></strong>
</p>

<hr>

<a href="<?= site_url('records') ?>" class="btn btn-primary">Manage Records</a>

</div>

</div>

<?= $this->endSection() ?>