<?php

namespace App\Controllers\Dosen\Proposal;

use App\Controllers\BaseController;
use App\Models\Proposal\ProposalOutcome;
use App\Models\Proposal\ProposalPengajuan;
use App\Libraries\Storage;
use Exception;

class OutcomeController extends BaseController
{
    protected ProposalOutcome $outcomeModel;
    protected ProposalPengajuan $proposalModel;
    protected Storage $storage;

    public function __construct()
    {
        $this->outcomeModel = new ProposalOutcome();
        $this->proposalModel = new ProposalPengajuan();
        $this->storage = new Storage();
    }

    /**
     * Store new outcome
     */
    public function store(string $proposalUuid)
    {
        $userId = (int) (user()['id'] ?? 0);
        $proposal = $this->proposalModel->where('uuid', $proposalUuid)->first();

        if (!$proposal || (int) $proposal->user_id !== $userId) {
            return redirect()->back()->with('error', 'Proposal tidak ditemukan atau Anda tidak memiliki akses.');
        }

        $tipe = $this->request->getPost('tipe');
        $outcomeSource = $this->request->getPost('outcome_source');
        
        $rules = [
            'tipe' => 'required|in_list[jurnal,buku]',
        ];

        if ($tipe === 'jurnal') {
            $rules['judul'] = 'required|max_length[255]';
            $rules['nama_jurnal'] = 'required|max_length[255]';
            $rules['volume_nomor'] = 'required|max_length[100]';
            if ($outcomeSource === 'upload') {
                $rules['berkas'] = 'uploaded[berkas]|max_size[berkas,10240]|ext_in[berkas,pdf]';
            } else {
                $rules['url'] = 'required|max_length[255]';
            }
        } else {
            $rules['judul'] = 'required|max_length[255]';
            $rules['isbn'] = 'required|max_length[50]';
            $rules['penerbit'] = 'required|max_length[255]';
            $rules['tahun_terbit'] = 'required|exact_length[4]|numeric';
            if ($outcomeSource === 'upload') {
                $rules['berkas'] = 'uploaded[berkas]|max_size[berkas,10240]|ext_in[berkas,pdf]';
            } else {
                $rules['url'] = 'required|max_length[255]';
            }
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Validasi gagal: ' . implode(', ', $this->validator->getErrors()));
        }

        $data = [
            'uuid' => bin2hex(random_bytes(16)),
            'proposal_id' => $proposal->id,
            'tipe' => $tipe,
            'judul' => $this->request->getPost('judul'),
        ];

        try {
            if ($outcomeSource === 'upload') {
                $file = $this->request->getFile('berkas');

                if (!$file || !$file->isValid() || $file->hasMoved()) {
                    return redirect()->back()->with('error', 'File tidak valid.');
                }

                $fileName = pathinfo($file->getRandomName(), PATHINFO_FILENAME);
                
                $upload = $this->storage->putObject(
                    $_FILES['berkas'],
                    'outcome',
                    $fileName,
                    Storage::SHR_PUBLIC,
                );

                if (!isset($upload['status']) || $upload['status'] != 1) {
                    $errorMsg = $upload['message'] ?? 'Gagal mengunggah berkas.';
                    return redirect()->back()->with('error', $errorMsg);
                }

                $objectName = $upload['data']['object_name'] ?? null;
                if (!$objectName) {
                    return redirect()->back()->with('error', 'Gagal mendapatkan nama file dari penyimpanan.');
                }

                $data['file_path'] = $objectName;
                $data['original_filename'] = $file->getClientName();
            } else {
                $url = (string) $this->request->getPost('url');
                // Clean URL from https:// or http://
                $url = str_replace(['https://', 'http://'], '', $url);
                $data['url'] = $url;
            }

            if ($tipe === 'jurnal') {
                $data['nama_penerbit_jurnal'] = $this->request->getPost('nama_jurnal');
                $data['volume_nomor'] = $this->request->getPost('volume_nomor');
            } else {
                $data['isbn'] = $this->request->getPost('isbn');
                $data['nama_penerbit_jurnal'] = $this->request->getPost('penerbit');
                $data['tahun_terbit'] = $this->request->getPost('tahun_terbit');
            }

            $this->outcomeModel->insert($data);
            return redirect()->back()->with('success', 'Outcome berhasil ditambahkan.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan outcome: ' . $e->getMessage());
        }
    }

    /**
     * Delete outcome
     */
    public function delete(string $outcomeUuid)
    {
        $userId = (int) (user()['id'] ?? 0);
        $outcome = $this->outcomeModel->where('uuid', $outcomeUuid)->first();

        if (!$outcome) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $proposal = $this->proposalModel->find($outcome->proposal_id);
        if (!$proposal || (int) $proposal->user_id !== $userId) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        try {
            // Delete file from storage if exists
            if ($outcome && !empty($outcome->file_path)) {
                $this->storage->deleteObject($outcome->file_path);
            }
            
            $this->outcomeModel->delete($outcome->id);
            return redirect()->back()->with('success', 'Outcome berhasil dihapus.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
