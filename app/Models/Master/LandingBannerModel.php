<?php

namespace App\Models\Master;

use App\Models\BaseModel;

class LandingBannerModel extends BaseModel
{
    protected $table            = 'landing_banners';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = [
        'uuid', 'image', 'title', 'description', 'link_url', 'sort_order', 'is_active'
    ];

    /**
     * Ambil banner yang aktif dan diurutkan.
     */
    public function getActiveBanners()
    {
        return $this->where('is_active', true)
                    ->orderBy('sort_order', 'ASC')
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }
}
