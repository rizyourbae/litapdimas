<?php

namespace App\Controllers\Admin\Master;

use App\Controllers\Admin\BaseMasterController;
use App\Models\Master\JabatanFungsionalModel;

class JabatanFungsionalController extends BaseMasterController
{
    protected $modelClass = JabatanFungsionalModel::class;
    protected $title = 'Jabatan Fungsional';
    protected $routePrefix = 'admin.master.jabatan_fungsional.';
    protected $viewPath = 'admin/master';
    protected $fields = [
        ['name' => 'nama', 'label' => 'Nama Jabatan', 'type' => 'text', 'required' => true],
    ];
    protected $useModal = true;
}
