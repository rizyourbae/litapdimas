<?php

namespace App\Controllers\Admin\Master;

use App\Controllers\Admin\BaseMasterController;
use App\Models\Master\ProgramStudiModel;
use App\Models\Master\FakultasModel;

class ProgramStudiController extends BaseMasterController
{
    protected $modelClass = ProgramStudiModel::class;
    protected $title = 'Program Studi';
    protected $routePrefix = 'admin.master.prodi.';
    protected $viewPath = 'admin/master';
    protected $fields = [
        ['name' => 'nama', 'label' => 'Nama Program Studi', 'type' => 'text', 'required' => true],
        [
            'name'    => 'fakultas_id',
            'label'   => 'Fakultas',
            'type'    => 'dropdown',
            'required' => true,
        ],
    ];
    protected $dropdowns = [
        'fakultas_id' => null,
    ];
    protected $useModal = false;

    public function initController($request, $response, $logger)
    {
        parent::initController($request, $response, $logger);
        $fakultasModel = new FakultasModel();
        $opts = $fakultasModel->select('id, nama')->findAll();
        $this->dropdowns['fakultas_id'] = array_map(function ($item) {
            return ['value' => $item['id'], 'label' => $item['nama']];
        }, $opts);
    }
}
