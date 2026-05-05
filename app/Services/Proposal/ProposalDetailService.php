<?php

namespace App\Services\Proposal;

use App\Models\Proposal\ProposalDokumen;
use App\Models\Proposal\ProposalReviewerAssignment;
use App\Models\Proposal\ProposalLogbook;
use App\Models\Proposal\ProposalOutput;
use App\Models\Proposal\ProposalReport;
use App\Models\Proposal\ProposalOutcome;

class ProposalDetailService
{
    private ProposalMasterOptionService $masterOptionService;

    private ProposalDokumen $proposalDokumenModel;

    private ProposalReviewerAssignment $assignmentModel;

    private ProposalLogbook $logbookModel;

    private ProposalOutput $outputModel;

    private ProposalReport $reportModel;

    private ProposalOutcome $outcomeModel;

    public function __construct()
    {
        $this->masterOptionService = new ProposalMasterOptionService();
        $this->proposalDokumenModel = new ProposalDokumen();
        $this->assignmentModel = new ProposalReviewerAssignment();
        $this->logbookModel = new ProposalLogbook();
        $this->outputModel = new ProposalOutput();
        $this->reportModel = new ProposalReport();
        $this->outcomeModel = new ProposalOutcome();
    }

    public function buildDetailPayload(object $proposal): array
    {
        $step1 = json_decode($proposal->step_1_data ?? '[]', true) ?: [];
        $step2 = json_decode($proposal->step_2_data ?? '[]', true) ?: [];
        $step3 = json_decode($proposal->step_3_data ?? '[]', true) ?: [];
        $step5 = json_decode($proposal->step_5_data ?? '[]', true) ?: [];

        $judul = !empty($proposal->judul) ? $proposal->judul : ($step1['judul'] ?? '');
        $kataKunci = !empty($proposal->kata_kunci) ? $proposal->kata_kunci : ($step1['kata_kunci'] ?? '');
        $pengelolaId = !empty($proposal->pengelola_bantuan_id) ? $proposal->pengelola_bantuan_id : ($step1['pengelola_bantuan_id'] ?? null);
        $klasterId = !empty($proposal->klaster_bantuan_id) ? $proposal->klaster_bantuan_id : ($step1['klaster_bantuan_id'] ?? null);
        $bidangId = !empty($proposal->bidang_ilmu_id) ? $proposal->bidang_ilmu_id : ($step1['bidang_ilmu_id'] ?? null);
        $temaId = !empty($proposal->tema_penelitian_id) ? $proposal->tema_penelitian_id : ($step1['tema_penelitian_id'] ?? null);
        $jenisId = !empty($proposal->jenis_penelitian_id) ? $proposal->jenis_penelitian_id : ($step1['jenis_penelitian_id'] ?? null);
        $kontribusiId = !empty($proposal->kontribusi_prodi_id) ? $proposal->kontribusi_prodi_id : ($step1['kontribusi_prodi_id'] ?? null);

        $pengelolaNama = $this->masterOptionService->getOptionNama('pengelola_bantuan', $pengelolaId);
        $klasterNama = $this->masterOptionService->getOptionNama('klaster_bantuan', $klasterId);
        $bidangNama = $this->masterOptionService->getOptionNama('bidang_ilmu', $bidangId);
        $temaNama = $this->masterOptionService->getOptionNama('tema_penelitian', $temaId);
        $jenisNama = $this->masterOptionService->getOptionNama('jenis_penelitian', $jenisId);
        $kontribusiNama = $this->masterOptionService->getOptionNama('kontribusi_prodi', $kontribusiId);

        return [
            'uuid' => $proposal->uuid,
            'title' => $judul,
            'judul' => $judul,
            'kata_kunci_formatted' => $this->formatKeywords($kataKunci),
            'pengelola_bantuan_nama' => $pengelolaNama,
            'klaster_bantuan_nama' => $klasterNama,
            'bidang_ilmu_nama' => $bidangNama,
            'tema_penelitian_nama' => $temaNama,
            'jenis_penelitian_nama' => $jenisNama,
            'kontribusi_prodi_nama' => $kontribusiNama,
            'status' => $proposal->status,
            'status_badge_class' => match ($proposal->status) {
                'draft' => 'text-bg-warning',
                'submitted' => 'text-bg-info',
                'reviewed' => 'text-bg-secondary',
                'approved' => 'text-bg-success',
                'rejected' => 'text-bg-danger',
                default => 'text-bg-light',
            },
            'status_label' => ucfirst((string) $proposal->status),
            'current_step' => (int) ($proposal->current_step ?? 1),
            'created_at' => $proposal->created_at,
            'updated_at' => $proposal->updated_at,
            'created_at_formatted' => format_indo($proposal->created_at, true),
            'overview_cards' => [
                [
                    'label' => 'Status Proposal',
                    'value' => ucfirst((string) ($proposal->status ?? 'draft')),
                    'icon' => 'fas fa-chart-line',
                ],
                [
                    'label' => 'Dibuat',
                    'value' => format_indo($proposal->created_at, true),
                    'icon' => 'fas fa-calendar-days',
                ],
                [
                    'label' => 'Pengelola Bantuan',
                    'value' => $pengelolaNama ?: '-',
                    'icon' => 'fas fa-handshake',
                ],
                [
                    'label' => 'Klaster Bantuan',
                    'value' => $klasterNama ?: '-',
                    'icon' => 'fas fa-layer-group',
                ],
            ],
            'summary' => [
                'judul' => $judul ?: '-',
                'kata_kunci' => $this->formatKeywords($kataKunci),
                'pengelola_bantuan' => $pengelolaNama ?: '-',
                'klaster_bantuan' => $klasterNama ?: '-',
                'bidang_ilmu' => $bidangNama ?: '-',
                'tema_penelitian' => $temaNama ?: '-',
                'jenis_penelitian' => $jenisNama ?: '-',
                'kontribusi_prodi' => $kontribusiNama ?: '-',
                'issn' => $step5['issn'] ?? '',
                'nama_jurnal' => $step5['nama_jurnal'] ?? '',
                'url_website' => $step5['url_website'] ?? '',
                'url_website_formatted' => $this->formatJournalLink($step5['url_website'] ?? null, 'Buka Website Jurnal'),
                'url_scopus_wos' => $step5['url_scopus_wos'] ?? '',
                'url_scopus_wos_formatted' => $this->formatJournalLink($step5['url_scopus_wos'] ?? null, 'Buka Scopus/WoS'),
                'url_surat_rekomendasi' => $step5['url_surat_rekomendasi'] ?? '',
                'url_surat_rekomendasi_formatted' => $this->formatJournalLink($step5['url_surat_rekomendasi'] ?? null, 'Buka Surat Rekomendasi'),
                'total_pengajuan_dana' => $step5['total_pengajuan_dana'] ?? '',
            ],
            'review_summary' => [
                'abstrak' => $step3['abstrak'] ?? '',
                'validator_notes' => '',
                'validator_notes_display' => 'Kotak catatan validator akan digunakan setelah halaman review tersedia.',
            ],
            'team_sections' => $this->prepareProposalTeamSections($step2),
            'document_rows' => $this->prepareProposalDocumentRows((int) $proposal->id),
            'review' => [
                'judul' => $proposal->judul,
                'abstrak' => $step3['abstrak'] ?? '',
                'substansi_bagian' => $step3['substansi_bagian'] ?? [],
            ],
            'all_steps_data' => [
                'step_1' => $step1,
                'step_3' => $step3,
                'step_5' => $step5,
            ],
            'reviewer_results' => $this->prepareAnonymizedReviewerResults((int) $proposal->id),
            'logbooks' => $this->prepareLogbookData((int) $proposal->id),
            'outputs' => $this->prepareOutputData((int) $proposal->id),
            'reports' => $this->prepareReportData((int) $proposal->id),
            'finance' => [
                'proposed_amount' => $proposal->total_pengajuan_dana ?? 0,
                'approved_amount' => $proposal->approved_amount ?? 0,
                'proposed_formatted' => number_format((int) ($proposal->total_pengajuan_dana ?? 0), 0, ',', '.'),
                'approved_formatted' => number_format((int) ($proposal->approved_amount ?? 0), 0, ',', '.'),
            ],
            'outcomes' => $this->prepareOutcomeData((int) $proposal->id),
            'admin_decision' => [
                'notes' => $proposal->admin_notes ?? '',
                'outcome_notes' => $proposal->outcome_admin_notes ?? '',
                'decided_at' => !empty($proposal->decided_at) ? format_indo($proposal->decided_at, true) : null,
                'is_decided' => !empty($proposal->decided_at),
            ],
        ];
    }

