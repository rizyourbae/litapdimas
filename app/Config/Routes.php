<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ============================================================
// Static Assets Routes (Uploads)
// ============================================================
$routes->get('uploads/kelengkapan_dokumen/(:any)', function ($filename) {
    $filepath = FCPATH . 'uploads/kelengkapan_dokumen/' . $filename;
    if (!is_file($filepath)) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException('File not found: ' . $filename);
    }

    $mime = mime_content_type($filepath);
    return service('response')
        ->setHeader('Content-Type', $mime ?: 'application/octet-stream')
        ->setHeader('Content-Length', filesize($filepath))
        ->setHeader('Content-Disposition', 'inline; filename="' . basename($filepath) . '"')
        ->setBody(file_get_contents($filepath));
});

$routes->get('uploads/riwayat_pendidikan/(:any)', function ($filename) {
    $filepath = FCPATH . 'uploads/riwayat_pendidikan/' . $filename;
    if (!is_file($filepath)) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException('File not found: ' . $filename);
    }

    $mime = mime_content_type($filepath);
    return service('response')
        ->setHeader('Content-Type', $mime ?: 'application/octet-stream')
        ->setHeader('Content-Length', filesize($filepath))
        ->setHeader('Content-Disposition', 'inline; filename="' . basename($filepath) . '"')
        ->setBody(file_get_contents($filepath));
});

$routes->get('uploads/profile/(:any)', function ($filename) {
    $filepath = FCPATH . 'uploads/profile/' . $filename;
    if (!is_file($filepath)) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException('File not found: ' . $filename);
    }

    $mime = mime_content_type($filepath);
    return service('response')
        ->setHeader('Content-Type', $mime ?: 'application/octet-stream')
        ->setHeader('Content-Length', filesize($filepath))
        ->setHeader('Content-Disposition', 'inline; filename="' . basename($filepath) . '"')
        ->setBody(file_get_contents($filepath));
});

// ============================================================
// Public Routes
// ============================================================
$routes->get('/', 'HomeController::index');

// Auth Routes
$routes->match(['GET', 'POST'], 'login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');

// ============================================================
// Smart Dashboard Redirect (berdasarkan role)
// ============================================================
$routes->get('dashboard', 'HomeController::dashboard', ['filter' => 'auth']);

// ============================================================
// Profile Routes (universal - semua role yang punya profile.manage)
// ============================================================
$routes->group('profile', ['filter' => 'auth:profile.manage'], function ($routes) {
    $routes->get('/',       'Profile\ProfileController::index',  ['as' => 'profile.edit']);
    $routes->post('update', 'Profile\ProfileController::update', ['as' => 'profile.update']);
});

// ============================================================
// Admin Routes
// ============================================================
$routes->group('admin', ['filter' => 'auth:admin.access'], function ($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');
});

// ============================================================
// Dosen Routes
// ============================================================
$routes->group('dosen', ['filter' => 'auth:dosen.access'], function ($routes) {
    $routes->get('dashboard', 'Dosen\DashboardController::index');
});

// ============================================================
// Dosen Publikasi Routes
// ============================================================
$routes->group('dosen/publikasi', ['filter' => 'auth:dosen.access'], function ($routes) {
    $routes->get('/',              'Dosen\Publikasi\PublikasiController::index',  ['as' => 'dosen.publikasi.index']);
    $routes->get('create',         'Dosen\Publikasi\PublikasiController::create', ['as' => 'dosen.publikasi.create']);
    $routes->get('show/(:any)',    'Dosen\Publikasi\PublikasiController::show/$1', ['as' => 'dosen.publikasi.show']);
    $routes->post('store',         'Dosen\Publikasi\PublikasiController::store',  ['as' => 'dosen.publikasi.store']);
    $routes->get('edit/(:any)',    'Dosen\Publikasi\PublikasiController::edit/$1', ['as' => 'dosen.publikasi.edit']);
    $routes->post('update/(:any)', 'Dosen\Publikasi\PublikasiController::update/$1', ['as' => 'dosen.publikasi.update']);
    $routes->get('delete/(:any)',  'Dosen\Publikasi\PublikasiController::delete/$1', ['as' => 'dosen.publikasi.delete']);
});

// ============================================================
// Dosen Kegiatan Mandiri Routes
// ============================================================
$routes->group('dosen/kegiatan-mandiri', ['filter' => 'auth:dosen.access'], function ($routes) {
    $routes->get('/',              'Dosen\KegiatanMandiri\KegiatanMandiriController::index', ['as' => 'dosen.kegiatan_mandiri.index']);
    $routes->get('create',         'Dosen\KegiatanMandiri\KegiatanMandiriController::create', ['as' => 'dosen.kegiatan_mandiri.create']);
    $routes->get('show/(:any)',    'Dosen\KegiatanMandiri\KegiatanMandiriController::show/$1', ['as' => 'dosen.kegiatan_mandiri.show']);
    $routes->post('store',         'Dosen\KegiatanMandiri\KegiatanMandiriController::store', ['as' => 'dosen.kegiatan_mandiri.store']);
    $routes->get('edit/(:any)',    'Dosen\KegiatanMandiri\KegiatanMandiriController::edit/$1', ['as' => 'dosen.kegiatan_mandiri.edit']);
    $routes->post('update/(:any)', 'Dosen\KegiatanMandiri\KegiatanMandiriController::update/$1', ['as' => 'dosen.kegiatan_mandiri.update']);
    $routes->get('delete/(:any)',  'Dosen\KegiatanMandiri\KegiatanMandiriController::delete/$1', ['as' => 'dosen.kegiatan_mandiri.delete']);
});

