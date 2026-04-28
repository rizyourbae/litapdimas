<?php

namespace App\Services\Proposal;

use App\Models\Proposal\ProposalPengajuan;
use App\Models\Proposal\ProposalReviewerAssignment;
use Config\Database;
use Exception;
use Ramsey\Uuid\Uuid;

class AdminProposalService
{
    private const ADMIN_VISIBLE_STATUSES = ['submitted', 'assigned', 'reviewed', 'approved', 'rejected'];

    private const RECOMMENDED_REVIEWER_PREVIEW_LIMIT = 5;

    private ProposalPengajuan $proposalModel;

    private ProposalReviewerAssignment $assignmentModel;

    private ProposalDetailService $detailService;

    private $db;

    public function __construct()
    {
        $this->proposalModel = new ProposalPengajuan();
        $this->assignmentModel = new ProposalReviewerAssignment();
        $this->detailService = new ProposalDetailService();
        $this->db = Database::connect();
    }

    public function getIndexPayload(): array
    {
        $proposals = $this->getAdminVisibleProposals();
        $tableRows = [];
        $statusCounts = [
            'submitted' => 0,
            'assigned' => 0,
            'reviewed' => 0,
        ];

        foreach ($proposals as $index => $proposal) {
            $statusKey = (string) ($proposal->status ?? 'submitted');
            if (array_key_exists($statusKey, $statusCounts)) {
                $statusCounts[$statusKey]++;
            }

            $tableRows[] = [
                'number' => $index + 1,
                'title' => $this->valueOrFallback((string) ($proposal->judul ?? ''), 'Judul belum diisi'),
                'owner_name' => $proposal->pengusul_nama,
                'owner_email' => $proposal->pengusul_email ?: '-',
                'bidang_ilmu' => $this->valueOrFallback((string) ($proposal->proposal_bidang_ilmu_nama ?? ''), '-'),
                'status_label' => $this->mapProposalStatusLabel((string) ($proposal->status ?? 'submitted')),
                'status_badge_class' => $this->mapProposalStatusBadgeClass((string) ($proposal->status ?? 'submitted')),
                'reviewer_count' => (int) ($proposal->reviewer_count ?? 0),
                'updated_at_label' => $this->formatDateTime($proposal->updated_at ?? $proposal->created_at),
                'show_url' => site_url('admin/proposals/show/' . $proposal->uuid),
            ];
        }

        return [
            'metrics' => [
                [
                    'label' => 'Menunggu Penugasan',
                    'value' => (string) $statusCounts['submitted'],
                    'tone_class' => 'text-warning',
                    'caption' => 'Proposal submitted yang belum mendapat reviewer.',
                ],
                [
                    'label' => 'Sudah Ditugaskan',
                    'value' => (string) $statusCounts['assigned'],
                    'tone_class' => 'text-primary',
                    'caption' => 'Proposal yang sudah memiliki reviewer aktif.',
                ],
                [
                    'label' => 'Selesai Direview',
                    'value' => (string) $statusCounts['reviewed'],
                    'tone_class' => 'text-success',
                    'caption' => 'Proposal dengan seluruh rekomendasi reviewer terkumpul.',
                ],
                [
                    'label' => 'Reviewer Aktif',
                    'value' => (string) count($this->getReviewerCandidates()),
                    'tone_class' => 'text-dark',
                    'caption' => 'Reviewer yang bisa langsung ditugaskan dari panel admin.',
                ],
            ],
            'tableRows' => $tableRows,
        ];
    }

