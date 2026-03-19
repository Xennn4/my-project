<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<h3>Records</h3>

<a href="<?= site_url('records/create') ?>" class="btn btn-primary mb-3">Add Record</a>

<table class="table table-striped">

<tr>
<th>ID</th>
<th>Title</th>
<th>Status</th>
<th>Category</th>
<th>Action</th>
</tr>

<?php foreach($records as $r): ?>

<tr>
<td><?= $r['id'] ?></td>
<td><?= $r['title'] ?></td>
<td><?= $r['status'] ?></td>
<td><?= $r['category'] ?></td>

<td>
<a href="<?= site_url('records/edit/' . $r['id']) ?>" class="btn btn-warning btn-sm">Edit</a>

<a href="<?= site_url('records/delete/' . $r['id']) ?>"
   class="btn btn-danger btn-sm"
   onclick="return confirm('Are you sure you want to delete this record?');">
   Delete
</a>
</td>

</tr>

<?php endforeach ?>

</table>

<?= $this->endSection() ?>