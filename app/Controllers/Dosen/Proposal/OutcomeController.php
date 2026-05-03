<?php

namespace App\Controllers\Dosen\Proposal;

use App\Controllers\BaseController;
use App\Models\Proposal\ProposalOutcome;
use App\Models\Proposal\ProposalPengajuan;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

class OutcomeController extends BaseController
{
    protected ProposalOutcome $outcomeModel;
    protected ProposalPengajuan $proposalModel;

    public function __construct()
    {
        $this->outcomeModel = new ProposalOutcome();
        $this->proposalModel = new ProposalPengajuan();
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
        
        $rules = [
            'tipe' => 'required|in_list[jurnal,buku]',
        ];

        if ($tipe === 'jurnal') {
            $rules['judul'] = 'required|max_length[255]';
            $rules['nama_jurnal'] = 'required|max_length[255]';
            $rules['volume_nomor'] = 'required|max_length[100]';
            $rules['url'] = 'required|max_length[255]';
        } else {
            $rules['judul'] = 'required|max_length[255]';
            $rules['isbn'] = 'required|max_length[50]';
            $rules['penerbit'] = 'required|max_length[255]';
            $rules['tahun_terbit'] = 'required|exact_length[4]|numeric';
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

        if ($tipe === 'jurnal') {
            $url = (string) $this->request->getPost('url');
            // Clean URL from https:// or http:// as requested in image note
            $url = str_replace(['https://', 'http://'], '', $url);
            
            $data['nama_penerbit_jurnal'] = $this->request->getPost('nama_jurnal');
            $data['volume_nomor'] = $this->request->getPost('volume_nomor');
            $data['url'] = $url;
        } else {
            $data['isbn'] = $this->request->getPost('isbn');
            $data['nama_penerbit_jurnal'] = $this->request->getPost('penerbit');
            $data['tahun_terbit'] = $this->request->getPost('tahun_terbit');
        }

        try {
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
            $this->outcomeModel->delete($outcome->id);
            return redirect()->back()->with('success', 'Outcome berhasil dihapus.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