    public function buildReviewPayload(object $proposal): array
    {
        $detail = $this->buildDetailPayload($proposal);
        $step1 = json_decode($proposal->step_1_data ?? '[]', true) ?: [];
        $step2 = json_decode($proposal->step_2_data ?? '[]', true) ?: [];
        $step3 = json_decode($proposal->step_3_data ?? '[]', true) ?: [];
        $step4 = json_decode($proposal->step_4_data ?? '[]', true) ?: [];
        $step5 = json_decode($proposal->step_5_data ?? '[]', true) ?: [];
        $documents = $this->prepareProposalDocuments((int) $proposal->id);
        $penelitiInternalCount = count(array_filter($step2['peneliti_internal'] ?? [], fn($item) => !empty(trim((string) ($item['nama'] ?? '')))));
        $mahasiswaCount = count(array_filter($step2['mahasiswa'] ?? [], fn($item) => !empty(trim((string) ($item['nama'] ?? '')))));
        $reviewStep1Items = $this->prepareProposalReviewStep1Items($proposal, $detail, $step1);

        return array_merge($detail, [
            'review_overview_cards' => [
                ['label' => 'Peneliti Internal', 'value' => (string) $penelitiInternalCount, 'icon' => 'fas fa-users'],
                ['label' => 'Mahasiswa', 'value' => (string) $mahasiswaCount, 'icon' => 'fas fa-user-graduate'],
                ['label' => 'Dokumen', 'value' => (string) count($documents), 'icon' => 'fas fa-folder-open'],
                ['label' => 'Tahap', 'value' => '5/5', 'icon' => 'fas fa-list-check'],
            ],
            'review_step1_items' => $reviewStep1Items,
            'review_step2_sections' => $this->prepareProposalReviewStep2Sections($step2),
            'review_step3_summary' => [
                'abstrak' => $step3['abstrak'] ?? '',
                'substansi_bagian' => array_values(array_filter($step3['substansi_bagian'] ?? [], fn($item) => !empty(trim((string) ($item['judul_bagian'] ?? ''))) || !empty(trim(strip_tags((string) ($item['isi_bagian'] ?? '')))))),
            ],
            'review_step5_summary' => [
                'issn' => $step5['issn'] ?? '',
                'nama_jurnal' => $step5['nama_jurnal'] ?? '',
                'profil_jurnal' => $step5['profil_jurnal'] ?? '',
                'total_pengajuan_dana' => $step5['total_pengajuan_dana'] ?? '',
                'links' => $this->prepareProposalReviewLinks($step5),
            ],
            'all_steps_data' => [
                'step_1' => $step1,
                'step_2' => $step2,
                'step_3' => $step3,
                'step_4' => $step4,
                'step_5' => $step5,
            ],
            'documents' => $documents,
        ]);
    }

