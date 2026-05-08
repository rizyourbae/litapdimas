<?php

namespace App\Controllers\Dosen\Proposal;

use App\Controllers\BaseController;
use App\Models\Proposal\ProposalLogbook;
use App\Models\Proposal\ProposalPengajuan;
use App\Libraries\Storage;
use Exception;

class LogbookController extends BaseController
{
    protected ProposalLogbook $logbookModel;
    protected ProposalPengajuan $proposalModel;
    protected Storage $storage;

    public function __construct()
    {
        $this->logbookModel = new ProposalLogbook();
        $this->proposalModel = new ProposalPengajuan();
        $this->storage = new Storage();
    }

    /**
     * Store new logbook entry
     */
    public function store(string $proposalUuid)
    {
        $userId = (int) (user()['id'] ?? 0);
        $proposal = $this->proposalModel->where('uuid', $proposalUuid)->first();

        if (!$proposal || (int) $proposal->user_id !== $userId) {
            return redirect()->back()->with('error', 'Proposal tidak ditemukan atau Anda tidak memiliki akses.');
        }

        if ($proposal->status !== 'approved') {
            return redirect()->back()->with('error', 'Hanya proposal yang disetujui yang dapat mengisi logbook.');
        }

        $rules = [
            'tanggal'            => 'required|valid_date',
            'tempat'             => 'required|max_length[255]',
            'nama_kegiatan'      => 'required|max_length[255]',
            'teknik'             => 'required|in_list[Analisis Dokumen,Diskusi,FGD,Observasi,Penyebaran Angket,Wawancara]',
            'deskripsi_kegiatan' => 'required',
        ];

        // Hanya validasi berkas jika ada file yang diunggah
        $file = $this->request->getFile('berkas');
        if ($file && $file->isValid()) {
            $rules['berkas'] = 'max_size[berkas,2048]|ext_in[berkas,pdf,jpg,jpeg,png,zip,docx]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal: ' . implode(', ', $this->validator->getErrors()));
        }

        $file = $this->request->getFile('berkas');
        $objectName = null;

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $fileName = pathinfo($file->getRandomName(), PATHINFO_FILENAME);
            
            try {
                $upload = $this->storage->putObject(
                    $_FILES['berkas'],
                    'logbook',
                    $fileName,
                    Storage::SHR_PUBLIC,
                );
            } catch (\Exception $e) {
                $upload = ['status' => 0, 'message' => $e->getMessage()];
            }

            if (!isset($upload['status']) || $upload['status'] != 1) {
                $errorMsg = $upload['message'] ?? 'Gagal mengunggah berkas.';
                return redirect()->back()->withInput()->with('error', $errorMsg);
            }

            $objectName = $upload['data']['object_name'] ?? null;
            if (!$objectName) {
                return redirect()->back()->withInput()->with('error', 'Gagal mendapatkan nama berkas dari penyimpanan.');
            }
        }

        $data = [
            'uuid'               => bin2hex(random_bytes(16)),
            'proposal_id'        => $proposal->id,
            'tanggal'            => $this->request->getPost('tanggal'),
            'tempat'             => $this->request->getPost('tempat'),
            'nama_kegiatan'      => $this->request->getPost('nama_kegiatan'),
            'teknik'             => $this->request->getPost('teknik'),
            'deskripsi_kegiatan' => $this->request->getPost('deskripsi_kegiatan'),
            'berkas_path'        => $objectName,
        ]; 

        try {
            $this->logbookModel->insert($data);
            return redirect()->back()->with('success', 'Logbook berhasil ditambahkan.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan logbook: ' . $e->getMessage());
        }
    }

    /**
     * Delete logbook entry
     */
    public function delete(string $uuid)
    {
        $userId = (int) (user()['id'] ?? 0);
        $logbook = $this->logbookModel->where('uuid', $uuid)->first();

        if (!$logbook) {
            return redirect()->back()->with('error', 'Data logbook tidak ditemukan.');
        }

        $proposal = $this->proposalModel->where('id', $logbook->proposal_id)->first();
        if (!$proposal || (int) $proposal->user_id !== $userId) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        try {
            if ($logbook->berkas_path) {
                $this->storage->deleteObject($logbook->berkas_path);
            }
            $this->logbookModel->delete($logbook->id);
            return redirect()->back()->with('success', 'Logbook berhasil dihapus.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus logbook: ' . $e->getMessage());
        }
    }
}
