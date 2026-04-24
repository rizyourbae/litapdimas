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
        [
            'label' => 'Data Master',
            'icon'  => 'bi-database',
            'url'   => '#',
            'permission' => 'master.manage',
            'children' => [
                [
                    'label'      => 'Referensi',
                    'url'        => 'admin/master/referensi',
                    'icon'       => 'bi-tags',
                    'permission' => 'master.manage',
                    'description' => 'Profesi, Bidang Ilmu, Jabatan Fungsional',
                ],
                [
                    'label'      => 'Akademik',
                    'url'        => 'admin/master/akademik',
                    'icon'       => 'bi-mortarboard',
                    'permission' => 'master.manage',
                    'description' => 'Fakultas & Program Studi',
                ],
                [
                    'label'      => 'Unit Kerja',
                    'url'        => 'admin/master/unit-kerja',
                    'icon'       => 'bi-diagram-3',
                    'permission' => 'master.manage',
                ],
            ]
        ]
    ];
}
