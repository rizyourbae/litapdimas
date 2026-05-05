<?php

namespace App\Controllers\Dosen\Proposal;

use App\Controllers\BaseController;
use App\Models\Proposal\ProposalReport;
use App\Models\Proposal\ProposalPengajuan;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

class ReportController extends BaseController
{
    protected ProposalReport $reportModel;
    protected ProposalPengajuan $proposalModel;

    public function __construct()
    {
        $this->reportModel = new ProposalReport();
        $this->proposalModel = new ProposalPengajuan();
    }

    /**
     * Store or update report file
     */
    public function upload(string $proposalUuid)
    {
        $userId = (int) (user()['id'] ?? 0);
        $proposal = $this->proposalModel->where('uuid', $proposalUuid)->first();

        if (!$proposal || (int) $proposal->user_id !== $userId) {
            return redirect()->back()->with('error', 'Proposal tidak ditemukan atau Anda tidak memiliki akses.');
        }

        if ($proposal->status !== 'approved') {
            return redirect()->back()->with('error', 'Hanya proposal yang disetujui yang dapat mengunggah laporan.');
        }

        $rules = [
            'kategori' => 'required|in_list[Laporan Antara,Laporan Keuangan Sementara,Laporan Akademik,Laporan Keuangan]',
            'berkas'   => 'uploaded[berkas]|max_size[berkas,10240]|ext_in[berkas,pdf]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Validasi gagal: ' . implode(', ', $this->validator->getErrors()));
        }

        $kategori = $this->request->getPost('kategori');
        $file = $this->request->getFile('berkas');

        // Check if already exists to replace
        $existing = $this->reportModel->getByProposalAndKategori($proposal->id, $kategori);

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/proposals/reports', $newName);
            $file_path = 'uploads/proposals/reports/' . $newName;

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
                    if ($existing && !empty($existing->file_path) && is_file(WRITEPATH . $existing->file_path)) {
                        @unlink(WRITEPATH . $existing->file_path);
                    }
                    $this->reportModel->update($existing->id, $data);
                } else {
                    $this->reportModel->insert($data);
                }

                return redirect()->back()->with('success', "Laporan '{$kategori}' berhasil diunggah.");
            } catch (Exception $e) {
                return redirect()->back()->with('error', 'Gagal mengunggah laporan: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('error', 'File tidak valid.');
    }
}
