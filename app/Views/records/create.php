<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<h3>Create Record</h3>

<form method="post" action="<?= site_url('records/store') ?>">
<input class="form-control mb-2" name="title" placeholder="Title">

<textarea class="form-control mb-2" name="description"></textarea>

<input class="form-control mb-2" name="status" placeholder="Status">

<input class="form-control mb-2" name="category" placeholder="Category">

<button class="btn btn-success">Save</button>

</form>

<?= $this->endSection() ?>