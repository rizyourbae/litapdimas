/**
 * Admin CMS Landing Page JS
 */
$(document).ready(function() {
    // Pastikan kita mengambil base URL yang benar dari tag <base> atau mendeteksi manual
    const baseUrl = $('base').attr('href') || window.location.origin + '/';
    const modalTheme = new bootstrap.Modal(document.getElementById('modalTheme'));
    const formTheme = $('#formTheme');
    const modalTitle = $('#modalThemeTitle');

    // --- TAMBAH TEMA ---
    $(document).on('click', '.btn-add-theme', function(e) {
        e.preventDefault();
        
        // Reset Form & Action
        formTheme.attr('action', `${baseUrl}admin/cms/landing/themes/store`);
        formTheme[0].reset();
        modalTitle.html('<i class="bi bi-plus-circle me-2"></i>Tambah Tema Riset');
        
        modalTheme.show();
    });

    // --- EDIT TEMA ---
    $(document).on('click', '.btn-edit-theme', function() {
        const uuid = $(this).data('uuid');
        const url = $(this).data('url');
        if (!url) return;

        // Tampilkan loading
        const btn = $(this);
        const oldHtml = btn.html();
        btn.html('<span class="spinner-border spinner-border-sm"></span>');
        
        // Load Data via JSON
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                btn.html(oldHtml);

                // Setup action untuk update
                const updateUrl = url.replace('/json/', '/update/');
                formTheme.attr('action', updateUrl);
                modalTitle.html('<i class="bi bi-pencil-square me-2"></i>Edit Tema Riset');
                
                // Populate Fields
                $('#theme_nama').val(data.nama);
                $('#theme_icon').val(data.icon);
                $('#theme_sort').val(data.sort_order);
                $('#theme_active').prop('checked', data.is_active == 1);
                
                modalTheme.show();
            },
            error: function(xhr) {
                btn.html(oldHtml);
                console.error("Detail Error:", xhr.responseText);
                
                let errorMsg = 'Gagal mengambil data dari server.';
                try {
                    const res = JSON.parse(xhr.responseText);
                    if (res.message) errorMsg = res.message;
                } catch(e) {}
                
                Swal.fire({
                    title: 'Error',
                    text: errorMsg,
                    footer: `<small class="text-danger">${xhr.statusText} (${xhr.status})</small>`,
                    icon: 'error'
                });
            }
        });
    });

    // --- DELETE TEMA ---
    $(document).on('click', '.btn-delete-theme', function() {
        const url = $(this).data('url');
        if (!url) return;
        
        Swal.fire({
            title: 'Hapus Tema Riset?',
            text: "Tema ini tidak akan muncul lagi di halaman landing.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6e7881',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                confirmButton: 'btn btn-danger px-4 rounded-pill',
                cancelButton: 'btn btn-light px-4 rounded-pill'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });

    // ============================================================
    // BANNER MANAGEMENT
    // ============================================================
    const modalBanner = new bootstrap.Modal(document.getElementById('modalBanner'));
    const formBanner = $('#formBanner');
    const modalBannerTitle = $('#modalBannerTitle');
    const bannerPreview = $('#banner_preview');
    const bannerPlaceholder = $('#banner_placeholder');

    // --- TAMBAH BANNER ---
    $(document).on('click', '.btn-add-banner', function(e) {
        e.preventDefault();
        formBanner.attr('action', `${baseUrl}admin/cms/landing/banners/store`);
        formBanner[0].reset();
        modalBannerTitle.html('<i class="bi bi-image me-2"></i>Tambah Banner Carousel');
        bannerPreview.addClass('d-none');
        bannerPlaceholder.removeClass('d-none');
        modalBanner.show();
    });

    // --- EDIT BANNER ---
    $(document).on('click', '.btn-edit-banner', function() {
        const url = $(this).data('url');
        const btn = $(this);
        const oldHtml = btn.html();
        btn.html('<span class="spinner-border spinner-border-sm"></span>');

        $.get(url, function(data) {
            btn.html(oldHtml);
            const updateUrl = url.replace('/json/', '/update/');
            formBanner.attr('action', updateUrl);
            modalBannerTitle.html('<i class="bi bi-pencil-square me-2"></i>Edit Banner Carousel');
            
            $('#banner_title').val(data.title);
            $('#banner_description').val(data.description);
            $('#banner_link').val(data.link_url);
            $('#banner_sort').val(data.sort_order);
            $('#banner_active').prop('checked', data.is_active == 1);
            
            // Show current image
            bannerPreview.attr('src', `${baseUrl}${data.image}`).removeClass('d-none');
            bannerPlaceholder.addClass('d-none');
            
            modalBanner.show();
        });
    });

    // --- DELETE BANNER ---
    $(document).on('click', '.btn-delete-banner', function() {
        const url = $(this).data('url');
        Swal.fire({
            title: 'Hapus Banner?',
            text: "Gambar ini tidak akan muncul lagi di slider.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                confirmButton: 'btn btn-danger px-4 rounded-pill',
                cancelButton: 'btn btn-light px-4 rounded-pill'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) window.location.href = url;
        });
    });

    // --- IMAGE PREVIEW ---
    $('#banner_file').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                bannerPreview.attr('src', e.target.result).removeClass('d-none');
                bannerPlaceholder.addClass('d-none');
            }
            reader.readAsDataURL(file);
        }
    });
});
