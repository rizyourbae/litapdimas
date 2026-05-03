<?php

namespace App\Controllers\Dosen\Proposal;

use App\Controllers\BaseController;
use App\Models\Proposal\ProposalLogbook;
use App\Models\Proposal\ProposalPengajuan;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

class LogbookController extends BaseController
{
    protected ProposalLogbook $logbookModel;
    protected ProposalPengajuan $proposalModel;

    public function __construct()
    {
        $this->logbookModel = new ProposalLogbook();
        $this->proposalModel = new ProposalPengajuan();
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
            'berkas'             => 'uploaded[berkas]|max_size[berkas,2048]|ext_in[berkas,pdf,jpg,jpeg,png,zip,docx]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal: ' . implode(', ', $this->validator->getErrors()));
        }

        $file = $this->request->getFile('berkas');
        $berkasPath = null;

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/proposals/logbooks', $newName);
            $berkasPath = 'uploads/proposals/logbooks/' . $newName;
        }

        $data = [
            'uuid'               => \CodeIgniter\Encryption\Encryption::createKey(16), // simple random for uuid if not using helper
            'proposal_id'        => $proposal->id,
            'tanggal'            => $this->request->getPost('tanggal'),
            'tempat'             => $this->request->getPost('tempat'),
            'nama_kegiatan'      => $this->request->getPost('nama_kegiatan'),
            'teknik'             => $this->request->getPost('teknik'),
            'deskripsi_kegiatan' => $this->request->getPost('deskripsi_kegiatan'),
            'berkas_path'        => $berkasPath,
        ];

        // Ensure real UUID
        $data['uuid'] = bin2hex(random_bytes(16)); 

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

        $proposal = $this->proposalModel->find($logbook->proposal_id);
        if (!$proposal || (int) $proposal->user_id !== $userId) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        try {
            if ($logbook->berkas_path && file_exists(FCPATH . $logbook->berkas_path)) {
                @unlink(FCPATH . $logbook->berkas_path);
            }
            $this->logbookModel->delete($logbook->id);
            return redirect()->back()->with('success', 'Logbook berhasil dihapus.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus logbook: ' . $e->getMessage());
        }
    }
}