    public function getDetailPayload(string $proposalUuid): ?array
    {
        $proposal = $this->getProposalByUuid($proposalUuid);
        if ($proposal === null) {
            return null;
        }

        $detailPayload = $this->detailService->buildDetailPayload($proposal);
        $assignedReviewers = $this->getAssignedReviewerRows((int) $proposal->id, $proposal->uuid);
        $reviewerResultsPanel = $this->buildReviewerResultsPanel($proposal, $assignedReviewers);
        $candidateGroups = $this->buildReviewerCandidateGroups($proposal);
        $decisionSummary = $this->buildDecisionSummary($assignedReviewers);

        return [
            'hero' => [
                'title' => $this->valueOrFallback((string) ($detailPayload['judul'] ?? ''), 'Judul belum diisi'),
                'subtitle' => $proposal->pengusul_nama . ' · ' . $this->mapProposalStatusLabel((string) ($proposal->status ?? 'submitted')),
                'status_label' => $this->mapProposalStatusLabel((string) ($proposal->status ?? 'submitted')),
                'status_badge_class' => $this->mapProposalStatusBadgeClass((string) ($proposal->status ?? 'submitted')),
                'badges' => [
                    [
                        'label' => 'Proposal Masuk',
                        'class' => 'text-bg-light border',
                    ],
                    [
                        'label' => $this->valueOrFallback((string) ($detailPayload['bidang_ilmu_nama'] ?? ''), 'Bidang Ilmu Belum Dipilih'),
                        'class' => 'text-bg-primary',
                    ],
                    [
                        'label' => (string) count($assignedReviewers) . ' Reviewer',
                        'class' => 'text-bg-success',
                    ],
                ],
            ],
            'actions' => [
                'back_url' => site_url('admin/proposals'),
            ],
            'metrics' => [
                [
                    'label' => 'Status Proposal',
                    'value' => $this->mapProposalStatusLabel((string) ($proposal->status ?? 'submitted')),
                    'tone_class' => $this->mapMetricToneClass((string) ($proposal->status ?? 'submitted')),
                    'caption' => 'Status header proposal yang dikendalikan admin.',
                ],
                [
                    'label' => 'Reviewer Ditugaskan',
                    'value' => (string) count($assignedReviewers),
                    'tone_class' => 'text-primary',
                    'caption' => 'Jumlah reviewer aktif untuk proposal ini.',
                ],
                [
                    'label' => 'Rekomendasi Masuk',
                    'value' => (string) ($decisionSummary['reviewed_count'] ?? 0),
                    'tone_class' => 'text-success',
                    'caption' => 'Assignment yang sudah mengirim hasil review.',
                ],
                [
                    'label' => 'Terakhir Diperbarui',
                    'value' => $this->formatDateTime($proposal->updated_at ?? $proposal->created_at),
                    'tone_class' => 'text-dark',
                    'caption' => 'Waktu pembaruan terakhir proposal.',
                ],
            ],
            'summaryItems' => [
                ['label' => 'Pengusul', 'value' => $proposal->pengusul_nama],
                ['label' => 'Email Pengusul', 'value' => $this->valueOrFallback((string) ($proposal->pengusul_email ?? ''), '-')],
                ['label' => 'Judul', 'value' => $this->valueOrFallback((string) ($detailPayload['judul'] ?? ''), '-')],
                ['label' => 'Kata Kunci', 'value' => $this->valueOrFallback((string) ($detailPayload['kata_kunci_formatted'] ?? ''), '-')],
                ['label' => 'Pengelola Bantuan', 'value' => $this->valueOrFallback((string) ($detailPayload['pengelola_bantuan_nama'] ?? ''), '-')],
                ['label' => 'Klaster Bantuan', 'value' => $this->valueOrFallback((string) ($detailPayload['klaster_bantuan_nama'] ?? ''), '-')],
                ['label' => 'Bidang Ilmu Proposal', 'value' => $this->valueOrFallback((string) ($detailPayload['bidang_ilmu_nama'] ?? ''), '-')],
                ['label' => 'Tema Penelitian', 'value' => $this->valueOrFallback((string) ($detailPayload['tema_penelitian_nama'] ?? ''), '-')],
                ['label' => 'Jenis Penelitian', 'value' => $this->valueOrFallback((string) ($detailPayload['jenis_penelitian_nama'] ?? ''), '-')],
                ['label' => 'Kontribusi Prodi', 'value' => $this->valueOrFallback((string) ($detailPayload['kontribusi_prodi_nama'] ?? ''), '-')],
            ],
            'abstract' => [
                'title' => 'Abstrak Proposal',
                'html' => $detailPayload['review_summary']['abstrak'] ?? '',
                'empty_message' => 'Abstrak proposal belum tersedia.',
            ],
            'substansiSections' => $this->buildSubstansiSections($detailPayload),
            'journalInfo' => [
                'items' => $this->buildJournalItems($detailPayload),
                'links' => $this->buildJournalLinks($detailPayload),
            ],
            'teamSections' => $detailPayload['team_sections'] ?? [],
            'documentRows' => $detailPayload['document_rows'] ?? [],
            'assignmentPanel' => [
                'form_action' => site_url('admin/proposals/assign-reviewers/' . $proposal->uuid),
                'assigned_reviewers' => $assignedReviewers,
                'recommended_reviewers' => $candidateGroups['recommended_preview'],
                'manual_reviewers' => $candidateGroups['manual_reviewers'],
                'recommended_total' => $candidateGroups['recommended_total'],
                'recommended_hidden_count' => $candidateGroups['recommended_hidden_count'],
                'manual_reviewer_count' => $candidateGroups['manual_reviewer_count'],
                'has_candidates' => !empty($candidateGroups['recommended_preview']) || !empty($candidateGroups['manual_reviewers']),
                'empty_message' => 'Tidak ada reviewer aktif yang bisa ditugaskan saat ini.',
                'assignment_hint' => 'Pilih satu atau beberapa reviewer. Sistem mengurutkan kandidat yang bidang ilmunya paling mendekati proposal di bagian rekomendasi.',
                'manual_hint' => 'Gunakan pencarian manual jika reviewer yang dicari belum muncul pada daftar prioritas.',
                'remove_button_label' => 'Batalkan Tugas',
            ],
            'reviewerResultsPanel' => $reviewerResultsPanel,
            'decisionSummary' => $decisionSummary,
        ];
    }

