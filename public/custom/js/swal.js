/**
 * SweetAlert2 Helpers
 * ==================
 * Wrapper global untuk SweetAlert2 yang digunakan di seluruh aplikasi.
 *
 * Menyediakan:
 * - Toast notification (success, error, warning, info)
 * - Konfirmasi hapus / aksi destruktif
 *
 * Syarat: SweetAlert2 sudah di-load sebelum file ini.
 */

(function () {
  "use strict";

  if (typeof Swal === "undefined") {
    console.warn("[swal.js] SweetAlert2 belum ter-load.");
    return;
  }

  // =========================================================
  // Toast mixin — muncul di sudut kanan atas
  // =========================================================
  const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3500,
    timerProgressBar: true,
    didOpen: function (toast) {
      toast.addEventListener("mouseenter", Swal.stopTimer);
      toast.addEventListener("mouseleave", Swal.resumeTimer);
    },
  });

  /**
   * Tampilkan toast dari data attribute #page-flash
   * Dipanggil otomatis saat DOM ready (lihat akhir file ini).
   */
  function handlePageFlash() {
    const el = document.getElementById("page-flash");
    if (!el) return;

    const success = el.dataset.success;
    const error = el.dataset.error;
    const warning = el.dataset.warning;
    const info = el.dataset.info;

    if (success) Toast.fire({ icon: "success", title: success });
    else if (error) Toast.fire({ icon: "error", title: error });
    else if (warning) Toast.fire({ icon: "warning", title: warning });
    else if (info) Toast.fire({ icon: "info", title: info });
  }

  // =========================================================
  // Public API
  // =========================================================

  /**
   * Toast cepat
   * @param {'success'|'error'|'warning'|'info'} icon
   * @param {string} title
   */
  window.SwalToast = function (icon, title) {
    Toast.fire({ icon, title });
  };

  /**
   * Dialog konfirmasi hapus
   * @param {string}   href  - URL yang dituju jika konfirmasi
   * @param {string}   nama  - Nama item yang akan dihapus (untuk ditampilkan)
   * @param {string}   [desc=''] - Keterangan tambahan
   */
  window.SwalDelete = function (href, nama, desc) {
    const descHtml = desc ? `<br><small class="text-muted">${desc}</small>` : '<br><small class="text-muted">Data akan dinonaktifkan dan dapat dipulihkan kembali.</small>';

    Swal.fire({
      title: "Hapus data ini?",
      html: `Data <strong>${nama}</strong> akan dihapus.${descHtml}`,
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#6c757d",
      confirmButtonText: '<i class="bi bi-trash me-1"></i>Ya, hapus!',
      cancelButtonText: "Batal",
      reverseButtons: true,
      focusCancel: true,
    }).then(function (result) {
      if (result.isConfirmed) {
        window.location.href = href;
      }
    });
  };

  /**
   * Dialog konfirmasi aksi umum (restore, dll.)
   * @param {string}   href
   * @param {string}   title
   * @param {string}   html
   * @param {string}   confirmText
   * @param {string}   confirmColor
   */
  window.SwalConfirm = function (href, title, html, confirmText, confirmColor) {
    Swal.fire({
      title: title,
      html: html,
      icon: "question",
      showCancelButton: true,
      confirmButtonColor: confirmColor || "#198754",
      cancelButtonColor: "#6c757d",
      confirmButtonText: confirmText || "Ya, lanjutkan!",
      cancelButtonText: "Batal",
      reverseButtons: true,
    }).then(function (result) {
      if (result.isConfirmed) {
        window.location.href = href;
      }
    });
  };

  // =========================================================
  // Auto-init saat DOM ready
  // =========================================================
  document.addEventListener("DOMContentLoaded", handlePageFlash);
})();