    private function prepareProposalReviewStep1Items(object $proposal, array $detail, array $step1): array
    {
        $judul = trim((string) ($detail['judul'] ?? ''));
        if ($judul === '') {
            $judul = trim((string) ($step1['judul'] ?? $proposal->judul ?? ''));
        }

        if ($judul === '') {
            $judul = '-';
        }

        $kataKunci = trim((string) ($detail['kata_kunci_formatted'] ?? ''));
        if ($kataKunci === '' || $kataKunci === '-') {
            $kataKunci = $this->formatKeywords((string) ($step1['kata_kunci'] ?? $proposal->kata_kunci ?? ''));
        }

        $pengelolaId = (int) ($step1['pengelola_bantuan_id'] ?? $proposal->pengelola_bantuan_id ?? 0);
        $klasterId = (int) ($step1['klaster_bantuan_id'] ?? $proposal->klaster_bantuan_id ?? 0);
        $bidangId = (int) ($step1['bidang_ilmu_id'] ?? $proposal->bidang_ilmu_id ?? 0);
        $temaId = (int) ($step1['tema_penelitian_id'] ?? $proposal->tema_penelitian_id ?? 0);
        $jenisId = (int) ($step1['jenis_penelitian_id'] ?? $proposal->jenis_penelitian_id ?? 0);
        $kontribusiId = (int) ($step1['kontribusi_prodi_id'] ?? $proposal->kontribusi_prodi_id ?? 0);

        $pengelolaNama = trim((string) ($detail['pengelola_bantuan_nama'] ?? ''));
        if ($pengelolaNama === '') {
            $pengelolaNama = $this->masterOptionService->getOptionNama('pengelola_bantuan', $pengelolaId);
        }

        $bidangNama = trim((string) ($detail['bidang_ilmu_nama'] ?? ''));
        if ($bidangNama === '') {
            $bidangNama = $this->masterOptionService->getOptionNama('bidang_ilmu', $bidangId);
        }

        $klasterNama = trim((string) ($detail['klaster_bantuan_nama'] ?? ''));
        if ($klasterNama === '') {
            $klasterNama = $this->masterOptionService->getOptionNama('klaster_bantuan', $klasterId);
        }

        $temaNama = trim((string) ($detail['tema_penelitian_nama'] ?? ''));
        if ($temaNama === '') {
            $temaNama = $this->masterOptionService->getOptionNama('tema_penelitian', $temaId);
        }

        $jenisNama = trim((string) ($detail['jenis_penelitian_nama'] ?? ''));
        if ($jenisNama === '') {
            $jenisNama = $this->masterOptionService->getOptionNama('jenis_penelitian', $jenisId);
        }

        $kontribusiNama = trim((string) ($detail['kontribusi_prodi_nama'] ?? ''));
        if ($kontribusiNama === '') {
            $kontribusiNama = $this->masterOptionService->getOptionNama('kontribusi_prodi', $kontribusiId);
        }

        return [
            ['label' => 'Judul', 'value' => $judul],
            ['label' => 'Kata Kunci', 'value' => $kataKunci ?: '-'],
            ['label' => 'Pengelola Bantuan', 'value' => $pengelolaNama ?: '-'],
            ['label' => 'Bidang Ilmu', 'value' => $bidangNama ?: '-'],
            ['label' => 'Klaster Bantuan', 'value' => $klasterNama ?: '-'],
            ['label' => 'Tema Penelitian', 'value' => $temaNama ?: '-'],
            ['label' => 'Jenis Penelitian', 'value' => $jenisNama ?: '-'],
            ['label' => 'Kontribusi Prodi', 'value' => $kontribusiNama ?: '-'],
        ];
    }

