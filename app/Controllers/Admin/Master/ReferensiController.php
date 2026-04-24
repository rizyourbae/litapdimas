<?php

namespace App\Controllers\Admin\Master;

use App\Controllers\BaseController;
use App\Models\Master\BidangIlmuModel;
use App\Models\Master\JabatanFungsionalModel;
use App\Models\Master\ProfesiModel;

/**
 * ReferensiController
 * Mengelola 3 master data sederhana (hanya field 'nama') dalam satu halaman:
 * - Profesi
 * - Bidang Ilmu
 * - Jabatan Fungsional
 */
class ReferensiController extends BaseController
{
    /**
     * Map slug → model class
     */
    private const TYPE_MAP = [
        'profesi'     => ProfesiModel::class,
        'bidang-ilmu' => BidangIlmuModel::class,
        'jabatan'     => JabatanFungsionalModel::class,
    ];

    /**
     * Label per type (untuk pesan user)
     */
    private const TYPE_LABELS = [
        'profesi'     => 'Profesi',
        'bidang-ilmu' => 'Bidang Ilmu',
        'jabatan'     => 'Jabatan Fungsional',
    ];

    public function index()
    {
        return $this->renderView('admin/master/referensi', [
            'title'     => 'Data Referensi',
            'profesi'   => (new ProfesiModel())->findAll(),
            'bidangIlmu' => (new BidangIlmuModel())->findAll(),
            'jabatan'   => (new JabatanFungsionalModel())->findAll(),
        ]);
    }

    public function store(string $type)
    {
        $model = $this->resolveModel($type);
        if (!$model) {
            return redirect()->to(site_url('admin/master/referensi'))->with('error', 'Tipe data tidak valid.');
        }

        $postData = $this->request->getPost(['nama']);

        if ($model->insert($postData)) {
            return redirect()
                ->to(site_url('admin/master/referensi'))
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
            return redirect()->to(site_url('admin/master/referensi'))->with('error', 'Tipe data tidak valid.');
        }

        $postData = $this->request->getPost(['nama']);

        if ($model->update($id, $postData)) {
            return redirect()
                ->to(site_url('admin/master/referensi'))
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
            ->to(site_url('admin/master/referensi'))
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
            ->to(site_url('admin/master/referensi'))
            ->with('success', 'Data berhasil dipulihkan.')
            ->with('active_tab', $type);
    }

    /**
     * JSON endpoint untuk modal edit (AJAX fetch data)
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
}
