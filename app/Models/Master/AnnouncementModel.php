<?php

namespace App\Models\Master;

use App\Models\BaseModel;

class AnnouncementModel extends BaseModel
{
    protected $table            = 'announcements';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = [
        'uuid', 'title', 'slug', 'content', 'image', 'file_attachment', 'view_count', 'is_active'
    ];

    // Validation
    protected $validationRules      = [
        'title'   => 'required|min_length[5]|max_length[255]',
        'content' => 'required',
    ];

    /**
     * Ambil pengumuman aktif terbaru
     */
    public function getLatest(int $limit = 6)
    {
        return $this->where('is_active', true)
                    ->orderBy('created_at', 'DESC')
                    ->findAll($limit);
    }

    /**
     * Cari berdasarkan slug
     */
    public function getBySlug(string $slug)
    {
        return $this->where('slug', $slug)
                    ->where('is_active', true)
                    ->first();
    }
}