    public function assignReviewers(string $proposalUuid, array $reviewerIds, int $assignedBy, ?string $assignmentNotes = null): void
    {
        $proposal = $this->getProposalByUuid($proposalUuid);
        if ($proposal === null) {
            throw new Exception('Proposal tidak ditemukan.');
        }

        $reviewerIds = array_values(array_unique(array_filter(array_map('intval', $reviewerIds))));
        if ($reviewerIds === []) {
            throw new Exception('Pilih minimal satu reviewer untuk ditugaskan.');
        }

        $availableReviewerIds = array_map(
            static fn(array $reviewer): int => (int) $reviewer['id'],
            $this->getReviewerCandidates()
        );

        if (array_diff($reviewerIds, $availableReviewerIds) !== []) {
            throw new Exception('Terdapat reviewer yang tidak valid atau sudah tidak aktif.');
        }

        $this->db->transStart();

        foreach ($reviewerIds as $reviewerId) {
            $existingAssignment = $this->assignmentModel->findActiveByProposalAndReviewer((int) $proposal->id, $reviewerId);
            if ($existingAssignment !== null) {
                continue;
            }

            $this->assignmentModel->insert([
                'uuid' => Uuid::uuid4()->toString(),
                'proposal_id' => (int) $proposal->id,
                'reviewer_user_id' => $reviewerId,
                'assigned_by' => $assignedBy > 0 ? $assignedBy : null,
                'assignment_notes' => $assignmentNotes ?: null,
                'status' => 'assigned',
                'recommendation' => 'pending',
            ]);
        }

        $this->proposalModel->update((int) $proposal->id, ['status' => 'assigned']);
        $this->db->transComplete();

        if (!$this->db->transStatus()) {
            throw new Exception('Gagal menyimpan assignment reviewer.');
        }
    }

    public function removeReviewerAssignment(string $proposalUuid, string $assignmentUuid): void
    {
        $proposal = $this->getProposalByUuid($proposalUuid);
        if ($proposal === null) {
            throw new Exception('Proposal tidak ditemukan.');
        }

        $assignment = $this->assignmentModel->where('uuid', $assignmentUuid)
            ->where('proposal_id', (int) $proposal->id)
            ->first();

        if (!$assignment) {
            throw new Exception('Assignment reviewer tidak ditemukan.');
        }

        $this->db->transStart();
        $this->assignmentModel->delete((int) $assignment->id);
        $remainingAssignments = $this->assignmentModel->countActiveByProposal((int) $proposal->id);
        $this->proposalModel->update((int) $proposal->id, [
            'status' => $remainingAssignments > 0 ? 'assigned' : 'submitted',
        ]);
        $this->db->transComplete();

        if (!$this->db->transStatus()) {
            throw new Exception('Gagal membatalkan assignment reviewer.');
        }
    }

