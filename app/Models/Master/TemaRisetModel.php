<?php

namespace App\Models\Master;

use App\Models\BaseModel;

class TemaRisetModel extends BaseModel
{
    protected $table            = 'tema_riset';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = [
        'uuid', 'nama', 'icon', 'keterangan', 'is_active', 'sort_order'
    ];

    /**
     * Ambil tema yang aktif dan diurutkan.
     */
    public function getActiveThemes()
    {
        return $this->where('is_active', true)
                    ->orderBy('sort_order', 'ASC')
                    ->orderBy('nama', 'ASC')
                    ->findAll();
    }
}
