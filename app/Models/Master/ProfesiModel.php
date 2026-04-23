<?php

namespace App\Models\Master;

use App\Models\BaseModel;
use App\Models\Traits\SoftDeleteTrait;
use App\Models\Traits\HasUserstampsTrait;

class ProfesiModel extends BaseModel
{
    use SoftDeleteTrait;
    use HasUserstampsTrait;

    protected $table      = 'master_profesi';
    protected $allowedFields = ['uuid', 'nama', 'created_by', 'updated_by'];
    protected $validationRules = [
        'nama' => 'required|min_length[3]|is_unique[master_profesi.nama,id,{id}]'
    ];
}