    private function getAdminVisibleProposals(): array
    {
        return $this->db->table('proposal_pengajuan')
            ->select([
                'proposal_pengajuan.*',
                'COALESCE(NULLIF(users.nama_lengkap, ""), users.username) AS pengusul_nama',
                'users.email AS pengusul_email',
                'proposal_bidang_ilmu.nama AS proposal_bidang_ilmu_nama',
                'COUNT(proposal_reviewer_assignments.id) AS reviewer_count',
            ])
            ->join('users', 'users.id = proposal_pengajuan.user_id', 'left')
            ->join('proposal_bidang_ilmu', 'proposal_bidang_ilmu.id = proposal_pengajuan.bidang_ilmu_id', 'left')
            ->join('proposal_reviewer_assignments', 'proposal_reviewer_assignments.proposal_id = proposal_pengajuan.id AND proposal_reviewer_assignments.deleted_at IS NULL', 'left')
            ->where('proposal_pengajuan.deleted_at', null)
            ->whereIn('proposal_pengajuan.status', self::ADMIN_VISIBLE_STATUSES)
            ->groupBy('proposal_pengajuan.id')
            ->orderBy('proposal_pengajuan.updated_at', 'DESC')
            ->get()
            ->getResult();
    }

    private function getProposalByUuid(string $proposalUuid): ?object
    {
        $proposal = $this->db->table('proposal_pengajuan')
            ->select([
                'proposal_pengajuan.*',
                'COALESCE(NULLIF(users.nama_lengkap, ""), users.username) AS pengusul_nama',
                'users.email AS pengusul_email',
            ])
            ->join('users', 'users.id = proposal_pengajuan.user_id', 'left')
            ->where('proposal_pengajuan.deleted_at', null)
            ->where('proposal_pengajuan.uuid', $proposalUuid)
            ->whereIn('proposal_pengajuan.status', self::ADMIN_VISIBLE_STATUSES)
            ->get()
            ->getRow();

        return is_object($proposal) ? $proposal : null;
    }

    private function getAssignedReviewerRows(int $proposalId, string $proposalUuid): array
    {
        $builder = $this->db->table('proposal_reviewer_assignments')
            ->select([
                'proposal_reviewer_assignments.*',
                'COALESCE(NULLIF(users.nama_lengkap, ""), users.username) AS reviewer_name',
                'users.email AS reviewer_email',
                'bidang_ilmu.nama AS reviewer_bidang_ilmu_nama',
            ])
            ->join('users', 'users.id = proposal_reviewer_assignments.reviewer_user_id', 'left')
            ->join('user_profiles', 'user_profiles.user_id = users.id AND user_profiles.deleted_at IS NULL', 'left')
            ->join('bidang_ilmu', 'bidang_ilmu.id = user_profiles.bidang_ilmu_id', 'left')
            ->where('proposal_reviewer_assignments.deleted_at', null)
            ->where('proposal_reviewer_assignments.proposal_id', $proposalId)
            ->orderBy('proposal_reviewer_assignments.created_at', 'ASC');

        if ($this->hasReviewScoreColumn()) {
            $builder->select('proposal_reviewer_assignments.review_score AS review_score');
        }

        $assignments = $builder->get()->getResult();

        return array_map(function (object $assignment) use ($proposalUuid): array {
            return [
                'uuid' => $assignment->uuid,
                'reviewer_name' => $assignment->reviewer_name,
                'reviewer_email' => $assignment->reviewer_email ?: '-',
                'reviewer_bidang_ilmu' => $this->valueOrFallback((string) ($assignment->reviewer_bidang_ilmu_nama ?? ''), 'Belum mengisi bidang ilmu'),
                'status_label' => $this->mapAssignmentStatusLabel((string) ($assignment->status ?? 'assigned')),
                'status_badge_class' => $this->mapAssignmentStatusBadgeClass((string) ($assignment->status ?? 'assigned')),
                'recommendation_label' => $this->mapRecommendationLabel((string) ($assignment->recommendation ?? 'pending')),
                'recommendation_badge_class' => $this->mapRecommendationBadgeClass((string) ($assignment->recommendation ?? 'pending')),
                'review_score_display' => $this->formatReviewerScore($assignment),
                'reviewed_at_label' => $this->formatDateTime($assignment->reviewed_at ?? null),
                'assignment_notes' => $this->valueOrFallback((string) ($assignment->assignment_notes ?? ''), '-'),
                'review_notes' => $this->valueOrFallback((string) ($assignment->review_notes ?? ''), 'Belum ada catatan reviewer.'),
                'remove_url' => site_url('admin/proposals/remove-reviewer/' . $proposalUuid . '/' . $assignment->uuid),
            ];
        }, $assignments);
    }