    private function prepareProposalReviewStep2Sections(array $step2): array
    {
        $penelitiInternal = array_values(array_filter($step2['peneliti_internal'] ?? [], fn($item) => !empty(trim((string) ($item['nama'] ?? '')))));
        $mahasiswa = array_values(array_filter($step2['mahasiswa'] ?? [], fn($item) => !empty(trim((string) ($item['nama'] ?? '')))));
        $anggotaEksternal = array_values(array_filter($step2['anggota_eksternal'] ?? [], fn($item) => !empty(trim((string) ($item['nama'] ?? '')))));

        return [
            [
                'title' => 'Peneliti Internal',
                'columns' => ['Jabatan', 'Nama', 'NIP', 'Email', 'Asal Instansi'],
                'rows' => array_map(static function (array $item, int $index): array {
                    return [
                        'cells' => [
                            $index === 0 ? 'Ketua' : 'Anggota ' . $index,
                            $item['nama'] ?? '-',
                            $item['nip'] ?? '-',
                            $item['email'] ?? '-',
                            $item['asal_instansi'] ?? '-',
                        ],
                    ];
                }, $penelitiInternal, array_keys($penelitiInternal)),
                'empty_message' => 'Belum ada data peneliti internal.',
                'colspan' => 5,
            ],
            [
                'title' => 'Mahasiswa',
                'columns' => ['Nama', 'NIM', 'Program Studi', 'Email'],
                'rows' => array_map(static function (array $item): array {
                    return [
                        'cells' => [
                            $item['nama'] ?? '-',
                            $item['nim'] ?? '-',
                            $item['program_studi_id'] ?? '-',
                            $item['email'] ?? '-',
                        ],
                    ];
                }, $mahasiswa),
                'empty_message' => 'Belum ada data mahasiswa.',
                'colspan' => 4,
            ],
            [
                'title' => 'Anggota Eksternal',
                'columns' => ['Nama', 'Institusi', 'Posisi', 'Email', 'Tipe'],
                'rows' => array_map(static function (array $item): array {
                    return [
                        'cells' => [
                            $item['nama'] ?? '-',
                            $item['institusi'] ?? '-',
                            $item['posisi'] ?? '-',
                            $item['email'] ?? '-',
                            $item['tipe'] ?? '-',
                        ],
                    ];
                }, $anggotaEksternal),
                'empty_message' => 'Belum ada data anggota eksternal.',
                'colspan' => 5,
            ],
        ];
    }

