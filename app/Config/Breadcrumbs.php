<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Breadcrumbs extends BaseConfig
{
    public $titles = [
        'admin' => 'Dashboard',
        'admin/dashboard' => 'Dashboard Admin',
        'admin/users' => 'Manajemen User',
        'admin/users/create' => 'Tambah User',
        'admin/users/edit' => 'Edit User',
        'admin/users/show' => 'Detail User',
        'admin/users/resetPassword' => 'Reset Password',
        'admin/proposals' => 'Daftar Proposal',
        'admin/proposals/create' => 'Tambah Proposal',
        'admin/proposals/show' => 'Detail Proposal',
        'admin/proposals/edit' => 'Edit Proposal',
        'admin/publikasi' => 'Publikasi',
        'admin/publikasi/create' => 'Tambah Publikasi',
        'admin/publikasi/show' => 'Detail Publikasi',
        'admin/publikasi/edit' => 'Edit Publikasi',
    ];
}
