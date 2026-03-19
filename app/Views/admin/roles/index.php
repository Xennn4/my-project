<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <h3 class="fw-bold mb-0 page-title">
        <i class="bi bi-shield-check me-2 text-danger"></i>Role Management
    </h3>
    <a href="<?= base_url('/admin/roles/create') ?>" class="btn btn-danger">
        <i class="bi bi-plus-circle me-1"></i>Create New Role
    </a>
</div>

<!-- Info banner -->
<div class="alert alert-warning border-0 d-flex align-items-start gap-3 mb-4">
    <i class="bi bi-exclamation-triangle-fill fs-5 flex-shrink-0 mt-1"></i>
    <div class="small">
        <strong>Administrator Only — Role Management</strong><br>
        Roles control what pages each user can access. The <code>admin</code> role cannot be deleted.
        Deleting a role will unassign all users currently holding that role.
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-4" style="width:50px;">#</th>
                        <th>Role Slug</th>
                        <th>Display Label</th>
                        <th>Description</th>
                        <th class="text-center">Users</th>
                        <th class="text-center pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($roles as $i => $role): ?>
                    <tr>
                        <td class="ps-4 text-muted"><?= $i + 1 ?></td>
                        <td>
                            <code class="bg-light px-2 py-1 rounded text-danger fw-bold">
                                <?= esc($role['name']) ?>
                            </code>
                            <?php if ($role['name'] === 'admin'): ?>
                                <span class="badge bg-danger ms-1">Protected</span>
                            <?php endif; ?>
                        </td>
                        <td class="fw-semibold"><?= esc($role['label']) ?></td>
                        <td class="text-muted small" style="max-width:280px;">
                            <?= esc($role['description'] ?? '—') ?>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-secondary rounded-pill">
                                <?= $role['user_count'] ?>
                            </span>
                        </td>
                        <td class="text-center pe-4">
                            <a href="<?= base_url('/admin/roles/edit/' . $role['id']) ?>"
                               class="btn btn-sm btn-warning me-1" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <?php if ($role['name'] !== 'admin'): ?>
                            <button type="button" class="btn btn-sm btn-danger"
                                    data-bs-toggle="modal" data-bs-target="#deleteModal"
                                    data-id="<?= $role['id'] ?>"
                                    data-label="<?= esc($role['label']) ?>"
                                    data-count="<?= $role['user_count'] ?>"
                                    title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                            <?php else: ?>
                            <button class="btn btn-sm btn-outline-secondary" disabled title="Cannot delete admin role">
                                <i class="bi bi-lock"></i>
                            </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i>Confirm Delete Role</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-4">
                <p class="mb-2">Are you sure you want to delete the role <strong id="deleteRoleLabel"></strong>?</p>
                <div id="deleteWarning" class="alert alert-warning py-2 small mb-0" style="display:none;">
                    <i class="bi bi-people me-1"></i>
                    <span id="deleteWarningText"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="deleteConfirmBtn" class="btn btn-danger">
                    <i class="bi bi-trash me-1"></i>Delete Role
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('deleteModal').addEventListener('show.bs.modal', function (e) {
        const btn     = e.relatedTarget;
        const id      = btn.dataset.id;
        const label   = btn.dataset.label;
        const count   = parseInt(btn.dataset.count);
        document.getElementById('deleteRoleLabel').textContent = '"' + label + '"';
        document.getElementById('deleteConfirmBtn').href = '<?= base_url('/admin/roles/delete/') ?>' + id;
        const warn = document.getElementById('deleteWarning');
        if (count > 0) {
            warn.style.display = '';
            document.getElementById('deleteWarningText').textContent =
                count + ' user(s) currently have this role. They will be unassigned.';
        } else {
            warn.style.display = 'none';
        }
    });
</script>

<?= $this->endSection() ?>
