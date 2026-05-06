/**
 * Admin CMS Announcements JS
 * =========================
 * Menggunakan pola otomasi admin.js untuk CRUD via modal.
 */
$(document).ready(function() {
    const baseUrl = $('base').attr('href') || window.location.origin + '/';

    // DataTable di-inisialisasi otomatis oleh admin.js melalui atribut data-admin-datatable
    
    // Tombol Hapus (Custom confirm via SweetAlert2)
    $(document).on('click', '.btn-delete-announcement', function(e) {
        e.preventDefault();
        const uuid = $(this).data('uuid');
        const url = baseUrl + 'admin/cms/announcements/delete/' + uuid;
        const title = $(this).closest('tr').find('.fw-bold').text();

        if (typeof window.SwalDelete === 'function') {
            window.SwalDelete(url, title, "Pengumuman ini tidak akan muncul lagi di halaman publik.");
        } else {
            if (confirm('Hapus pengumuman: ' + title + '?')) {
                window.location.href = url;
            }
        }
    });
});