    private function prepareProposalReviewLinks(array $step5): array
    {
        return [
            [
                'label' => 'Website Jurnal',
                'url' => $step5['url_website'] ?? '',
            ],
            [
                'label' => 'Scopus / WoS',
                'url' => $step5['url_scopus_wos'] ?? '',
            ],
            [
                'label' => 'Surat Rekomendasi',
                'url' => $step5['url_surat_rekomendasi'] ?? '',
            ],
        ];
    }

    private function prepareProposalTeamSections(array $step2): array
    {
        $penelitiRows = array_values(array_filter(
            $step2['peneliti_internal'] ?? [],
            fn($item) => !empty(trim((string) ($item['nama'] ?? '')))
        ));
        $mahasiswaRows = array_values(array_filter(
            $step2['mahasiswa'] ?? [],
            fn($item) => !empty(trim((string) ($item['nama'] ?? '')))
        ));
        $eksternalRows = array_values(array_filter(
            $step2['anggota_eksternal'] ?? [],
            fn($item) => !empty(trim((string) ($item['nama'] ?? '')))
        ));

        return [
            [
                'title' => 'Peneliti (PTKI)',
                'icon' => 'fas fa-users',
                'headers' => ['Jabatan', 'Nama', 'NIP', 'NIDN', 'Institusi', 'ID Peneliti'],
                'rows' => array_map(static function (array $item, int $index): array {
                    $jabatan = trim((string) ($item['posisi'] ?? '')) !== '' ? $item['posisi'] : ($index === 0 ? 'Ketua' : 'Anggota');

                    return [
                        'cells' => [
                            $jabatan,
                            $item['nama'] ?? '-',
                            $item['nip'] ?? '-',
                            '-',
                            $item['asal_instansi'] ?? '-',
                            '-',
                        ],
                    ];
                }, $penelitiRows, array_keys($penelitiRows)),
                'colspan' => 6,
                'empty_message' => 'Data peneliti belum diisi.',
            ],
            [
                'title' => 'Mahasiswa Pembantu Peneliti',
                'icon' => 'fas fa-user-graduate',
                'headers' => ['No', 'NIM', 'Nama', 'Program Studi'],
                'rows' => array_map(static function (array $item, int $index): array {
                    return [
                        'cells' => [
                            $index + 1,
                            $item['nim'] ?? '-',
                            $item['nama'] ?? '-',
                            $item['program_studi_id'] ?? '-',
                        ],
                    ];
                }, $mahasiswaRows, array_keys($mahasiswaRows)),
                'colspan' => 4,
                'empty_message' => 'Data mahasiswa belum diisi.',
            ],
            [
                'title' => 'Anggota Peneliti PTU / Profesional',
                'icon' => 'fas fa-briefcase',
                'headers' => ['No.', 'NIDN / NIK', 'Nama Peneliti', 'Institusi'],
                'rows' => array_map(static function (array $item, int $index): array {
                    return [
                        'cells' => [
                            $index + 1,
                            '-',
                            $item['nama'] ?? '-',
                            $item['institusi'] ?? '-',
                        ],
                    ];
                }, $eksternalRows, array_keys($eksternalRows)),
                'colspan' => 4,
                'empty_message' => 'Data anggota eksternal belum diisi.',
            ],
        ];
    }