    private function buildReviewerResultsPanel(object $proposal, array $assignedReviewers): array
    {
        $reviewedCount = count(array_filter(
            $assignedReviewers,
            static fn(array $reviewer): bool => ($reviewer['status_label'] ?? '') === 'Sudah Direview'
        ));
        $allReviewed = !empty($assignedReviewers) && $reviewedCount === count($assignedReviewers);

        return [
            'items' => $assignedReviewers,
            'has_items' => !empty($assignedReviewers),
            'reviewed_count' => $reviewedCount,
            'all_reviewed' => $allReviewed,
            'presentasi_url' => site_url('admin/proposals/show/' . $proposal->uuid . '#proposalReviewerPresentasiTab'),
            'presentasi_label' => 'Buka Penilaian Presentasi',
            'presentasi_hint' => 'Tombol ini dibuka setelah seluruh reviewer menyelesaikan penilaian usulan.',
            'empty_message' => 'Belum ada reviewer yang ditugaskan untuk proposal ini.',
            'completion_message' => $allReviewed
                ? 'Semua reviewer sudah menyelesaikan penilaian usulan. Tahap presentasi bisa dibuka.'
                : 'Tahap presentasi belum dibuka karena masih ada reviewer yang belum menyelesaikan usulan.',
            'proposal_status' => (string) ($proposal->status ?? 'submitted'),
        ];
    }

    private function buildReviewerCandidateGroups(object $proposal): array
    {
        $proposalDetail = $this->detailService->buildDetailPayload($proposal);
        $proposalBidangIlmu = (string) ($proposalDetail['bidang_ilmu_nama'] ?? '');
        $candidates = [];

        foreach ($this->getReviewerCandidates() as $reviewer) {
            if ($this->assignmentModel->findActiveByProposalAndReviewer((int) $proposal->id, (int) $reviewer['id']) !== null) {
                continue;
            }

            $score = $this->calculateReviewerScore($proposalBidangIlmu, (string) ($reviewer['reviewer_bidang_ilmu'] ?? ''));
            $candidates[] = [
                'id' => (int) $reviewer['id'],
                'name' => $reviewer['display_name'],
                'email' => $reviewer['email'] ?: '-',
                'bidang_ilmu' => $this->valueOrFallback((string) ($reviewer['reviewer_bidang_ilmu'] ?? ''), 'Belum mengisi bidang ilmu'),
                'score' => $score,
                'fit_label' => $score > 0 ? 'Direkomendasikan' : 'Pilih Manual',
                'fit_badge_class' => $score > 0 ? 'text-bg-success' : 'text-bg-light border',
            ];
        }

        usort($candidates, function (array $left, array $right): int {
            if ($left['score'] === $right['score']) {
                return strcmp($left['name'], $right['name']);
            }

            return $right['score'] <=> $left['score'];
        });

        $recommendedCandidates = array_values(array_filter(
            $candidates,
            static fn(array $candidate): bool => $candidate['score'] > 0
        ));
        $otherCandidates = array_values(array_filter(
            $candidates,
            static fn(array $candidate): bool => $candidate['score'] <= 0
        ));

        $recommendedPreview = array_slice($recommendedCandidates, 0, self::RECOMMENDED_REVIEWER_PREVIEW_LIMIT);
        $recommendedOverflow = array_slice($recommendedCandidates, self::RECOMMENDED_REVIEWER_PREVIEW_LIMIT);
        $manualReviewers = array_values(array_merge($recommendedOverflow, $otherCandidates));

        return [
            'recommended_preview' => $recommendedPreview,
            'recommended_total' => count($recommendedCandidates),
            'recommended_hidden_count' => max(count($recommendedCandidates) - count($recommendedPreview), 0),
            'manual_reviewers' => $manualReviewers,
            'manual_reviewer_count' => count($manualReviewers),
        ];
    }

    private function getReviewerCandidates(): array
    {
        $rows = $this->db->table('users')
            ->select([
                'users.id',
                'COALESCE(NULLIF(users.nama_lengkap, ""), users.username) AS display_name',
                'users.email',
                'bidang_ilmu.nama AS reviewer_bidang_ilmu',
            ])
            ->join('user_roles', 'user_roles.user_id = users.id', 'inner')
            ->join('roles', 'roles.id = user_roles.role_id AND roles.name = "reviewer"', 'inner')
            ->join('user_profiles', 'user_profiles.user_id = users.id AND user_profiles.deleted_at IS NULL', 'left')
            ->join('bidang_ilmu', 'bidang_ilmu.id = user_profiles.bidang_ilmu_id', 'left')
            ->where('users.deleted_at', null)
            ->where('users.aktif', 1)
            ->groupBy('users.id')
            ->orderBy('display_name', 'ASC')
            ->get()
            ->getResultArray();

        return array_map(static function (array $row): array {
            return [
                'id' => (int) $row['id'],
                'display_name' => $row['display_name'],
                'email' => $row['email'],
                'reviewer_bidang_ilmu' => $row['reviewer_bidang_ilmu'] ?? '',
            ];
        }, $rows);
    }