// ============================================================
// Dosen Riwayat Pendidikan Routes
// ============================================================
$routes->group('dosen/riwayat-pendidikan', ['filter' => 'auth:dosen.access'], function ($routes) {
    $routes->get('/',              'Dosen\RiwayatPendidikan\RiwayatPendidikanController::index',  ['as' => 'dosen.riwayat_pendidikan.index']);
    $routes->get('create',         'Dosen\RiwayatPendidikan\RiwayatPendidikanController::create', ['as' => 'dosen.riwayat_pendidikan.create']);
    $routes->post('store',         'Dosen\RiwayatPendidikan\RiwayatPendidikanController::store',  ['as' => 'dosen.riwayat_pendidikan.store']);
    $routes->get('edit/(:any)',    'Dosen\RiwayatPendidikan\RiwayatPendidikanController::edit/$1', ['as' => 'dosen.riwayat_pendidikan.edit']);
    $routes->post('update/(:any)', 'Dosen\RiwayatPendidikan\RiwayatPendidikanController::update/$1', ['as' => 'dosen.riwayat_pendidikan.update']);
    $routes->get('delete/(:any)',  'Dosen\RiwayatPendidikan\RiwayatPendidikanController::delete/$1', ['as' => 'dosen.riwayat_pendidikan.delete']);
});

// ============================================================
// Dosen Kelengkapan Dokumen Routes
// ============================================================
$routes->group('dosen/kelengkapan-dokumen', ['filter' => 'auth:dosen.access'], function ($routes) {
    $routes->get('/',              'Dosen\KelengkapanDokumen\KelengkapanDokumenController::index',  ['as' => 'dosen.kelengkapan_dokumen.index']);
    $routes->get('edit/(:any)',    'Dosen\KelengkapanDokumen\KelengkapanDokumenController::edit/$1', ['as' => 'dosen.kelengkapan_dokumen.edit']);
    $routes->post('update/(:any)', 'Dosen\KelengkapanDokumen\KelengkapanDokumenController::update/$1', ['as' => 'dosen.kelengkapan_dokumen.update']);
});

// ============================================================
// Dosen Profil SINTA Routes
// ============================================================
$routes->group('dosen/profil-sinta', ['filter' => 'auth:dosen.access'], function ($routes) {
    $routes->get('/', 'Dosen\ProfilSinta\ProfilSintaController::index', ['as' => 'dosen.profil_sinta.index']);
    $routes->post('sync', 'Dosen\ProfilSinta\ProfilSintaController::sync', ['as' => 'dosen.profil_sinta.sync']);
});

// ============================================================
// Reviewer Routes
// ============================================================
$routes->group('reviewer', ['filter' => 'auth:reviewer.access'], function ($routes) {
    $routes->get('dashboard', 'Reviewer\DashboardController::index');
});

// ============================================================
// Admin Master Data Routes
// ============================================================
$routes->group('admin/master', ['filter' => 'auth:master.manage'], function ($routes) {

    // ----------------------------------------------------------
    // Referensi: Profesi, Bidang Ilmu, Jabatan Fungsional
    // Semua dalam 1 halaman dengan tabs, method pakai :segment (type slug)
    // ----------------------------------------------------------
    $routes->get('referensi', 'Admin\Master\ReferensiController::index', ['as' => 'admin.master.referensi']);
    $routes->post('referensi/store/(:segment)', 'Admin\Master\ReferensiController::store/$1');
    $routes->post('referensi/update/(:segment)/(:num)', 'Admin\Master\ReferensiController::update/$1/$2');
    $routes->get('referensi/delete/(:segment)/(:num)', 'Admin\Master\ReferensiController::delete/$1/$2');
    $routes->get('referensi/restore/(:segment)/(:num)', 'Admin\Master\ReferensiController::restore/$1/$2');
    $routes->get('referensi/json/(:segment)/(:num)', 'Admin\Master\ReferensiController::json/$1/$2');

    // ----------------------------------------------------------
    // Akademik: Fakultas + Program Studi (saling terkait)
    // ----------------------------------------------------------
    $routes->get('akademik', 'Admin\Master\AkademikController::index', ['as' => 'admin.master.akademik']);
    $routes->post('akademik/store/(:segment)', 'Admin\Master\AkademikController::store/$1');
    $routes->post('akademik/update/(:segment)/(:num)', 'Admin\Master\AkademikController::update/$1/$2');
    $routes->get('akademik/delete/(:segment)/(:num)', 'Admin\Master\AkademikController::delete/$1/$2');
    $routes->get('akademik/restore/(:segment)/(:num)', 'Admin\Master\AkademikController::restore/$1/$2');
    $routes->get('akademik/json/(:segment)/(:num)', 'Admin\Master\AkademikController::json/$1/$2');

    // ----------------------------------------------------------
    // Unit Kerja: standalone, hierarki (punya parent)
    // ----------------------------------------------------------
    $routes->get('unit-kerja', 'Admin\Master\UnitKerjaController::index', ['as' => 'admin.master.unit_kerja']);
    $routes->post('unit-kerja/store', 'Admin\Master\UnitKerjaController::store');
    $routes->post('unit-kerja/update/(:num)', 'Admin\Master\UnitKerjaController::update/$1');
    $routes->get('unit-kerja/delete/(:num)', 'Admin\Master\UnitKerjaController::delete/$1');
    $routes->get('unit-kerja/restore/(:num)', 'Admin\Master\UnitKerjaController::restore/$1');
    $routes->get('unit-kerja/json/(:num)', 'Admin\Master\UnitKerjaController::json/$1');
});

