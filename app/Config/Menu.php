<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Menu extends BaseConfig
{
    public $sidebar = [
        [
            'label' => 'Dashboard',
            'icon'  => 'bi-speedometer',
            'url'   => 'admin/dashboard',
            'permission' => 'dashboard.access',
        ],
        [
            'label' => 'Manajemen User',
            'icon'  => 'bi-people-fill',
            'url'   => '#', // parent menu
            'permission' => 'users.manage',
            'children' => [
                ['label' => 'Semua User', 'url' => 'admin/users', 'icon' => 'bi-circle'],
                ['label' => 'Tambah User', 'url' => 'admin/users/create', 'icon' => 'bi-circle'],
            ],
        ],
        [
            'label' => 'Proposal',
            'icon'  => 'bi-file-earmark-text',
            'url'   => '#',
            'permission' => 'proposals.access',
            'children' => [
                ['label' => 'Daftar Proposal', 'url' => 'admin/proposals', 'icon' => 'bi-circle'],
                ['label' => 'Review', 'url' => 'admin/review', 'icon' => 'bi-circle'],
            ],
        ],
    ];
}
