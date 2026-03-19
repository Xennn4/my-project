<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<h3>Edit Record</h3>

<form method="post" action="<?= site_url('records/update/' . $record['id']) ?>">
<input class="form-control mb-2" name="title" value="<?= $record['title'] ?>">

<textarea class="form-control mb-2" name="description"><?= $record['description'] ?></textarea>

<input class="form-control mb-2" name="status" value="<?= $record['status'] ?>">

<input class="form-control mb-2" name="category" value="<?= $record['category'] ?>">

<button class="btn btn-success">Update</button>

</form>

<?= $this->endSection() ?>