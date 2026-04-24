<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
# use CodeIgniter\Model;

abstract class BaseMasterController extends BaseController
{
    protected $model;
    protected $modelClass;
    protected $title = 'Data Master';
    protected $routePrefix = 'admin.master.';
    protected $viewPath = 'admin/master';
    protected $fields = [];
    protected $useModal = false;
    protected $dropdowns = [];  // key = field name, value = array options atau closure

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        if ($this->modelClass) {
            $this->model = new $this->modelClass();
        }
    }

    public function index()
    {
        $items = $this->model->findAll();
        $data = [
            'title'    => $this->title,
            'items'    => $items,
            'fields'   => $this->fields,
            'useModal' => $this->useModal,
            'routePrefix' => $this->routePrefix,
            'addUrl'   => site_url($this->routePrefix . 'create'),
            'editUrl'  => site_url($this->routePrefix . 'edit/'), // akan ditambah id di view
            'deleteUrl' => site_url($this->routePrefix . 'delete/'),
            'restoreUrl' => site_url($this->routePrefix . 'restore/'),
            'jsonUrl'  => site_url($this->routePrefix . 'json/'), // untuk ambil data via AJAX
        ];
        return $this->renderView($this->viewPath . '/index', $data);
    }

    public function create()
    {
        $data = [
            'title'     => 'Tambah ' . $this->title,
            'fields'    => $this->fields,
            'dropdowns' => $this->resolveDropdowns(),
            'action'    => site_url($this->routePrefix . 'store'),
            'item'      => null,
            'useModal'  => $this->useModal,
            'routePrefix' => $this->routePrefix,
        ];

        if ($this->useModal) {
            // Untuk modal, tampilkan halaman index dengan data form via variable
            // Kita akan render form partial
            return $this->renderView($this->viewPath . '/form_modal', $data);
        }
        return $this->renderView($this->viewPath . '/form', $data);
    }

    public function store()
    {
        $data = $this->request->getPost();
        if ($this->model->insert($data)) {
            return redirect()->to(site_url($this->routePrefix))->with('success', 'Data berhasil ditambahkan.');
        }
        return redirect()->back()->withInput()->with('errors', $this->model->errors());
    }

    public function edit($id)
    {
        $item = $this->model->find($id);
        if (!$item) {
            return redirect()->to(site_url($this->routePrefix))->with('error', 'Data tidak ditemukan.');
        }
        $data = [
            'title'     => 'Edit ' . $this->title,
            'fields'    => $this->fields,
            'dropdowns' => $this->resolveDropdowns(),
            'action'    => site_url($this->routePrefix . 'update/' . $id),
            'item'      => $item,
            'useModal'  => $this->useModal,
            'routePrefix' => $this->routePrefix,
        ];

        if ($this->useModal) {
            return $this->renderView($this->viewPath . '/form_modal', $data);
        }
        return $this->renderView($this->viewPath . '/form', $data);
    }

    public function update($id)
    {
        $data = $this->request->getPost();
        if ($this->model->update($id, $data)) {
            return redirect()->to(site_url($this->routePrefix))->with('success', 'Data berhasil diubah.');
        }
        return redirect()->back()->withInput()->with('errors', $this->model->errors());
    }

    public function delete($id)
    {
        $this->model->delete($id); // soft delete dari trait
        return redirect()->to(site_url($this->routePrefix))->with('success', 'Data berhasil dihapus.');
    }

    public function restore($id)
    {
        $this->model->update($id, ['deleted_at' => null]);
        return redirect()->to(site_url($this->routePrefix))->with('success', 'Data berhasil dikembalikan.');
    }

    // Endpoint JSON untuk edit modal (mengambil satu data)
    public function json($id)
    {
        $item = $this->model->find($id);
        if (!$item) {
            return $this->response->setJSON(['error' => 'Not found'])->setStatusCode(404);
        }
        return $this->response->setJSON($item);
    }

    // Resolve dropdowns jika ada closure
    protected function resolveDropdowns(): array
    {
        $result = [];
        foreach ($this->dropdowns as $field => $source) {
            if ($source instanceof \Closure) {
                $result[$field] = $source();
            } else {
                $result[$field] = $source;
            }
        }
        return $result;
    }
}
