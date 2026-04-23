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
        'admin/proposals' => 'Daftar Proposal',
    ];
}
