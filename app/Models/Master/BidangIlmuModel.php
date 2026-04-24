<?php

namespace App\Models\Master;

use App\Models\BaseModel;
use App\Models\Traits\SoftDeleteTrait;
use App\Models\Traits\HasUserstampsTrait;
use App\Models\Traits\HasMasterValidation;

class BidangIlmuModel extends BaseModel
{
    use SoftDeleteTrait;
    use HasUserstampsTrait;
    use HasMasterValidation;

    protected $table      = 'master_bidang_ilmu';
    protected $allowedFields = ['uuid', 'nama', 'created_by', 'updated_by'];

    public function __construct()
    {
        parent::__construct();
        // Initialize centralized validation rules dan messages
        $this->initializeMasterValidation();
    }
}
