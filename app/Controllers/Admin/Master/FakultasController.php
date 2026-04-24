<?php

namespace App\Controllers\Admin\Master;

use App\Controllers\Admin\BaseMasterController;
use App\Models\Master\FakultasModel;

class FakultasController extends BaseMasterController
{
    protected $modelClass = FakultasModel::class;
    protected $title = 'Fakultas';
    protected $routePrefix = 'admin.master.fakultas.';
    protected $viewPath = 'admin/master';
    protected $fields = [
        ['name' => 'nama', 'label' => 'Nama Fakultas', 'type' => 'text', 'required' => true],
    ];
    protected $useModal = false; // halaman penuh
}