// ============================================================
// Admin Publikasi Routes
// ============================================================
$routes->group('admin/publikasi', ['filter' => 'auth:admin.access'], function ($routes) {
    $routes->get('/',              'Admin\Publikasi\PublikasiController::index',  ['as' => 'admin.publikasi.index']);
    $routes->get('create',         'Admin\Publikasi\PublikasiController::create', ['as' => 'admin.publikasi.create']);
    $routes->get('show/(:any)',    'Admin\Publikasi\PublikasiController::show/$1', ['as' => 'admin.publikasi.show']);
    $routes->post('store',         'Admin\Publikasi\PublikasiController::store',  ['as' => 'admin.publikasi.store']);
    $routes->get('edit/(:any)',    'Admin\Publikasi\PublikasiController::edit/$1', ['as' => 'admin.publikasi.edit']);
    $routes->post('update/(:any)', 'Admin\Publikasi\PublikasiController::update/$1', ['as' => 'admin.publikasi.update']);
    $routes->get('delete/(:any)',  'Admin\Publikasi\PublikasiController::delete/$1', ['as' => 'admin.publikasi.delete']);
});

// ============================================================
// Admin Kegiatan Mandiri Routes
// ============================================================
$routes->group('admin/kegiatan-mandiri', ['filter' => 'auth:admin.access'], function ($routes) {
    $routes->get('/',              'Admin\KegiatanMandiri\KegiatanMandiriController::index', ['as' => 'admin.kegiatan_mandiri.index']);
    $routes->get('create',         'Admin\KegiatanMandiri\KegiatanMandiriController::create', ['as' => 'admin.kegiatan_mandiri.create']);
    $routes->get('show/(:any)',    'Admin\KegiatanMandiri\KegiatanMandiriController::show/$1', ['as' => 'admin.kegiatan_mandiri.show']);
    $routes->post('store',         'Admin\KegiatanMandiri\KegiatanMandiriController::store', ['as' => 'admin.kegiatan_mandiri.store']);
    $routes->get('edit/(:any)',    'Admin\KegiatanMandiri\KegiatanMandiriController::edit/$1', ['as' => 'admin.kegiatan_mandiri.edit']);
    $routes->post('update/(:any)', 'Admin\KegiatanMandiri\KegiatanMandiriController::update/$1', ['as' => 'admin.kegiatan_mandiri.update']);
    $routes->get('delete/(:any)',  'Admin\KegiatanMandiri\KegiatanMandiriController::delete/$1', ['as' => 'admin.kegiatan_mandiri.delete']);
});

// ============================================================
// Admin User Routes
// ============================================================
$routes->group('admin', ['filter' => 'auth:users.manage'], function ($routes) {
    $routes->get('users', 'Admin\User\UserController::index', ['as' => 'admin.users.index']);
    $routes->get('users/create', 'Admin\User\UserController::create', ['as' => 'admin.users.create']);
    $routes->post('users/store', 'Admin\User\UserController::store', ['as' => 'admin.users.store']);
    $routes->get('users/edit/(:any)', 'Admin\User\UserController::edit/$1', ['as' => 'admin.users.edit']);
    $routes->post('users/update/(:any)', 'Admin\User\UserController::update/$1', ['as' => 'admin.users.update']);
    $routes->get('users/delete/(:any)', 'Admin\User\UserController::delete/$1', ['as' => 'admin.users.delete']);
    $routes->get('users/restore/(:any)', 'Admin\User\UserController::restore/$1', ['as' => 'admin.users.restore']);
    $routes->match(['GET', 'POST'], 'users/resetPassword/(:any)', 'Admin\User\UserController::resetPassword/$1', ['as' => 'admin.users.resetPassword']);
});
