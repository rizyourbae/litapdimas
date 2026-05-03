<?php

namespace App\Controllers\Dosen\Proposal;

use App\Controllers\BaseController;
use App\Models\Proposal\ProposalOutput;
use App\Models\Proposal\ProposalPengajuan;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

class OutputController extends BaseController
{
    protected ProposalOutput $outputModel;
    protected ProposalPengajuan $proposalModel;

    public function __construct()
    {
        $this->outputModel = new ProposalOutput();
        $this->proposalModel = new ProposalPengajuan();
    }

    /**
     * Store or update output file
     */
    public function upload(string $proposalUuid)
    {
        $userId = (int) (user()['id'] ?? 0);
        $proposal = $this->proposalModel->where('uuid', $proposalUuid)->first();

        if (!$proposal || (int) $proposal->user_id !== $userId) {
            return redirect()->back()->with('error', 'Proposal tidak ditemukan atau Anda tidak memiliki akses.');
        }

        if ($proposal->status !== 'approved') {
            return redirect()->back()->with('error', 'Hanya proposal yang disetujui yang dapat mengunggah luaran.');
        }

        $rules = [
            'kategori' => 'required|in_list[HKI,Laporan Bantuan Lengkap,Draft Artikel,Dummy Buku,Dokumen Kemanfaatan,Executive Summary]',
            'berkas'   => 'uploaded[berkas]|max_size[berkas,10240]|ext_in[berkas,pdf]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Validasi gagal: ' . implode(', ', $this->validator->getErrors()));
        }

        $kategori = $this->request->getPost('kategori');
        $file = $this->request->getFile('berkas');

        // Check if already exists to replace
        $existing = $this->outputModel->getByProposalAndKategori($proposal->id, $kategori);

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/proposals/outputs', $newName);
            $file_path = 'uploads/proposals/outputs/' . $newName;

            $data = [
                'uuid'              => bin2hex(random_bytes(16)),
                'proposal_id'       => $proposal->id,
                'kategori'          => $kategori,
                'file_path'         => $file_path,
                'original_filename' => $file->getClientName(),
            ];

            try {
                if ($existing) {
                    // Delete old file
                    if (file_exists(FCPATH . $existing->file_path)) {
                        @unlink(FCPATH . $existing->file_path);
                    }
                    $this->outputModel->update($existing->id, $data);
                } else {
                    $this->outputModel->insert($data);
                }

                return redirect()->back()->with('success', "Luaran '{$kategori}' berhasil diunggah.");
            } catch (Exception $e) {
                return redirect()->back()->with('error', 'Gagal mengunggah luaran: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('error', 'File tidak valid.');
    }
}
