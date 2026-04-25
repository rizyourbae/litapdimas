<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Menu extends BaseConfig
{
    public $sidebar = [
        // ============================================================
        // SEMUA ROLE: Dashboard (akses berdasarkan dashboard.access)
        // ============================================================
        [
            'label'      => 'Dashboard',
            'icon'       => 'bi-speedometer',
            'url'        => 'dashboard',
            'permission' => 'dashboard.access',
        ],

        // ============================================================
        // ADMIN: Manajemen User & Data Master
        // ============================================================
        [
            'label'      => 'Manajemen User',
            'icon'       => 'bi-people-fill',
            'url'        => '#',
            'permission' => 'users.manage',
            'children'   => [
                ['label' => 'Semua User',  'url' => 'admin/users',        'icon' => 'bi-circle'],
            ],
        ],
        [
            'label'      => 'Data Master',
            'icon'       => 'bi-database',
            'url'        => '#',
            'permission' => 'master.manage',
            'children'   => [
                [
                    'label'       => 'Referensi',
                    'url'         => 'admin/master/referensi',
                    'icon'        => 'bi-tags',
                    'permission'  => 'master.manage',
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
            ],
        ],

        // ============================================================
        // ADMIN: Publikasi
        // ============================================================
        [
            'label'      => 'Publikasi',
            'icon'       => 'bi-journal-richtext',
            'url'        => '#',
            'permission' => 'admin.access',
            'children'   => [
                ['label' => 'Publikasi', 'url' => 'admin/publikasi',        'icon' => 'bi-circle'],
                ['label' => 'Kegiatan Mandiri', 'url' => 'admin/kegiatan-mandiri',        'icon' => 'bi-circle'],
            ],
        ],

        // ============================================================
        // DOSEN: Proposal & Profil
        // ============================================================
        [
            'label'      => 'Akademik',
            'icon'       => 'bi-mortarboard',
            'url'        => '#',
            'permission' => 'dosen.access',
            'children'   => [
                ['label' => 'Publikasi',          'url' => 'dosen/publikasi',        'icon' => 'bi-journal-richtext'],
                ['label' => 'Kegiatan Mandiri',   'url' => 'dosen/kegiatan-mandiri', 'icon' => 'bi-list-check'],
                ['label' => 'Riwayat Pendidikan',  'url' => 'dosen/riwayat-pendidikan', 'icon' => 'bi-mortarboard'],
                ['label' => 'Kelengkapan Dokumen', 'url' => 'dosen/kelengkapan-dokumen', 'icon' => 'bi-file-earmark-check'],
            ],
        ],
        [
            'label'      => 'Proposal Saya',
            'icon'       => 'bi-file-earmark-text',
            'url'        => '#',
            'permission' => 'dosen.access',
            'children'   => [
                ['label' => 'Daftar Proposal', 'url' => 'dosen/proposals',        'icon' => 'bi-circle'],
                ['label' => 'Ajukan Proposal', 'url' => 'dosen/proposals/create', 'icon' => 'bi-circle'],
            ],
        ],
        [
            'label'      => 'Profil Saya',
            'icon'       => 'bi-person-circle',
            'url'        => 'profile',
            'permission' => 'profile.manage',
        ],

        // ============================================================
        // REVIEWER: Antrian & Riwayat Review
        // ============================================================
        [
            'label'      => 'Antrian Review',
            'icon'       => 'bi-clipboard2-check',
            'url'        => '#',
            'permission' => 'reviewer.access',
            'children'   => [
                ['label' => 'Menunggu Review', 'url' => 'reviewer/queue',   'icon' => 'bi-circle'],
                ['label' => 'Riwayat Review',  'url' => 'reviewer/history', 'icon' => 'bi-circle'],
            ],
        ],
    ];
}
