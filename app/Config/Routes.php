<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ============================================================
// Public Routes
// ============================================================
$routes->get('/', 'HomeController::index');

// Auth Routes
$routes->match(['GET', 'POST'], 'login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');

// ============================================================
// Admin Routes
// ============================================================
$routes->group('admin', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');
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
