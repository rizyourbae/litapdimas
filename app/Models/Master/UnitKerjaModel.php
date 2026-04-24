<?php

namespace App\Models\Master;

use App\Models\BaseModel;
use App\Models\Traits\SoftDeleteTrait;
use App\Models\Traits\HasUserstampsTrait;
use App\Models\Traits\HasMasterValidation;

class UnitKerjaModel extends BaseModel
{
    use SoftDeleteTrait;
    use HasUserstampsTrait;
    use HasMasterValidation;

    protected $table      = 'master_unit_kerja';
    protected $allowedFields = ['uuid', 'nama', 'parent_id', 'created_by', 'updated_by'];

    public function __construct()
    {
        parent::__construct();
        // Initialize centralized validation for 'nama' + additional rules
        $this->initializeMasterValidation([
            'parent_id' => 'permit_empty|integer|is_not_unique[master_unit_kerja.id]',
        ]);
    }

    // Untuk dropdown parent
    public function getParentOptions(): array
    {
        return $this->select('id, nama')->where('parent_id', null)->findAll();
    }
}
