<?php

namespace App\Controllers\Admin\Master;

use App\Controllers\Admin\BaseMasterController;
use App\Models\Master\ProfesiModel;

class ProfesiController extends BaseMasterController
{
    protected $modelClass = ProfesiModel::class;
    protected $title = 'Profesi';
    protected $routePrefix = 'admin.master.profesi.';
    protected $viewPath = 'admin/master';
    protected $fields = [
        ['name' => 'nama', 'label' => 'Nama Profesi', 'type' => 'text', 'required' => true],
    ];
    protected $useModal = true;
}