    private function prepareProposalDocumentRows(int $proposalId): array
    {
        $documents = $this->prepareProposalDocuments($proposalId);
        $documentMap = [];

        foreach ($documents as $document) {
            $type = (string) ($document['type'] ?? '');
            if ($type !== '') {
                $documentMap[$type] = $document;
            }
        }

        $rows = [];
        foreach (['proposal' => 'Proposal', 'rab' => 'RAB', 'similarity' => 'Similarity Check', 'pendukung' => 'Dokumen Pendukung'] as $type => $label) {
            $document = $documentMap[$type] ?? null;
            $rows[] = [
                'label' => $label,
                'file_name' => $document['nama_file'] ?? '-',
                'file_size_label' => $document['file_size_label'] ?? '-',
                'view_url' => $document['view_url'] ?? '',
                'has_file' => !empty($document['view_url']),
            ];
        }

        return $rows;
    }

    private function prepareProposalDocuments(int $proposalId): array
    {
        $documents = $this->proposalDokumenModel->getByProposal($proposalId);

        return array_map(function ($document) {
            $labels = [
                'proposal' => 'Proposal',
                'rab' => 'RAB',
                'similarity' => 'Similarity Check',
                'pendukung' => 'Dokumen Pendukung',
            ];

            return [
                'type' => $document->tipe_dokumen,
                'label' => $labels[$document->tipe_dokumen] ?? ucfirst($document->tipe_dokumen),
                'nama_file' => $document->nama_file,
                'path_file' => $document->path_file,
                'view_url' => site_url('files/view/proposal/' . $document->uuid),
                'download_url' => site_url('files/proposal/' . $document->uuid),
                'file_size' => $document->file_size,
                'file_size_label' => $this->formatFileSize((int) ($document->file_size ?? 0)),
            ];
        }, $documents);
    }

    private function formatKeywords(string $keywords): string
    {
        if (trim($keywords) === '') {
            return '-';
        }

        $keywordArray = array_map('trim', explode(',', $keywords));

        return implode(', ', $keywordArray);
    }

    private function formatFileSize(int $size): string
    {
        if ($size <= 0) {
            return '-';
        }

        if ($size >= 1024 * 1024) {
            return number_format($size / 1024 / 1024, 2) . ' MB';
        }

        return number_format($size / 1024, 2) . ' KB';
    }

    private function prepareAnonymizedReviewerResults(int $proposalId): array
    {
        $assignments = $this->assignmentModel->getActiveByProposal($proposalId);
        $results = [];

        foreach ($assignments as $index => $assignment) {
            if ($assignment->status !== 'reviewed') {
                continue;
            }

            $recommendationLabel = match ($assignment->recommendation) {
                'recommended' => 'Direkomendasikan',
                'revision' => 'Perlu Revisi',
                'rejected' => 'Ditolak',
                default => 'Tertunda',
            };

            $recommendationBadge = match ($assignment->recommendation) {
                'recommended' => 'text-bg-success',
                'revision' => 'text-bg-warning',
                'rejected' => 'text-bg-danger',
                default => 'text-bg-secondary',
            };

            $results[] = [
                'reviewer_label' => 'Reviewer ' . ($index + 1),
                'recommendation_label' => $recommendationLabel,
                'recommendation_badge' => $recommendationBadge,
                'notes' => $assignment->review_notes ?: 'Tidak ada catatan.',
                'reviewed_at' => $assignment->reviewed_at,
                'reviewed_at_formatted' => format_indo($assignment->reviewed_at),
                'score' => $assignment->review_score,
            ];
        }

        return $results;
    }

