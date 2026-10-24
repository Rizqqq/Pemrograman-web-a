</main>
<?php if ($loggedIn): ?>
    </div>
</div>
<?php endif; ?>

<!-- Bootstrap JS bawaan Anda -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- 1. CDN Library SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- 2. Eksekusi Swal.fire saat ada pesan flash dari controller/middleware -->
<?php if (!empty($flash)): ?>
<script>
    Swal.fire({
        icon: "<?= $flash['type'] === 'danger' ? 'error' : 'success' ?>",
        title: "<?= $flash['type'] === 'danger' ? 'Oops...' : 'Berhasil!' ?>",
        text: <?= json_encode($flash['message']) ?>,
        confirmButtonColor: "#0d6efd"
    });
</script>
<?php endif; ?>

</body>
</html>