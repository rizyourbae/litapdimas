<?php

namespace App\Models\Master;

use App\Models\BaseModel;
use App\Models\Traits\SoftDeleteTrait;
use App\Models\Traits\HasUserstampsTrait;
use App\Models\Traits\HasMasterValidation;

class ProgramStudiModel extends BaseModel
{
    use SoftDeleteTrait;
    use HasUserstampsTrait;
    use HasMasterValidation;

    protected $table      = 'master_program_studi';
    protected $allowedFields = ['uuid', 'nama', 'fakultas_id', 'created_by', 'updated_by'];

    public function __construct()
    {
        parent::__construct();
        // Initialize centralized validation for 'nama' + additional rules
        $this->initializeMasterValidation([
            'fakultas_id' => 'required|integer|is_not_unique[master_fakultas.id]',
        ]);
    }

    // Mendapatkan daftar fakultas untuk dropdown
    public function getFakultasOptions(): array
    {
        return (new FakultasModel())->select('id, nama')->findAll();
    }
}
