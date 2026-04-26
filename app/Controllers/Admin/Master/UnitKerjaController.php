<?php

namespace App\Controllers\Admin\Master;

use App\Controllers\BaseController;
use App\Models\Master\UnitKerjaModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * UnitKerjaController
 * Mengelola master data Unit Kerja (hierarki: bisa punya parent)
 */
class UnitKerjaController extends BaseController
{
    protected $model;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->model = new UnitKerjaModel();
    }

    public function index()
    {
        // Load dengan nama induk (left join ke dirinya sendiri)
        $items = $this->model
            ->select('u.*, p.nama as nama_induk')
            ->from('master_unit_kerja u')
            ->join('master_unit_kerja p', 'p.id = u.parent_id', 'left')
            ->get()
            ->getResultArray();

        return $this->renderView('admin/master/unit_kerja', [
            'title'     => 'Unit Kerja',
            'items'     => $items,
            'options'   => $this->model->select('id, nama')->findAll(),
            'viewState' => [
                'openModal' => session()->getFlashdata('open_modal') ?? null,
                'errors'    => session()->getFlashdata('errors') ?? [],
                'baseUrl'   => site_url('admin/master/unit_kerja'),
            ],
        ]);
    }

    public function store()
    {
        $data = $this->request->getPost(['nama', 'parent_id']);

        if ($this->model->insert($data)) {
            return redirect()
                ->to(site_url('admin/master/unit-kerja'))
                ->with('success', 'Unit Kerja berhasil ditambahkan.');
        }

        return redirect()
            ->back()
            ->withInput()
            ->with('errors', $this->model->errors())
            ->with('open_modal', 'tambah');
    }

    public function update(int $id)
    {
        $data = $this->request->getPost(['nama', 'parent_id']);

        if ($this->model->update($id, $data)) {
            return redirect()
                ->to(site_url('admin/master/unit-kerja'))
                ->with('success', 'Unit Kerja berhasil diubah.');
        }

        return redirect()
            ->back()
            ->withInput()
            ->with('errors', $this->model->errors())
            ->with('open_modal', 'edit-' . $id);
    }

    public function delete(int $id)
    {
        $this->model->delete($id);

        return redirect()
            ->to(site_url('admin/master/unit-kerja'))
            ->with('success', 'Unit Kerja berhasil dihapus.');
    }

    public function restore(int $id)
    {
        $this->model->update($id, ['deleted_at' => null]);

        return redirect()
            ->to(site_url('admin/master/unit-kerja'))
            ->with('success', 'Unit Kerja berhasil dipulihkan.');
    }

    public function json(int $id)
    {
        $item = $this->model->find($id);
        if (!$item) {
            return $this->response->setJSON(['error' => 'Data tidak ditemukan'])->setStatusCode(404);
        }

        return $this->response->setJSON($item);
    }
}