    private function prepareLogbookData(int $proposalId): array
    {
        $logbooks = $this->logbookModel->getByProposal($proposalId);

        return array_map(function ($log) {
            return [
                'uuid' => $log->uuid,
                'tanggal' => format_indo($log->tanggal),
                'tempat' => $log->tempat,
                'nama_kegiatan' => $log->nama_kegiatan,
                'teknik' => $log->teknik,
                'deskripsi' => $log->deskripsi_kegiatan,
                'berkas_url' => $log->berkas_path ? site_url('files/view/logbook/' . $log->uuid) : null,
                'download_url' => $log->berkas_path ? site_url('files/logbook/' . $log->uuid) : null,
                'delete_url' => site_url('dosen/proposals/logbook/delete/' . $log->uuid),
            ];
        }, $logbooks);
    }

    private function prepareOutputData(int $proposalId): array
    {
        $outputs = $this->outputModel->getByProposal($proposalId);
        $kategoriWajib = [
            'HKI',
            'Laporan Bantuan Lengkap',
            'Draft Artikel',
            'Dummy Buku',
            'Dokumen Kemanfaatan',
            'Executive Summary'
        ];

        $outputMap = [];
        foreach ($outputs as $out) {
            $outputMap[$out->kategori] = $out;
        }

        $result = [];
        foreach ($kategoriWajib as $kat) {
            $existing = $outputMap[$kat] ?? null;
            $result[] = [
                'kategori' => $kat,
                'file_path' => $existing ? $existing->file_path : null,
                'file_url' => ($existing && !empty($existing->uuid) && basename($existing->file_path) !== 'outputs') ? site_url('files/view/output/' . $existing->uuid) : null,
                'download_url' => ($existing && !empty($existing->uuid) && basename($existing->file_path) !== 'outputs') ? site_url('files/output/' . $existing->uuid) : null,
                'original_filename' => $existing ? $existing->original_filename : null,
                'uploaded_at' => $existing ? format_indo($existing->created_at, true) : null,
            ];
        }

        return $result;
    }

    private function prepareReportData(int $proposalId): array
    {
        $reports = $this->reportModel->getByProposal($proposalId);
        $kategoriWajib = [
            'Laporan Antara',
            'Laporan Keuangan Sementara',
            'Laporan Akademik',
            'Laporan Keuangan'
        ];

        $reportMap = [];
        foreach ($reports as $rep) {
            $reportMap[$rep->kategori] = $rep;
        }

        $result = [];
        foreach ($kategoriWajib as $kat) {
            $existing = $reportMap[$kat] ?? null;
            $result[$kat] = [
                'kategori' => $kat,
                'file_path' => $existing ? $existing->file_path : null,
                'file_url' => ($existing && !empty($existing->uuid) && basename($existing->file_path) !== 'reports') ? site_url('files/view/report/' . $existing->uuid) : null,
                'download_url' => ($existing && !empty($existing->uuid) && basename($existing->file_path) !== 'reports') ? site_url('files/report/' . $existing->uuid) : null,
                'original_filename' => $existing ? $existing->original_filename : null,
                'uploaded_at' => $existing ? format_indo($existing->created_at, true) : null,
            ];
        }

        return $result;
    }

    private function prepareOutcomeData(int $proposalId): array
    {
        $outcomes = $this->outcomeModel->getByProposal($proposalId);
        $result = [
            'journals' => [],
            'books' => []
        ];

        foreach ($outcomes as $out) {
            $item = (array) $out;
            if ($out->tipe === 'jurnal') {
                $result['journals'][] = $item;
            } else {
                $result['books'][] = $item;
            }
        }

        return $result;
    }

    private function formatJournalLink(?string $url, string $label): string
    {
        if (empty($url)) {
            return '-';
        }

        $host = parse_url($url, PHP_URL_HOST) ?: $url;
        $host = preg_replace('/^www\./', '', (string) $host);

        return '<div class="journal-link-item">'
            . '<span class="journal-link-host">' . esc($host) . '</span>'
            . '<a href="' . esc($url) . '" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary journal-link-btn">'
            . '<i class="bi bi-box-arrow-up-right me-1"></i>' . esc($label)
            . '</a>'
            . '</div>';
    }
}
