<?php

namespace App\Controllers\Dosen\Proposal;

use App\Controllers\BaseController;
use App\Services\Proposal\ProposalWizardService;
use App\Services\Proposal\ProposalMasterOptionService;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * ProposalController
 *
 * Thin Controller untuk Proposal Wizard
 * Prinsip:
 * - Ambil request
 * - Delegasi ke service
 * - Return response
 * 
 * Tidak ada business logic di sini!
 */
class ProposalController extends BaseController
{
    private const REDIRECT_INDEX = 'dosen/proposals';
    private const NEW_PROPOSAL_TOKEN = '__new__';

    protected ProposalWizardService $wizardService;
    protected ProposalMasterOptionService $masterService;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ): void {
        parent::initController($request, $response, $logger);
        $this->wizardService = new ProposalWizardService();
        $this->masterService = new ProposalMasterOptionService();
    }

    /**
     * Helper: Get user ID from auth
     */
    private function userId(): int
    {
        return (int) (user()['id'] ?? 0);
    }

    /**
     * List all proposals for current user
     */
    public function index()
    {
        $userId = $this->userId();

        return $this->renderView('dosen/proposals/index', [
            'title' => 'Proposal Saya',
            'proposals' => $this->prepareProposalIndexPayload(
                $this->wizardService->getProposalsByUser($userId)
            ),
        ]);
    }

    /**
     * Create new proposal (init wizard)
     */
    public function create()
    {
        if ($this->userId() <= 0) {
            return redirect()
                ->to(self::REDIRECT_INDEX)
                ->with('error', 'User tidak terautentikasi dengan benar. Silakan login kembali.');
        }

        return $this->renderProposalStepForm(1, self::NEW_PROPOSAL_TOKEN, []);
    }

    /**
     * Store new proposal (POST create)
     * Redirects to step 1 form
     */
    public function store()
    {
        return $this->create();
    }

    /**
     * Display form for specific step
     */
    public function step(int $stepNum, string $uuid)
    {
        $userId = $this->userId();
        $proposal = $this->wizardService->getProposal($userId, $uuid);

        if (!$proposal) {
            return redirect()
                ->to(self::REDIRECT_INDEX)
                ->with('error', 'Proposal tidak ditemukan');
        }

        // Check authorization
        if ((int) $proposal->user_id !== $userId) {
            return redirect()
                ->to(self::REDIRECT_INDEX)
                ->with('error', 'Akses ditolak');
        }

        return $this->renderProposalStepForm($stepNum, $proposal->uuid, (array) $proposal);
    }

    /**
     * Save step data (POST form)
     */
    public function saveStep(int $stepNum, string $uuid)
    {
        $userId = $this->userId();
        $formData = $this->request->getPost();

        if ($stepNum === 1 && $uuid === self::NEW_PROPOSAL_TOKEN) {
            $createdUuid = $this->wizardService->createDraftFromStepOne($userId, $formData);

            if (!$createdUuid) {
                return redirect()
                    ->to(site_url('dosen/proposals/create'))
                    ->with('error', $this->wizardService->getLastError())
                    ->withInput();
            }

            return redirect()
                ->to("dosen/proposals/step/2/{$createdUuid}")
                ->with('success', 'Step 1 berhasil disimpan.');
        }

        try {
            $result = $this->wizardService->saveStepData(
                $userId,
                $uuid,
                $stepNum,
                $formData
            );

            if (!$result) {
                return redirect()
                    ->to("dosen/proposals/step/{$stepNum}/{$uuid}")
                    ->with('error', $this->wizardService->getLastError())
                    ->withInput();
            }

            // Redirect ke step berikutnya atau review
            $nextStep = $stepNum + 1;
            if ($nextStep > 5) {
                return redirect()
                    ->to("dosen/proposals/review/{$uuid}")
                    ->with('success', 'Step ' . $stepNum . ' berhasil disimpan. Silakan review proposal Anda.');
            }

            return redirect()
                ->to("dosen/proposals/step/{$nextStep}/{$uuid}")
                ->with('success', 'Step ' . $stepNum . ' berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()
                ->to("dosen/proposals/step/{$stepNum}/{$uuid}")
                ->with('error', 'Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Review page (ringkasan sebelum submit)
     */
    public function review(string $uuid)
    {
        $userId = $this->userId();
        $proposal = $this->wizardService->getProposal($userId, $uuid);

        if (!$proposal || (int) $proposal->user_id !== $userId) {
            return redirect()
                ->to(self::REDIRECT_INDEX)
                ->with('error', 'Proposal tidak ditemukan');
        }

        return $this->renderView('dosen/proposals/review', [
            'title' => 'Review Proposal',
            'proposal' => $this->prepareProposalReviewPayload($proposal),
            'proposalUuid' => $uuid,
        ]);
    }

    /**
     * Submit proposal (final)
     */
    public function submit(string $uuid)
    {
        $userId = $this->userId();

        try {
            $result = $this->wizardService->submitProposal($userId, $uuid);

            if (!$result) {
                return redirect()
                    ->to("dosen/proposals/review/{$uuid}")
                    ->with('error', 'Gagal submit proposal: ' . $this->wizardService->getLastError());
            }

            return redirect()
                ->to(self::REDIRECT_INDEX)
                ->with('success', 'Proposal berhasil disubmit untuk review.');
        } catch (\Exception $e) {
            return redirect()
                ->to("dosen/proposals/review/{$uuid}")
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Show proposal detail
     */
    public function show(string $uuid)
    {
        $userId = $this->userId();
        $proposal = $this->wizardService->getProposal($userId, $uuid);

        if (!$proposal || (int) $proposal->user_id !== $userId) {
            return redirect()
                ->to(self::REDIRECT_INDEX)
                ->with('error', 'Proposal tidak ditemukan');
        }

        if ($this->isUntouchedDraft($proposal)) {
            return redirect()
                ->to(self::REDIRECT_INDEX)
                ->with('error', 'Proposal ini belum memiliki data yang tersimpan.');
        }

        return $this->renderView('dosen/proposals/show', [
            'title' => 'Detail Proposal',
            'proposal' => $this->prepareProposalDetailPayload($proposal),
        ]);
    }

    public function delete(string $uuid)
    {
        $userId = $this->userId();

        if (!$this->wizardService->deleteProposal($userId, $uuid)) {
            return redirect()
                ->to(self::REDIRECT_INDEX)
                ->with('error', $this->wizardService->getLastError());
        }

        return redirect()
            ->to(self::REDIRECT_INDEX)
            ->with('success', 'Proposal berhasil dihapus.');
    }

    /**
     * Prepare payload untuk index page
     */
    private function prepareProposalIndexPayload(array $proposals): array
    {
        $visibleProposals = array_values(array_filter(
            $proposals,
            fn($proposal) => !$this->isUntouchedDraft($proposal)
        ));

        return array_map(function ($proposal) {
            $statusBadgeClass = match ($proposal->status ?? 'draft') {
                'draft' => 'text-bg-warning',
                'submitted' => 'text-bg-info',
                'reviewed' => 'text-bg-secondary',
                'approved' => 'text-bg-success',
                'rejected' => 'text-bg-danger',
                default => 'text-bg-light',
            };

            $statusLabel = ucfirst($proposal->status ?? 'draft');
            $status = $proposal->status ?? 'draft';
            $currentStep = (int) ($proposal->current_step ?? 1);

            $primaryActionLabel = $status === 'draft' ? 'Lanjutkan' : 'Detail';
            $primaryActionIcon = $status === 'draft' ? 'fas fa-pen' : 'fas fa-eye';
            $primaryActionUrl = $status === 'draft'
                ? site_url("dosen/proposals/step/{$currentStep}/{$proposal->uuid}")
                : site_url("dosen/proposals/show/{$proposal->uuid}");

            return [
                'uuid' => $proposal->uuid,
                'judul' => $proposal->judul ?? 'Judul belum diisi',
                'status' => $status,
                'status_badge_class' => $statusBadgeClass,
                'status_label' => $statusLabel,
                'current_step' => $currentStep,
                'created_at_formatted' => date_format(
                    date_create($proposal->created_at),
                    'd M Y'
                ),
                'show_url' => site_url("dosen/proposals/show/{$proposal->uuid}"),
                'edit_url' => site_url("dosen/proposals/step/{$currentStep}/{$proposal->uuid}"),
                'delete_url' => site_url("dosen/proposals/delete/{$proposal->uuid}"),
                'primary_action_label' => $primaryActionLabel,
                'primary_action_icon' => $primaryActionIcon,
                'primary_action_url' => $primaryActionUrl,
            ];
        }, $visibleProposals);
    }

    private function renderProposalStepForm(int $stepNum, string $proposalId, array $proposal): string
    {
        return $this->renderView('dosen/proposals/form', [
            'title' => 'Buat Proposal',
            'currentStep' => $stepNum,
            'stepTitle' => $this->getStepTitle($stepNum),
            'stepLabels' => [
                1 => 'Pernyataan Peneliti',
                2 => 'Data Peneliti',
                3 => 'Substansi Usulan',
                4 => 'Unggah Berkas',
                5 => 'Data Jurnal',
            ],
            'proposalId' => $proposalId,
            'proposal' => $proposal,
            'formAction' => site_url("dosen/proposals/step/{$stepNum}/{$proposalId}"),
            'masterOptions' => $this->masterService->getAllOptions(),
        ]);
    }

    private function isUntouchedDraft(object $proposal): bool
    {
        if (($proposal->status ?? null) !== 'draft') {
            return false;
        }

        if (trim((string) ($proposal->judul ?? '')) !== '') {
            return false;
        }

        $stepKeys = ['step_1_data', 'step_2_data', 'step_3_data', 'step_4_data', 'step_5_data'];

        foreach ($stepKeys as $stepKey) {
            $stepData = json_decode((string) ($proposal->{$stepKey} ?? '[]'), true);

            if (!empty($stepData)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Prepare payload untuk review page
     */
    private function prepareProposalReviewPayload($proposal): array
    {
        $detail = $this->prepareProposalDetailPayload($proposal);
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

    /**
     * Prepare review Step 1 display items.
     */
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
            $pengelolaNama = $this->masterService->getOptionNama('pengelola_bantuan', $pengelolaId);
        }

        $bidangNama = trim((string) ($detail['bidang_ilmu_nama'] ?? ''));
        if ($bidangNama === '') {
            $bidangNama = $this->masterService->getOptionNama('bidang_ilmu', $bidangId);
        }

        $klasterNama = trim((string) ($detail['klaster_bantuan_nama'] ?? ''));
        if ($klasterNama === '') {
            $klasterNama = $this->masterService->getOptionNama('klaster_bantuan', $klasterId);
        }

        $temaNama = trim((string) ($detail['tema_penelitian_nama'] ?? ''));
        if ($temaNama === '') {
            $temaNama = $this->masterService->getOptionNama('tema_penelitian', $temaId);
        }

        $jenisNama = trim((string) ($detail['jenis_penelitian_nama'] ?? ''));
        if ($jenisNama === '') {
            $jenisNama = $this->masterService->getOptionNama('jenis_penelitian', $jenisId);
        }

        $kontribusiNama = trim((string) ($detail['kontribusi_prodi_nama'] ?? ''));
        if ($kontribusiNama === '') {
            $kontribusiNama = $this->masterService->getOptionNama('kontribusi_prodi', $kontribusiId);
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

    /**
     * Prepare step 2 review sections.
     */
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

    /**
     * Prepare journal links for review page.
     */
    private function prepareProposalReviewLinks(array $step5): array
    {
        return [
            $this->prepareProposalLinkItem('Website Jurnal', $step5['url_website'] ?? ''),
            $this->prepareProposalLinkItem('Scopus / WoS', $step5['url_scopus_wos'] ?? ''),
            $this->prepareProposalLinkItem('Surat Rekomendasi', $step5['url_surat_rekomendasi'] ?? ''),
        ];
    }

    /**
     * Prepare a single external link item.
     */
    private function prepareProposalLinkItem(string $label, string $url): array
    {
        $url = trim($url);
        $host = '';

        if ($url !== '') {
            $host = parse_url($url, PHP_URL_HOST) ?: $url;
            $host = preg_replace('/^www\./', '', (string) $host);
        }

        return [
            'label' => $label,
            'url' => $url,
            'host' => $host,
            'has_url' => $url !== '',
        ];
    }

    /**
     * Prepare payload untuk detail/show page
     */
    private function prepareProposalDetailPayload($proposal): array
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

        $pengelolaNama = $this->masterService->getOptionNama('pengelola_bantuan', $pengelolaId);
        $klasterNama = $this->masterService->getOptionNama('klaster_bantuan', $klasterId);
        $bidangNama = $this->masterService->getOptionNama('bidang_ilmu', $bidangId);
        $temaNama = $this->masterService->getOptionNama('tema_penelitian', $temaId);
        $jenisNama = $this->masterService->getOptionNama('jenis_penelitian', $jenisId);
        $kontribusiNama = $this->masterService->getOptionNama('kontribusi_prodi', $kontribusiId);

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
            'status_label' => ucfirst($proposal->status),
            'current_step' => (int) ($proposal->current_step ?? 1),
            'created_at_formatted' => date_format(date_create($proposal->created_at), 'd M Y H:i'),
            'overview_cards' => [
                [
                    'label' => 'Status Proposal',
                    'value' => ucfirst((string) ($proposal->status ?? 'draft')),
                    'icon' => 'fas fa-chart-line',
                ],
                [
                    'label' => 'Dibuat',
                    'value' => date_format(date_create($proposal->created_at), 'd M Y H:i'),
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
                'url_scopus_wos' => $step5['url_scopus_wos'] ?? '',
                'url_surat_rekomendasi' => $step5['url_surat_rekomendasi'] ?? '',
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
        ];
    }

    /**
     * Prepare team summary sections for the show page.
     */
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

    /**
     * Prepare document summary rows for the show page.
     */
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

    private function formatRupiah(int|float|string|null $amount): string
    {
        if ($amount === null || $amount === '') {
            return '-';
        }

        return 'Rp ' . number_format((float) $amount, 0, ',', '.');
    }

    private function prepareProposalDocuments(int $proposalId): array
    {
        $documents = (new \App\Models\Proposal\ProposalDokumen())->getByProposal($proposalId);

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
                'view_url' => base_url($document->path_file),
                'file_size' => $document->file_size,
                'file_size_label' => $this->formatFileSize((int) ($document->file_size ?? 0)),
            ];
        }, $documents);
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

    /**
     * Format keywords for display
     */
    private function formatKeywords(string $keywords): string
    {
        if (trim($keywords) === '') {
            return '-';
        }

        $keywordArray = array_map('trim', explode(',', $keywords));
        return implode(', ', $keywordArray);
    }

    /**
     * Get step title
     */
    private function getStepTitle(int $step): string
    {
        return match ($step) {
            1 => 'Pernyataan Peneliti',
            2 => 'Data Peneliti',
            3 => 'Substansi Usulan',
            4 => 'Unggah Berkas',
            5 => 'Data Jurnal',
            default => 'Step ' . $step,
        };
    }
}
