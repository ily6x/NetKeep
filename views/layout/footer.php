<?php if (isset($_SESSION['user_id'])): ?>
</main><!-- /.nk-main -->
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('shown.bs.modal', function () {
    document.querySelectorAll('.modal-backdrop').forEach(function(el) {
        el.style.cssText = 'opacity:1 !important; background:#000 !important;';
    });
});
</script>
</body>
</html>