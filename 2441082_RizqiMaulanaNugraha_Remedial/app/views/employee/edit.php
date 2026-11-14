<div class="d-flex justify-content-between align-items-center mb-4">
<div>
<h3 class="mb-0 fw-bold">Edit Pekerja</h3>
<small class="text-muted">Perbarui data pekerja</small>
</div>
<a href="<?= BASEURL ?>/employees" class="btn btn-outline-secondary">
<i class="bi bi-arrow-left"></i> Kembali
</a>
</div>
<div class="card border-0 shadow-sm">
<div class="card-body">
<form action="<?= BASEURL ?>/employees/update" method="post">
<input type="hidden" name="id" value="<?= e($category['id']) ?>">
<?php require ROOT_PATH . '/app/views/employees/_form.php'; ?>
<div class="mt-4">
<button type="submit" class="btn btn-primary">
<i class="bi bi-save"></i> Update
</button>
<a href="<?= BASEURL ?>/employees" class="btn btn-light">Batal</a>
</div>
</form>
</div>
</div>