    private function buildSubstansiSections(array $detailPayload): array
    {
        $sections = $detailPayload['review']['substansi_bagian'] ?? [];

        return array_values(array_map(static function (array $section, int $index): array {
            return [
                'number' => $index + 1,
                'title' => trim((string) ($section['judul_bagian'] ?? '')) !== '' ? $section['judul_bagian'] : 'Bagian ' . ($index + 1),
                'content_html' => $section['isi_bagian'] ?? '',
            ];
        }, $sections, array_keys($sections)));
    }

    private function buildJournalItems(array $detailPayload): array
    {
        $summary = $detailPayload['summary'] ?? [];

        return [
            ['label' => 'ISSN', 'value' => $this->valueOrFallback((string) ($summary['issn'] ?? ''), '-')],
            ['label' => 'Nama Jurnal', 'value' => $this->valueOrFallback((string) ($summary['nama_jurnal'] ?? ''), '-')],
            ['label' => 'Total Pengajuan Dana', 'value' => $this->valueOrFallback((string) ($summary['total_pengajuan_dana'] ?? ''), '-')],
        ];
    }

    private function buildJournalLinks(array $detailPayload): array
    {
        $summary = $detailPayload['summary'] ?? [];

        return array_values(array_filter([
            $this->buildLinkItem('Website Jurnal', (string) ($summary['url_website'] ?? '')),
            $this->buildLinkItem('Scopus / WoS', (string) ($summary['url_scopus_wos'] ?? '')),
            $this->buildLinkItem('Surat Rekomendasi', (string) ($summary['url_surat_rekomendasi'] ?? '')),
        ]));
    }

    private function buildLinkItem(string $label, string $url): ?array
    {
        $url = trim($url);
        if ($url === '') {
            return null;
        }

        $normalizedUrl = preg_match('~^[a-z][a-z0-9+.-]*:~i', $url) ? $url : 'https://' . ltrim($url, '/');
        $host = parse_url($normalizedUrl, PHP_URL_HOST) ?: $normalizedUrl;
        $host = preg_replace('/^www\./', '', (string) $host);

        return [
            'label' => $label,
            'url' => $normalizedUrl,
            'value' => $host,
        ];
    }

    private function buildDecisionSummary(array $assignedReviewers): array
    {
        $reviewedCount = count(array_filter(
            $assignedReviewers,
            static fn(array $reviewer): bool => ($reviewer['status_label'] ?? '') === 'Sudah Direview'
        ));
        $pendingCount = max(count($assignedReviewers) - $reviewedCount, 0);

        return [
            'reviewed_count' => $reviewedCount,
            'pending_count' => $pendingCount,
            'cards' => [
                ['label' => 'Reviewer Aktif', 'value' => (string) count($assignedReviewers), 'tone_class' => 'text-primary'],
                ['label' => 'Rekomendasi Masuk', 'value' => (string) $reviewedCount, 'tone_class' => 'text-success'],
                ['label' => 'Masih Menunggu', 'value' => (string) $pendingCount, 'tone_class' => 'text-warning'],
            ],
            'note' => count($assignedReviewers) === 0
                ? 'Belum ada reviewer yang ditugaskan. Admin belum bisa mengambil keputusan akhir sampai ada reviewer aktif.'
                : ($pendingCount > 0
                    ? 'Sebagian reviewer masih belum mengirim rekomendasi. Keputusan akhir admin sebaiknya menunggu seluruh masukan masuk.'
                    : 'Semua reviewer aktif sudah mengirim hasil review. Modul keputusan akhir admin siap dilanjutkan pada iterasi berikutnya.'),
        ];
    }

