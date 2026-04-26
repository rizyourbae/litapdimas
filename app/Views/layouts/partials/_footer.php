<footer class="app-footer">
    <div class="float-end d-none d-sm-inline">Litapdimas v1.0</div>
    <strong>
        &copy; <?= date('Y') ?> Universitas.
    </strong>
    All rights reserved.
</footer>

<!-- Scripts -->
<!-- OverlayScrollbars -->
<script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
    crossorigin="anonymous"></script>
<!-- jQuery (dibutuhkan DataTables) -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"
    crossorigin="anonymous"></script>
<!-- Popper & Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"
    crossorigin="anonymous"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"
    crossorigin="anonymous"></script>
<!-- Tom Select -->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"
    crossorigin="anonymous"></script>
<!-- DataTables + Bootstrap 5 integration -->
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"
    crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"
    crossorigin="anonymous"></script>
<!-- AdminLTE JS -->
<script src="<?= base_url('assets/adminlte/js/adminlte.js') ?>"></script>

<!-- Custom JS Helpers -->
<script src="<?= base_url('custom/js/swal.js') ?>"></script>
<script src="<?= base_url('custom/js/select2-init.js') ?>"></script>
<script src="<?= base_url('custom/js/datatables-init.js') ?>"></script>
<script src="<?= base_url('custom/js/dosen.js') ?>"></script>
<script src="<?= base_url('custom/js/admin.js') ?>"></script>
<script src="<?= base_url('custom/js/profile-form.js') ?>"></script>

<!-- OverlayScrollbars Init -->
<script>
    const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
    const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
    };
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
        if (sidebarWrapper && typeof OverlayScrollbarsGlobal !== 'undefined') {
            OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                scrollbars: {
                    theme: Default.scrollbarTheme,
                    autoHide: Default.scrollbarAutoHide,
                    clickScroll: Default.scrollbarClickScroll,
                },
            });
        }
    });
</script>