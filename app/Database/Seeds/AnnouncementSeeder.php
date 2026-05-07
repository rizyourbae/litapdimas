<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run()
    {
        $announcements = [
            [
                'uuid' => 'd8882e84-56ad-4e34-99ec-797de8349be8',
                'title' => 'Jadwal',
                'slug' => 'jadwal-57HP',
                'content' => 'Iya',
                'image' => NULL,
                'file_attachment' => 'uploads/attachments/jadwal.pdf',
                'view_count' => 0,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'deleted_at' => NULL,
            ],
            [
                'uuid' => 'aa1b7442-f78c-46f7-ac04-dcdc85955bf5',
                'title' => 'Sosialisasi Reviewer',
                'slug' => 'sosialisasi-reviewer-yiAP',
                'content' => 'Undangan Sosialisasi dan Peningkatan Kapasitas Reviewer Nasional Penelitian, Publikasi Ilmiah, dan Pengabdian kepada Masyarakat di PTKI',
                'image' => NULL,
                'file_attachment' => 'uploads/attachments/sosialisasi-reviewer.pdf',
                'view_count' => 0,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'deleted_at' => NULL,
            ],
        ];

        foreach ($announcements as $a) {
            $this->db->table('announcements')->insert($a);
        }

        echo "✓ Announcements seeded successfully!\n";
    }
}
