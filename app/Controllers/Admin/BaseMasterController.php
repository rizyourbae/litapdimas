<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
# use CodeIgniter\Model;

abstract class BaseMasterController extends BaseController
{
    protected $model;
    protected $modelClass;
    protected $title = 'Data Master';
    protected $routePrefix = 'admin.master.'; // untuk redirect
    protected $viewPath = 'admin/master';
    protected $fields = [];   // definisi field untuk form
    protected $useModal = false; // default pakai halaman penuh

    public function initController(...)
    {
        parent::initController(...);
        // Inisialisasi model
        if ($this->modelClass) {
            $this->model = new $this->modelClass();
        }
    }

    // Menampilkan daftar data
    public function index()
    {
        $items = $this->model->findAll();
        $data = [
            'title'    => $this->title,
            'items'    => $items,
            'fields'   => $this->fields,
            'useModal' => $this->useModal,
            'routePrefix' => $this->routePrefix,
            'editUrl'  => site_url($this->routePrefix . 'edit'), // pattern akan diganti di view
            'deleteUrl'=> site_url($this->routePrefix . 'delete'),
            'restoreUrl'=> site_url($this->routePrefix . 'restore'),
        ];
        return $this->renderView($this->viewPath . '/index', $data);
    }

    // Form tambah (halaman penuh)
    public function create()
    {
        $data = [
            'title' => 'Tambah ' . $this->title,
            'fields'=> $this->fields,
            'action'=> site_url($this->routePrefix . 'store'),
            'item'  => null,
        ];
        return $this->renderView($this->viewPath . '/form', $data);
    }

    // Simpan data
    public function store()
    {
        $data = $this->request->getPost();
        // Validasi sederhana dari model
        if ($this->model->insert($data)) {
            return redirect()->to(site_url($this->routePrefix))->with('success', 'Data berhasil ditambahkan.');
        }
        return redirect()->back()->withInput()->with('errors', $this->model->errors());
    }

    // Form edit
    public function edit($id)
    {
        $item = $this->model->find($id);
        if (!$item) {
            return redirect()->to(site_url($this->routePrefix))->with('error', 'Data tidak ditemukan.');
        }
        $data = [
            'title' => 'Edit ' . $this->title,
            'fields'=> $this->fields,
            'action'=> site_url($this->routePrefix . 'update/' . $id),
            'item'  => $item,
        ];
        return $this->renderView($this->viewPath . '/form', $data);
    }

    // Update data
    public function update($id)
    {
        $data = $this->request->getPost();
        if ($this->model->update($id, $data)) {
            return redirect()->to(site_url($this->routePrefix))->with('success', 'Data berhasil diubah.');
        }
        return redirect()->back()->withInput()->with('errors', $this->model->errors());
    }

    // Soft delete
    public function delete($id)
    {
        $this->model->delete($id);
        return redirect()->to(site_url($this->routePrefix))->with('success', 'Data berhasil dihapus.');
    }

    // Restore
    public function restore($id)
    {
        $this->model->update($id, ['deleted_at' => null]);
        return redirect()->to(site_url($this->routePrefix))->with('success', 'Data berhasil dikembalikan.');
    }
}