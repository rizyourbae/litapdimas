<?php

namespace App\Controllers\Admin\Master;

use App\Controllers\Admin\BaseMasterController;
use App\Models\Master\BidangIlmuModel;

class BidangIlmuController extends BaseMasterController
{
    protected $modelClass = BidangIlmuModel::class;
    protected $title = 'Bidang Ilmu';
    protected $routePrefix = 'admin.master.bidang_ilmu.';
    protected $viewPath = 'admin/master';
    protected $fields = [
        ['name' => 'nama', 'label' => 'Nama Bidang Ilmu', 'type' => 'text', 'required' => true],
    ];
    protected $useModal = true;
}
