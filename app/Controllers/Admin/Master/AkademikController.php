<?php

namespace App\Controllers\Admin\Master;

use App\Controllers\BaseController;
use App\Models\Master\FakultasModel;
use App\Models\Master\ProgramStudiModel;

/**
 * AkademikController
 * Mengelola 2 master data akademik yang saling terkait dalam satu halaman:
 * - Fakultas
 * - Program Studi (has foreign key ke Fakultas)
 */
class AkademikController extends BaseController
{
    private const TYPE_MAP = [
        'fakultas' => FakultasModel::class,
        'prodi'    => ProgramStudiModel::class,
    ];

    private const TYPE_LABELS = [
        'fakultas' => 'Fakultas',
        'prodi'    => 'Program Studi',
    ];

    public function index()
    {
        $fakultasModel = new FakultasModel();
        $prodiModel    = new ProgramStudiModel();

        // Prodi dengan nama fakultas (left join)
        $prodiList = $prodiModel
            ->select('master_program_studi.*, master_fakultas.nama as nama_fakultas')
            ->join('master_fakultas', 'master_fakultas.id = master_program_studi.fakultas_id', 'left')
            ->findAll();

        return $this->renderView('admin/master/akademik', [
            'title'           => 'Data Akademik',
            'fakultas'        => $fakultasModel->findAll(),
            'prodi'           => $prodiList,
            'fakultasOptions' => $fakultasModel->select('id, nama')->findAll(),
        ]);
    }

    public function store(string $type)
    {
        $model = $this->resolveModel($type);
        if (!$model) {
            return redirect()->to(site_url('admin/master/akademik'))->with('error', 'Tipe data tidak valid.');
        }

        $postData = $this->getPostByType($type);

        if ($model->insert($postData)) {
            return redirect()
                ->to(site_url('admin/master/akademik'))
                ->with('success', esc(self::TYPE_LABELS[$type]) . ' berhasil ditambahkan.')
                ->with('active_tab', $type);
        }

        return redirect()
            ->back()
            ->withInput()
            ->with('errors', $model->errors())
            ->with('open_modal', 'tambah-' . $type);
    }

    public function update(string $type, int $id)
    {
        $model = $this->resolveModel($type);
        if (!$model) {
            return redirect()->to(site_url('admin/master/akademik'))->with('error', 'Tipe data tidak valid.');
        }

        $postData = $this->getPostByType($type);

        if ($model->update($id, $postData)) {
            return redirect()
                ->to(site_url('admin/master/akademik'))
                ->with('success', esc(self::TYPE_LABELS[$type]) . ' berhasil diubah.')
                ->with('active_tab', $type);
        }

        return redirect()
            ->back()
            ->withInput()
            ->with('errors', $model->errors())
            ->with('open_modal', 'edit-' . $type . '-' . $id);
    }

    public function delete(string $type, int $id)
    {
        $model = $this->resolveModel($type);
        if ($model) {
            $model->delete($id);
        }

        return redirect()
            ->to(site_url('admin/master/akademik'))
            ->with('success', 'Data berhasil dihapus.')
            ->with('active_tab', $type);
    }

    public function restore(string $type, int $id)
    {
        $model = $this->resolveModel($type);
        if ($model) {
            $model->update($id, ['deleted_at' => null]);
        }

        return redirect()
            ->to(site_url('admin/master/akademik'))
            ->with('success', 'Data berhasil dipulihkan.')
            ->with('active_tab', $type);
    }

    /**
     * JSON endpoint untuk modal edit
     */
    public function json(string $type, int $id)
    {
        $model = $this->resolveModel($type);
        if (!$model) {
            return $this->response->setJSON(['error' => 'Tipe tidak valid'])->setStatusCode(400);
        }

        $item = $model->find($id);
        if (!$item) {
            return $this->response->setJSON(['error' => 'Data tidak ditemukan'])->setStatusCode(404);
        }

        return $this->response->setJSON($item);
    }

    private function resolveModel(string $type): ?object
    {
        $class = self::TYPE_MAP[$type] ?? null;
        return $class ? new $class() : null;
    }

    /**
     * Ambil POST fields berdasarkan type (whitelist per model)
     */
    private function getPostByType(string $type): array
    {
        return match ($type) {
            'prodi'    => $this->request->getPost(['nama', 'fakultas_id']),
            default    => $this->request->getPost(['nama']),
        };
    }
}