    private function calculateReviewerScore(string $proposalBidangIlmu, string $reviewerBidangIlmu): int
    {
        $proposalTokens = $this->normalizeTextTokens($proposalBidangIlmu);
        $reviewerTokens = $this->normalizeTextTokens($reviewerBidangIlmu);

        if ($proposalTokens === [] || $reviewerTokens === []) {
            return 0;
        }

        $proposalText = implode(' ', $proposalTokens);
        $reviewerText = implode(' ', $reviewerTokens);

        if ($proposalText === $reviewerText) {
            return 100;
        }

        if (str_contains($proposalText, $reviewerText) || str_contains($reviewerText, $proposalText)) {
            return 80;
        }

        $intersections = array_intersect($proposalTokens, $reviewerTokens);
        if ($intersections === []) {
            return 0;
        }

        return 50 + (count($intersections) * 10);
    }

    private function normalizeTextTokens(string $value): array
    {
        $normalized = strtolower(trim(preg_replace('/\s+/', ' ', $value)));
        if ($normalized === '' || $normalized === '-') {
            return [];
        }

        $parts = preg_split('/[^a-z0-9]+/i', $normalized) ?: [];

        return array_values(array_filter($parts, static fn(string $token): bool => $token !== ''));
    }

    private function formatDateTime(?string $dateTime): string
    {
        if (empty($dateTime)) {
            return '-';
        }

        return date_format(date_create($dateTime), 'd M Y H:i');
    }

    private function formatReviewerScore(object $assignment): string
    {
        if (property_exists($assignment, 'review_score') && $assignment->review_score !== null) {
            return $this->formatScore((float) $assignment->review_score);
        }

        $score = $this->extractReviewScoreFromNotes((string) ($assignment->review_notes ?? ''));

        return $score === null ? '-' : $this->formatScore($score);
    }

    private function extractReviewScoreFromNotes(string $notes): ?float
    {
        if (preg_match('/^Nilai:\s*([0-9]+(?:[\.,][0-9]+)?)/m', $notes, $matches) !== 1) {
            return null;
        }

        return (float) str_replace(',', '.', $matches[1]);
    }

    private function hasReviewScoreColumn(): bool
    {
        static $hasColumn = null;

        if ($hasColumn !== null) {
            return $hasColumn;
        }

        $hasColumn = $this->db->fieldExists('review_score', 'proposal_reviewer_assignments');

        return $hasColumn;
    }

    private function formatScore(?float $score): string
    {
        if ($score === null) {
            return '-';
        }

        return number_format($score, 2, ',', '.');
    }

    private function mapProposalStatusLabel(string $status): string
    {
        return match ($status) {
            'submitted' => 'Menunggu Penugasan',
            'assigned' => 'Sedang Direview',
            'reviewed' => 'Siap Diputuskan',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default => ucfirst($status),
        };
    }

    private function mapProposalStatusBadgeClass(string $status): string
    {
        return match ($status) {
            'submitted' => 'text-bg-warning',
            'assigned' => 'text-bg-primary',
            'reviewed', 'approved' => 'text-bg-success',
            'rejected' => 'text-bg-danger',
            default => 'text-bg-light border',
        };
    }

    private function mapMetricToneClass(string $status): string
    {
        return match ($status) {
            'submitted' => 'text-warning',
            'assigned' => 'text-primary',
            'reviewed', 'approved' => 'text-success',
            'rejected' => 'text-danger',
            default => 'text-dark',
        };
    }

    private function mapAssignmentStatusLabel(string $status): string
    {
        return match ($status) {
            'assigned' => 'Menunggu Review',
            'reviewed' => 'Sudah Direview',
            'declined' => 'Ditolak Reviewer',
            default => ucfirst($status),
        };
    }

    private function mapAssignmentStatusBadgeClass(string $status): string
    {
        return match ($status) {
            'assigned' => 'text-bg-warning',
            'reviewed' => 'text-bg-success',
            'declined' => 'text-bg-danger',
            default => 'text-bg-light border',
        };
    }

    private function mapRecommendationLabel(string $recommendation): string
    {
        return match ($recommendation) {
            'recommended' => 'Layak Dilanjutkan',
            'revision' => 'Perlu Revisi',
            'rejected' => 'Tidak Direkomendasikan',
            default => 'Belum Ada Rekomendasi',
        };
    }

    private function mapRecommendationBadgeClass(string $recommendation): string
    {
        return match ($recommendation) {
            'recommended' => 'text-bg-success',
            'revision' => 'text-bg-warning',
            'rejected' => 'text-bg-danger',
            default => 'text-bg-light border',
        };
    }

    private function valueOrFallback(string $value, string $fallback): string
    {
        return trim($value) !== '' ? $value : $fallback;
    }
}
