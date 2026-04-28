<?php

namespace App\Services\Reviewer;

use App\Models\Proposal\ProposalPengajuan;
use App\Models\Proposal\ProposalReviewerAssignment;
use App\Services\Proposal\ProposalDetailService;
use Config\Database;

class ReviewerAssessmentService
{
    private const SESSION_KEY = 'reviewer_assessments';

    private const TAB_DEFINITIONS = [
        'proposal' => [
            'label' => 'Penilaian Proposal',
            'icon' => 'bi bi-journal-text',
        ],
        'presentasi' => [
            'label' => 'Penilaian Presentasi',
            'icon' => 'bi bi-easel2',
        ],
        'luaran' => [
            'label' => 'Penilaian Luaran',
            'icon' => 'bi bi-display',
        ],
        'portofolio' => [
            'label' => 'Portofolio',
            'icon' => 'bi bi-briefcase',
        ],
    ];

    private const STATUS_OPTIONS = [
        '' => 'Semua Status',
        'pending' => 'Belum Dinilai',
        'draft' => 'Draft',
        'completed' => 'Sudah Dinilai',
    ];

    private const DETAIL_TAB_DEFINITIONS = [
        'review' => [
            'label' => 'Review Isian',
            'icon' => 'bi bi-card-text',
        ],
        'scoring' => [
            'label' => 'Penilaian Usulan',
            'icon' => 'bi bi-table',
        ],
    ];

    private const PROPOSAL_SCORE_ASPECTS = [
        'latar_belakang' => [
            'number' => 1,
            'label' => 'Latar Belakang Masalah',
            'weight' => 10,
        ],
        'rumusan_masalah' => [
            'number' => 2,
            'label' => 'Rumusan Masalah dan Tujuan',
            'weight' => 10,
        ],
        'originalitas' => [
            'number' => 3,
            'label' => 'Originalitas, Urgensi, dan Manfaat',
            'weight' => 15,
        ],
        'kontribusi_akademik' => [
            'number' => 4,
            'label' => 'Kontribusi Akademik',
            'weight' => 15,
        ],
        'ketepatan_metode' => [
            'number' => 5,
            'label' => 'Ketepatan Penggunaan Metode, & Teori',
            'weight' => 10,
        ],
        'penggunaan_referensi' => [
            'number' => 6,
            'label' => 'Penggunaan Referensi',
            'weight' => 10,
        ],
        'kajian_riset' => [
            'number' => 7,
            'label' => 'Kajian Hasil Riset Sebelumnya yang Berkaitan',
            'weight' => 10,
        ],
        'keutuhan_gagasan' => [
            'number' => 8,
            'label' => 'Keutuhan Gagasan',
            'weight' => 10,
        ],
        'biaya_waktu' => [
            'number' => 9,
            'label' => 'Alokasi Biaya dan Waktu',
            'weight' => 10,
        ],
    ];

    private const PRESENTATION_SCORE_ASPECTS = [
        'pembukaan' => [
            'number' => 1,
            'label' => 'Pembukaan dan Alur Penyampaian',
            'weight' => 20,
        ],
        'penguasaan_materi' => [
            'number' => 2,
            'label' => 'Penguasaan Materi',
            'weight' => 20,
        ],
        'kualitas_media' => [
            'number' => 3,
            'label' => 'Kualitas Slide dan Media Presentasi',
            'weight' => 20,
        ],
        'menjawab_tanya' => [
            'number' => 4,
            'label' => 'Kemampuan Menjawab Pertanyaan',
            'weight' => 20,
        ],
        'manajemen_waktu' => [
            'number' => 5,
            'label' => 'Manajemen Waktu Presentasi',
            'weight' => 20,
        ],
    ];

    private ProposalReviewerAssignment $assignmentModel;

    private ProposalPengajuan $proposalModel;

    private ProposalDetailService $proposalDetailService;

    private $db;

    private ?bool $hasReviewScoreColumn = null;

    public function __construct()
    {
        $this->assignmentModel = new ProposalReviewerAssignment();
        $this->proposalModel = new ProposalPengajuan();
        $this->proposalDetailService = new ProposalDetailService();
        $this->db = Database::connect();
    }

    public function buildQueuePayload(?string $activeTab = null, array $statusFilters = []): array
    {
        $activeTab = $this->normalizeTab($activeTab);
        $tabs = $this->buildTabs($activeTab, $statusFilters);
        $allRows = $this->collectAllRows();

        return [
            'title' => 'Penilaian Reviewer',
            'currentModule' => 'Reviewer',
            'hero' => [
                'title' => 'Penilaian Reviewer',
                'subtitle' => 'Kelola penilaian reviewer per kategori melalui tab yang terpisah agar alur kerja tetap fokus, konsisten, dan mudah dikembangkan bertahap.',
                'badges' => [
                    ['label' => 'Reviewer Workspace', 'class' => 'text-bg-light border'],
                    ['label' => '4 Kategori Penilaian', 'class' => 'text-bg-primary'],
                ],
            ],
            'metrics' => [
                [
                    'label' => 'Total Antrian',
                    'value' => (string) count($allRows),
                    'tone_class' => 'text-dark',
                    'caption' => 'Total item penilaian reviewer pada seluruh kategori MVP.',
                ],
                [
                    'label' => 'Belum Dinilai',
                    'value' => (string) count(array_filter($allRows, static fn(array $row): bool => $row['review_status'] === 'pending')),
                    'tone_class' => 'text-warning',
                    'caption' => 'Item yang belum memiliki progress penilaian reviewer.',
                ],
                [
                    'label' => 'Draft',
                    'value' => (string) count(array_filter($allRows, static fn(array $row): bool => $row['review_status'] === 'draft')),
                    'tone_class' => 'text-primary',
                    'caption' => 'Item yang sudah dibuka dan masih berada pada tahap draft.',
                ],
                [
                    'label' => 'Sudah Dinilai',
                    'value' => (string) count(array_filter($allRows, static fn(array $row): bool => $row['review_status'] === 'completed')),
                    'tone_class' => 'text-success',
                    'caption' => 'Item yang telah selesai dinilai pada tahap MVP.',
                ],
            ],
            'tabs' => $tabs,
        ];
    }

    public function buildHistoryPayload(): array
    {
        $rows = [];

        foreach (array_keys(self::TAB_DEFINITIONS) as $categoryKey) {
            foreach ($this->getRowsForCategory($categoryKey) as $row) {
                if ($row['review_status'] === 'pending') {
                    continue;
                }

                $rows[] = [
                    'category_label' => self::TAB_DEFINITIONS[$categoryKey]['label'],
                    'title' => $row['title'],
                    'cluster' => $row['cluster'],
                    'score_display' => $this->formatScore($row['score_value'] ?? null),
                    'review_status_label' => $this->mapStatusLabel((string) ($row['review_status'] ?? 'pending')),
                    'review_status_badge_class' => $this->mapStatusBadgeClass((string) ($row['review_status'] ?? 'pending')),
                    'action_label' => 'Lihat',
                    'action_url' => site_url('reviewer/queue/' . $categoryKey . '/' . $row['item_key']),
                ];
            }
        }

        return [
            'title' => 'Riwayat Review',
            'currentModule' => 'Reviewer',
            'hero' => [
                'title' => 'Riwayat Review',
                'subtitle' => 'Riwayat penilaian reviewer ditampilkan terpisah agar hasil review yang sudah pernah dikerjakan dapat ditelusuri kembali dengan cepat.',
                'badges' => [
                    ['label' => 'Reviewer Workspace', 'class' => 'text-bg-light border'],
                    ['label' => 'Riwayat Penilaian', 'class' => 'text-bg-success'],
                ],
            ],
            'metrics' => [
                [
                    'label' => 'Total Riwayat',
                    'value' => (string) count($rows),
                    'tone_class' => 'text-dark',
                    'caption' => 'Seluruh item yang sudah pernah memiliki progress review.',
                ],
                [
                    'label' => 'Draft',
                    'value' => (string) count(array_filter($rows, static fn(array $row): bool => $row['review_status_label'] === 'Draft')),
                    'tone_class' => 'text-primary',
                    'caption' => 'Riwayat yang masih berada pada status draft penilaian.',
                ],
                [
                    'label' => 'Sudah Dinilai',
                    'value' => (string) count(array_filter($rows, static fn(array $row): bool => $row['review_status_label'] === 'Sudah Dinilai')),
                    'tone_class' => 'text-success',
                    'caption' => 'Riwayat dengan status penilaian selesai.',
                ],
            ],
            'table' => [
                'table_id' => 'dt-reviewer-history',
                'skeleton_id' => 'sk-reviewer-history',
                'real_wrap_id' => 'rw-reviewer-history',
                'rows' => $rows,
            ],
        ];
    }

    public function getAssessmentDetail(string $category, string $itemKey, ?string $activeDetailTab = null): ?array
    {
        $category = $this->normalizeTab($category);
        $activeDetailTab = $this->normalizeDetailTab($activeDetailTab);

        foreach ($this->getRowsForCategory($category) as $row) {
            if ($row['item_key'] !== $itemKey) {
                continue;
            }

            if ($category === 'proposal') {
                return $this->buildProposalDetailPayload($row, $activeDetailTab);
            }

            if ($category === 'presentasi') {
                return $this->buildPresentationDetailPayload($row, $activeDetailTab);
            }

            return $this->buildGenericDetailPayload($category, $row);
        }

        return null;
    }

    public function saveProposalAssessment(string $itemKey, array $input): bool
    {
        $proposalRow = $this->findAssessmentRow('proposal', $itemKey);

        if ($proposalRow === null) {
            return false;
        }

        $proposalId = (int) ($proposalRow['proposal_id'] ?? 0);
        $reviewerId = $this->getCurrentUserId();

        if ($proposalId <= 0 || $reviewerId <= 0) {
            return false;
        }

        $assignment = $this->assignmentModel->findActiveByProposalAndReviewer($proposalId, $reviewerId);

        if ($assignment === null) {
            return false;
        }

        $normalizedScores = $this->normalizeProposalScores($input['scores'] ?? []);
        $comments = $this->normalizeSectionComments($input['comments'] ?? []);
        $generalComment = $this->cleanHtml((string) ($input['general_comment'] ?? ''));
        $validatorNote = trim((string) ($input['validator_note'] ?? ''));

        $totals = $this->calculateProposalScoreTotals($normalizedScores);
        $formattedScore = $this->formatScore($totals['normalized_total']);
        $reviewNotes = $this->composeReviewNotesSummary($proposalRow, $comments, $generalComment, $validatorNote, $formattedScore);
        $recommendation = $this->mapRecommendationByScore($totals['normalized_total']);

        $state = [
            'scores' => $normalizedScores,
            'comments' => $comments,
            'general_comment' => $generalComment,
            'validator_note' => $validatorNote,
            'total_score_raw' => $totals['raw_total'],
            'score_value' => $totals['normalized_total'],
            'review_status' => 'completed',
        ];

        $updateData = [
            'review_notes' => $reviewNotes,
            'recommendation' => $recommendation,
            'status' => 'reviewed',
            'reviewed_at' => date('Y-m-d H:i:s'),
        ];

        if ($this->hasReviewScoreColumn()) {
            $updateData['review_score'] = $totals['normalized_total'];
        }

        $this->assignmentModel->update((int) $assignment->id, $updateData);

        $this->syncProposalReviewStatus($proposalId);

        $storage = $this->getSessionStorage();
        $storage['proposal'][$itemKey] = $state;

        session()->set(self::SESSION_KEY, $storage);

        return true;
    }

    public function savePresentationAssessment(string $itemKey, array $input): bool
    {
        $presentationRow = $this->findAssessmentRow('presentasi', $itemKey);

        if ($presentationRow === null) {
            return false;
        }

        $reviewerId = $this->getCurrentUserId();

        if ($reviewerId <= 0) {
            return false;
        }

        $normalizedScores = $this->normalizePresentationScores($input['scores'] ?? []);
        $comments = $this->normalizeSectionComments($input['comments'] ?? []);
        $generalComment = $this->cleanHtml((string) ($input['general_comment'] ?? ''));
        $validatorNote = trim((string) ($input['validator_note'] ?? ''));

        $totals = $this->calculatePresentationScoreTotals($normalizedScores);
        $formattedScore = $this->formatScore($totals['normalized_total']);
        $reviewNotes = $this->composePresentationNotesSummary($presentationRow, $comments, $generalComment, $validatorNote, $formattedScore);

        $state = [
            'scores' => $normalizedScores,
            'comments' => $comments,
            'general_comment' => $generalComment,
            'validator_note' => $validatorNote,
            'total_score_raw' => $totals['raw_total'],
            'score_value' => $totals['normalized_total'],
            'review_status' => 'completed',
        ];

        $storage = $this->getSessionStorage();
        $storage['presentasi'][$itemKey] = $state;
        session()->set(self::SESSION_KEY, $storage);

        return true;
    }

    private function buildTabs(string $activeTab, array $statusFilters = []): array
    {
        $tabs = [];

        foreach (self::TAB_DEFINITIONS as $key => $definition) {
            $isActive = $key === $activeTab;
            $selectedStatus = $this->normalizeStatusFilter($statusFilters[$key] ?? null);
            $tableId = 'dt-reviewer-' . $key;
            $skeletonId = 'sk-reviewer-' . $key;
            $realWrapId = 'rw-reviewer-' . $key;
            $rows = $this->filterRowsByStatus($this->getRowsForCategory($key), $selectedStatus);

            $tabs[] = [
                'key' => $key,
                'label' => $definition['label'],
                'icon' => $definition['icon'],
                'is_active' => $isActive,
                'button_id' => 'tab-reviewer-' . $key,
                'button_class' => $isActive ? 'nav-link active' : 'nav-link',
                'pane_id' => 'pane-reviewer-' . $key,
                'pane_class' => $isActive ? 'tab-pane fade show active' : 'tab-pane fade',
                'table_id' => $tableId,
                'skeleton_id' => $skeletonId,
                'real_wrap_id' => $realWrapId,
                'filter' => [
                    'action_url' => site_url('reviewer/queue'),
                    'field_name' => $key . '_status',
                    'selected_status' => $selectedStatus,
                    'options' => $this->buildStatusOptions(),
                ],
                'rows' => array_map(
                    fn(array $row): array => $this->buildTableRow($key, $row),
                    $rows
                ),
            ];
        }

        return $tabs;
    }

    private function buildTableRow(string $category, array $row): array
    {
        return [
            'title' => $row['title'],
            'cluster' => $row['cluster'],
            'score_display' => $this->formatScore($row['score_value'] ?? null),
            'review_status_label' => $this->mapStatusLabel((string) ($row['review_status'] ?? 'pending')),
            'review_status_badge_class' => $this->mapStatusBadgeClass((string) ($row['review_status'] ?? 'pending')),
            'action_label' => 'Lihat',
            'action_url' => site_url('reviewer/queue/' . $category . '/' . $row['item_key']),
        ];
    }

    private function normalizeTab(?string $tab): string
    {
        $tab = strtolower(trim((string) $tab));

        if (!array_key_exists($tab, self::TAB_DEFINITIONS)) {
            return 'proposal';
        }

        return $tab;
    }

    private function getRowsForCategory(string $category): array
    {
        $rows = match ($category) {
            'proposal' => $this->getProposalRows(),
            'presentasi' => $this->getPresentationRows(),
            'luaran' => [
                [
                    'item_key' => 'luaran-jurnal-scopus',
                    'title' => 'Luaran Jurnal Internasional Terindeks Scopus',
                    'cluster' => 'Publikasi Ilmiah',
                    'score_value' => null,
                    'review_status' => 'pending',
                ],
                [
                    'item_key' => 'luaran-buku-ajar',
                    'title' => 'Luaran Buku Ajar Metodologi Penelitian Terapan',
                    'cluster' => 'Produk Akademik',
                    'score_value' => 88.00,
                    'review_status' => 'completed',
                ],
            ],
            'portofolio' => [
                [
                    'item_key' => 'portofolio-riset-komunitas',
                    'title' => 'Portofolio Riset Komunitas dan Dampak Sosial',
                    'cluster' => 'Rekam Jejak Peneliti',
                    'score_value' => null,
                    'review_status' => 'pending',
                ],
                [
                    'item_key' => 'portofolio-hki-terapan',
                    'title' => 'Portofolio HKI dan Inovasi Produk Terapan',
                    'cluster' => 'Kinerja Inovasi',
                    'score_value' => 80.75,
                    'review_status' => 'draft',
                ],
            ],
            default => [],
        };

        return array_map(
            fn(array $row): array => $this->applyStoredAssessmentState($category, $row),
            $rows
        );
    }

    private function buildGenericDetailPayload(string $category, array $row): array
    {
        return [
            'title' => self::TAB_DEFINITIONS[$category]['label'],
            'currentModule' => 'Reviewer',
            'hero' => [
                'title' => self::TAB_DEFINITIONS[$category]['label'],
                'subtitle' => 'Halaman detail ini disiapkan sebagai titik masuk MVP untuk setiap kategori penilaian reviewer.',
                'badges' => [
                    ['label' => 'Reviewer Workspace', 'class' => 'text-bg-light border'],
                    ['label' => self::TAB_DEFINITIONS[$category]['label'], 'class' => 'text-bg-success'],
                ],
            ],
            'detail' => [
                'category_label' => self::TAB_DEFINITIONS[$category]['label'],
                'title' => $row['title'],
                'cluster' => $row['cluster'],
                'score_display' => $this->formatScore($row['score_value'] ?? null),
                'review_status_label' => $this->mapStatusLabel((string) ($row['review_status'] ?? 'pending')),
                'review_status_badge_class' => $this->mapStatusBadgeClass((string) ($row['review_status'] ?? 'pending')),
                'back_url' => site_url('reviewer/queue?tab=' . $category),
                'notes' => [
                    'Panel penilaian rinci untuk kategori ini akan diimplementasikan bertahap pada iterasi berikutnya.',
                    'Struktur route dan halaman detail sudah disiapkan agar penambahan form penilaian tidak mengubah arsitektur dasar reviewer.',
                ],
            ],
            'page_type' => 'generic',
        ];
    }

    private function buildProposalDetailPayload(array $row, string $activeDetailTab): array
    {
        $assessment = $this->mergeProposalAssessmentState($row);
        $summary = $this->buildProposalSummaryItems($row, $assessment);
        $reviewSections = $this->buildProposalReviewSections($row, $assessment);
        $totals = $this->calculateProposalScoreTotals($assessment['scores']);
        $scoring = $this->buildProposalScoringPayload($row, $assessment, $totals);
        $detailTabs = $this->buildDetailTabs($activeDetailTab);

        return [
            'title' => self::TAB_DEFINITIONS['proposal']['label'],
            'currentModule' => 'Reviewer',
            'page_type' => 'proposal',
            'hero' => [
                'title' => self::TAB_DEFINITIONS['proposal']['label'],
                'subtitle' => 'Reviewer dapat membaca substansi usulan, memberi komentar per bagian, lalu mengisi penilaian proposal dengan skala 1 sampai 5 pada setiap aspek.',
                'badges' => [
                    ['label' => 'Reviewer Workspace', 'class' => 'text-bg-light border'],
                    ['label' => 'Penilaian Proposal', 'class' => 'text-bg-danger'],
                ],
            ],
            'detail' => [
                'category_label' => self::TAB_DEFINITIONS['proposal']['label'],
                'title' => $row['title'],
                'cluster' => $row['cluster'],
                'score_display' => $this->formatScore($assessment['score_value']),
                'review_status_label' => $this->mapStatusLabel((string) ($assessment['review_status'] ?? 'pending')),
                'review_status_badge_class' => $this->mapStatusBadgeClass((string) ($assessment['review_status'] ?? 'pending')),
                'back_url' => site_url('reviewer/queue?tab=proposal'),
            ],
            'proposal' => [
                'summary_card_title' => 'Ringkasan Usulan',
                'summary_items' => $summary,
                'detail_tabs' => $detailTabs,
                'review' => [
                    'card_title' => 'Review Isian Substansi',
                    'sections' => $reviewSections,
                ],
                'scoring' => $scoring,
                'form' => [
                    'action_url' => site_url('reviewer/queue/proposal/' . $row['item_key'] . '/save'),
                    'redirect_tab' => $activeDetailTab,
                    'submit_label' => 'Simpan Penilaian Proposal',
                ],
            ],
        ];
    }

    private function buildPresentationDetailPayload(array $row, string $activeDetailTab): array
    {
        $proposal = $this->loadProposalById((int) ($row['proposal_id'] ?? 0));
        if ($proposal === null) {
            return $this->buildGenericDetailPayload('presentasi', $row);
        }

        $detailPayload = $this->proposalDetailService->buildReviewPayload($proposal);
        $assessment = $this->mergePresentationAssessmentState($row);
        $totals = $this->calculatePresentationScoreTotals($assessment['scores']);

        return [
            'title' => 'Penilaian Presentasi',
            'currentModule' => 'Reviewer',
            'page_type' => 'presentasi',
            'hero' => [
                'title' => 'Penilaian Presentasi',
                'subtitle' => 'Gunakan proposal yang sama sebagai materi presentasi, lalu isi form penilaian presentasi dengan aspek yang berbeda dari penilaian proposal.',
                'badges' => [
                    ['label' => 'Reviewer Workspace', 'class' => 'text-bg-light border'],
                    ['label' => 'Tahap Presentasi', 'class' => 'text-bg-success'],
                ],
            ],
            'detail' => [
                'category_label' => 'Penilaian Presentasi',
                'title' => $row['title'],
                'cluster' => $row['cluster'],
                'score_display' => $this->formatScore($assessment['score_value']),
                'review_status_label' => $this->mapStatusLabel((string) ($assessment['review_status'] ?? 'pending')),
                'review_status_badge_class' => $this->mapStatusBadgeClass((string) ($assessment['review_status'] ?? 'pending')),
                'back_url' => site_url('reviewer/queue?tab=presentasi'),
            ],
            'proposal' => [
                'summary_card_title' => 'Ringkasan Proposal untuk Presentasi',
                'summary_items' => $this->buildPresentationSummaryItems($detailPayload, $assessment),
                'proposal_score_label' => 'Skor Review Proposal',
                'proposal_score_value' => $this->formatScore($row['proposal_review_score'] ?? null),
                'proposal_notes_label' => 'Catatan Review Proposal',
                'proposal_notes_value' => trim((string) ($row['proposal_review_notes'] ?? '')) !== ''
                    ? (string) $row['proposal_review_notes']
                    : 'Belum ada catatan review proposal.',
                'abstract' => [
                    'title' => 'Abstrak Proposal',
                    'html' => $detailPayload['review_summary']['abstrak'] ?? '',
                    'empty_message' => 'Abstrak proposal belum tersedia.',
                ],
                'substansi_sections' => $this->buildPresentationSectionsFromDetailPayload($detailPayload['review_step3_summary'] ?? []),
            ],
            'presentation' => $this->buildPresentationScoringPayload($row, $assessment, $totals),
        ];
    }

    private function buildPresentationSummaryItems(array $detailPayload, array $assessment): array
    {
        return [
            ['label' => 'Judul Usulan', 'value' => $detailPayload['judul'] ?? '-'],
            ['label' => 'Kata Kunci', 'value' => $detailPayload['kata_kunci_formatted'] ?? '-'],
            ['label' => 'Pengelola Bantuan', 'value' => $detailPayload['pengelola_bantuan_nama'] ?? '-'],
            ['label' => 'Bidang Ilmu', 'value' => $detailPayload['bidang_ilmu_nama'] ?? '-'],
            ['label' => 'Skor Proposal', 'value' => $this->formatScore($assessment['proposal_score_value'] ?? null)],
            ['label' => 'Status Presentasi', 'value' => $this->mapProposalSummaryStatus((string) ($assessment['review_status'] ?? 'pending'))],
        ];
    }

    private function buildPresentationSectionsFromDetailPayload(array $reviewStep3): array
    {
        $sections = [];

        $sections[] = [
            'title' => 'Abstrak',
            'content_html' => (string) ($reviewStep3['abstrak'] ?? ''),
        ];

        foreach (array_values($reviewStep3['substansi_bagian'] ?? []) as $index => $section) {
            $sections[] = [
                'title' => trim((string) ($section['judul_bagian'] ?? '')) !== '' ? (string) $section['judul_bagian'] : 'Bagian ' . ($index + 1),
                'content_html' => (string) ($section['isi_bagian'] ?? ''),
            ];
        }

        return $sections;
    }

    private function buildPresentationScoringPayload(array $row, array $assessment, array $totals): array
    {
        $sections = [];
        $options = $this->buildProposalScaleOptions();

        foreach (self::PRESENTATION_SCORE_ASPECTS as $key => $aspect) {
            $sections[] = [
                'title' => $aspect['number'] . '. ' . $aspect['label'] . ' (Bobot: ' . $aspect['weight'] . ')',
                'score_field_name' => 'scores[' . $key . ']',
                'score_value' => (string) ($assessment['scores'][$key] ?? ''),
                'comment_field_name' => 'comments[' . $key . ']',
                'comment_value' => (string) ($assessment['comments'][$key] ?? ''),
                'options' => $options,
            ];
        }

        return [
            'card_title' => 'Penilaian Presentasi',
            'sections' => $sections,
            'general_comment' => [
                'label' => 'Komentar Umum Presentasi',
                'field_name' => 'general_comment',
                'value' => (string) ($assessment['general_comment'] ?? ''),
            ],
            'validator_note' => [
                'label' => 'Catatan Validator Presentasi',
                'field_name' => 'validator_note',
                'value' => (string) ($assessment['validator_note'] ?? ''),
                'hint' => 'Catatan singkat ini akan tersimpan pada sesi presentasi reviewer.',
            ],
            'totals' => [
                'raw_label' => 'Total Bobot x Skor',
                'raw_value' => (string) $totals['raw_total'],
                'normalized_label' => 'Nilai Akhir Presentasi',
                'normalized_value' => $this->formatScore($totals['normalized_total']),
                'hint' => 'Nilai akhir presentasi dihitung dengan rumus total bobot x skor lalu dikonversi ke skala 100.',
            ],
            'form' => [
                'action_url' => site_url('reviewer/queue/presentasi/' . $row['item_key'] . '/save'),
                'submit_label' => 'Simpan Penilaian Presentasi',
            ],
        ];
    }

    private function mergePresentationAssessmentState(array $row): array
    {
        $defaultAssessment = is_array($row['presentation_assessment'] ?? null) ? $row['presentation_assessment'] : [];
        $storedAssessment = $this->getStoredAssessmentState('presentasi', (string) ($row['item_key'] ?? ''));

        $scores = $this->normalizePresentationScores(array_merge(
            $defaultAssessment['scores'] ?? [],
            $storedAssessment['scores'] ?? []
        ));

        $comments = $this->normalizeSectionComments(array_merge(
            $defaultAssessment['comments'] ?? [],
            $storedAssessment['comments'] ?? []
        ));

        $generalComment = $storedAssessment['general_comment'] ?? ($defaultAssessment['general_comment'] ?? '');
        $validatorNote = $storedAssessment['validator_note'] ?? ($defaultAssessment['validator_note'] ?? '');
        $reviewStatus = (string) ($storedAssessment['review_status'] ?? ($row['review_status'] ?? 'pending'));
        $scoreValue = null;

        if (array_key_exists('score_value', $storedAssessment) && is_numeric($storedAssessment['score_value'])) {
            $scoreValue = (float) $storedAssessment['score_value'];
        } elseif (is_numeric($row['score_value'] ?? null)) {
            $scoreValue = (float) $row['score_value'];
        }

        $proposalScoreValue = null;
        if (is_numeric($row['proposal_review_score'] ?? null)) {
            $proposalScoreValue = (float) $row['proposal_review_score'];
        }

        return [
            'scores' => $scores,
            'comments' => $comments,
            'general_comment' => $this->cleanHtml((string) $generalComment),
            'validator_note' => trim((string) $validatorNote),
            'review_status' => $reviewStatus,
            'score_value' => $scoreValue,
            'proposal_score_value' => $proposalScoreValue,
        ];
    }

    private function normalizePresentationScores(array $scores): array
    {
        $normalized = [];

        foreach (array_keys(self::PRESENTATION_SCORE_ASPECTS) as $key) {
            $value = (int) ($scores[$key] ?? 0);
            $normalized[$key] = $value >= 1 && $value <= 5 ? $value : 0;
        }

        return $normalized;
    }

    private function calculatePresentationScoreTotals(array $scores): array
    {
        $rawTotal = 0;

        foreach (self::PRESENTATION_SCORE_ASPECTS as $key => $aspect) {
            $rawTotal += ((int) ($scores[$key] ?? 0)) * (int) $aspect['weight'];
        }

        return [
            'raw_total' => $rawTotal,
            'normalized_total' => round($rawTotal / 5, 2),
        ];
    }

    private function formatPresentationQueueScore(object $proposal): ?float
    {
        if (is_numeric($proposal->proposal_review_score ?? null)) {
            return (float) $proposal->proposal_review_score;
        }

        return null;
    }

    private function composePresentationNotesSummary(array $presentationRow, array $comments, string $generalComment, string $validatorNote, string $formattedScore): string
    {
        $lines = [];

        $lines[] = 'Nilai Presentasi: ' . $formattedScore;

        foreach (self::PRESENTATION_SCORE_ASPECTS as $key => $aspect) {
            $comment = trim((string) ($comments[$key] ?? ''));
            if ($comment === '') {
                continue;
            }

            $lines[] = $aspect['label'] . ': ' . $this->stripHtmlToText($comment);
        }

        if ($generalComment !== '') {
            $lines[] = 'Komentar Umum Presentasi: ' . $this->stripHtmlToText($generalComment);
        }

        if ($validatorNote !== '') {
            $lines[] = 'Catatan Validator Presentasi: ' . $validatorNote;
        }

        return implode("\n\n", $lines);
    }

    private function loadProposalById(int $proposalId): ?object
    {
        if ($proposalId <= 0) {
            return null;
        }

        $proposal = $this->proposalModel->find($proposalId);

        return is_object($proposal) ? $proposal : null;
    }

    private function buildProposalSummaryItems(array $row, array $assessment): array
    {
        return [
            [
                'label' => 'Judul Usulan',
                'value' => $row['title'],
            ],
            [
                'label' => 'Klaster',
                'value' => $row['cluster'],
            ],
            [
                'label' => 'Usulan Biaya',
                'value' => (string) ($row['budget_label'] ?? $this->formatCurrency((int) ($row['budget_amount'] ?? 0))),
            ],
            [
                'label' => 'Status Review',
                'value' => $this->mapProposalSummaryStatus((string) ($assessment['review_status'] ?? 'pending')),
            ],
            [
                'label' => 'Lampiran Berkas',
                'value' => (string) ($row['attachment_label'] ?? 'Tidak ada berkas.'),
            ],
        ];
    }

    private function buildProposalReviewSections(array $row, array $assessment): array
    {
        $sections = [];

        foreach (($row['proposal_sections'] ?? []) as $index => $section) {
            $sectionKey = (string) ($section['key'] ?? 'section_' . $index);
            $commentValue = (string) ($assessment['comments'][$sectionKey] ?? '');
            $editorId = 'reviewer-comment-' . $row['item_key'] . '-' . $sectionKey;
            $inputId = 'reviewer-comment-input-' . $row['item_key'] . '-' . $sectionKey;

            $sections[] = [
                'title' => (string) ($section['title'] ?? 'Bagian Proposal'),
                'content_html' => (string) ($section['content_html'] ?? ''),
                'comment_label' => 'Komentar Anda untuk bagian: ' . (string) ($section['title'] ?? 'Bagian Proposal'),
                'comment_field_name' => 'comments[' . $sectionKey . ']',
                'comment_value' => $commentValue,
                'editor_id' => $editorId,
                'editor_name' => $editorId,
                'input_id' => $inputId,
            ];
        }

        return $sections;
    }

    private function getProposalRows(): array
    {
        $reviewerId = $this->getCurrentUserId();
        if ($reviewerId <= 0) {
            return [];
        }

        $builder = $this->db->table('proposal_reviewer_assignments')
            ->select([
                'proposal_pengajuan.*',
                'proposal_reviewer_assignments.uuid AS assignment_uuid',
                'proposal_reviewer_assignments.status AS assignment_status',
                'proposal_reviewer_assignments.recommendation AS assignment_recommendation',
                'proposal_reviewer_assignments.review_notes AS assignment_review_notes',
                'proposal_reviewer_assignments.reviewed_at AS assignment_reviewed_at',
            ]);

        if ($this->hasReviewScoreColumn()) {
            $builder->select('proposal_reviewer_assignments.review_score AS assignment_review_score');
        }

        $rows = $builder
            ->join('proposal_pengajuan', 'proposal_pengajuan.id = proposal_reviewer_assignments.proposal_id AND proposal_pengajuan.deleted_at IS NULL', 'inner')
            ->where('proposal_reviewer_assignments.deleted_at', null)
            ->where('proposal_reviewer_assignments.reviewer_user_id', $reviewerId)
            ->whereIn('proposal_reviewer_assignments.status', ['assigned', 'reviewed'])
            ->orderBy('proposal_reviewer_assignments.updated_at', 'DESC')
            ->get()
            ->getResult();

        return array_map(fn(object $proposal): array => $this->buildProposalRow($proposal), $rows);
    }

    private function getPresentationRows(): array
    {
        $reviewerId = $this->getCurrentUserId();
        if ($reviewerId <= 0) {
            return [];
        }

        $builder = $this->db->table('proposal_reviewer_assignments')
            ->select([
                'proposal_pengajuan.*',
                'proposal_reviewer_assignments.uuid AS assignment_uuid',
                'proposal_reviewer_assignments.status AS assignment_status',
                'proposal_reviewer_assignments.review_notes AS proposal_review_notes',
                'proposal_reviewer_assignments.reviewed_at AS proposal_reviewed_at',
            ])
            ->join('proposal_pengajuan', 'proposal_pengajuan.id = proposal_reviewer_assignments.proposal_id AND proposal_pengajuan.deleted_at IS NULL', 'inner')
            ->where('proposal_reviewer_assignments.deleted_at', null)
            ->where('proposal_reviewer_assignments.reviewer_user_id', $reviewerId)
            ->where('proposal_reviewer_assignments.status', 'reviewed')
            ->orderBy('proposal_reviewer_assignments.updated_at', 'DESC');

        if ($this->hasReviewScoreColumn()) {
            $builder->select('proposal_reviewer_assignments.review_score AS proposal_review_score');
        }

        $rows = $builder->get()->getResult();

        return array_map(fn(object $proposal): array => $this->buildPresentationRow($proposal), $rows);
    }

    private function buildProposalRow(object $proposal): array
    {
        $detailPayload = $this->proposalDetailService->buildReviewPayload($proposal);
        $reviewStep3 = $detailPayload['review_step3_summary'] ?? [];
        $summary = $detailPayload['summary'] ?? [];
        $documentRows = $detailPayload['document_rows'] ?? [];
        $attachmentCount = count(is_array($documentRows) ? $documentRows : []);

        return [
            'proposal_id' => (int) ($proposal->id ?? 0),
            'item_key' => (string) ($proposal->uuid ?? ''),
            'assignment_uuid' => (string) ($proposal->assignment_uuid ?? ''),
            'title' => trim((string) ($detailPayload['judul'] ?? $proposal->judul ?? '')) ?: 'Judul belum diisi',
            'cluster' => trim((string) ($detailPayload['klaster_bantuan_nama'] ?? '')) ?: '-',
            'score_value' => is_numeric($proposal->assignment_review_score ?? null)
                ? (float) $proposal->assignment_review_score
                : $this->extractReviewScoreFromNotes((string) ($proposal->assignment_review_notes ?? '')),
            'review_status' => $this->deriveProposalReviewStatus($proposal),
            'budget_label' => trim((string) ($summary['total_pengajuan_dana'] ?? '')) ?: '-',
            'attachment_label' => $attachmentCount > 0 ? $attachmentCount . ' berkas tersedia.' : 'Tidak ada berkas.',
            'proposal_sections' => $this->buildProposalSectionsFromDetailPayload($reviewStep3),
            'proposal_assessment' => [],
        ];
    }

    private function buildPresentationRow(object $proposal): array
    {
        $detailPayload = $this->proposalDetailService->buildReviewPayload($proposal);
        $reviewStep3 = $detailPayload['review_step3_summary'] ?? [];
        $summary = $detailPayload['summary'] ?? [];
        $storedAssessment = $this->mergePresentationAssessmentState([
            'item_key' => (string) ($proposal->uuid ?? ''),
            'proposal_id' => (int) ($proposal->id ?? 0),
            'proposal_review_score' => is_numeric($proposal->proposal_review_score ?? null) ? (float) $proposal->proposal_review_score : null,
            'proposal_review_notes' => (string) ($proposal->proposal_review_notes ?? ''),
            'review_status' => 'pending',
            'presentation_assessment' => [],
        ]);

        return [
            'proposal_id' => (int) ($proposal->id ?? 0),
            'item_key' => (string) ($proposal->uuid ?? ''),
            'assignment_uuid' => (string) ($proposal->assignment_uuid ?? ''),
            'title' => trim((string) ($detailPayload['judul'] ?? $proposal->judul ?? '')) ?: 'Judul belum diisi',
            'cluster' => trim((string) ($detailPayload['klaster_bantuan_nama'] ?? '')) ?: '-',
            'score_value' => is_numeric($storedAssessment['score_value'] ?? null)
                ? (float) $storedAssessment['score_value']
                : $this->formatPresentationQueueScore($proposal),
            'review_status' => (string) ($storedAssessment['review_status'] ?? 'pending'),
            'proposal_review_score' => is_numeric($proposal->proposal_review_score ?? null) ? (float) $proposal->proposal_review_score : null,
            'proposal_review_notes' => (string) ($proposal->proposal_review_notes ?? ''),
            'proposal_sections' => $this->buildProposalSectionsFromDetailPayload($reviewStep3),
            'proposal_assessment' => [],
            'presentation_assessment' => [],
            'budget_label' => trim((string) ($summary['total_pengajuan_dana'] ?? '')) ?: '-',
            'attachment_label' => 'Dokumen proposal tersedia untuk tahap presentasi.',
        ];
    }

    private function buildProposalSectionsFromDetailPayload(array $reviewStep3): array
    {
        $sections = [];
        $abstractHtml = (string) ($reviewStep3['abstrak'] ?? '');

        $sections[] = [
            'key' => 'abstrak',
            'title' => 'Abstrak',
            'content_html' => $abstractHtml,
        ];

        foreach (array_values($reviewStep3['substansi_bagian'] ?? []) as $index => $section) {
            $sections[] = [
                'key' => 'substansi_' . ($index + 1),
                'title' => trim((string) ($section['judul_bagian'] ?? '')) !== '' ? (string) $section['judul_bagian'] : 'Bagian ' . ($index + 1),
                'content_html' => (string) ($section['isi_bagian'] ?? ''),
            ];
        }

        return $sections;
    }

    private function deriveProposalReviewStatus(object $proposal): string
    {
        $assignmentStatus = (string) ($proposal->assignment_status ?? 'assigned');
        $reviewNotes = trim((string) ($proposal->assignment_review_notes ?? ''));

        if ($assignmentStatus === 'reviewed') {
            return 'completed';
        }

        if ($reviewNotes !== '') {
            return 'draft';
        }

        return 'pending';
    }

    private function composeReviewNotesSummary(array $proposalRow, array $comments, string $generalComment, string $validatorNote, string $formattedScore): string
    {
        $lines = [];

        $lines[] = 'Nilai: ' . $formattedScore;

        foreach (($proposalRow['proposal_sections'] ?? []) as $section) {
            $sectionKey = (string) ($section['key'] ?? '');
            $sectionTitle = trim((string) ($section['title'] ?? ''));
            $comment = trim((string) ($comments[$sectionKey] ?? ''));

            if ($comment === '') {
                continue;
            }

            $lines[] = $sectionTitle !== '' ? $sectionTitle . ': ' . $this->stripHtmlToText($comment) : $this->stripHtmlToText($comment);
        }

        if ($generalComment !== '') {
            $lines[] = 'Komentar Umum: ' . $this->stripHtmlToText($generalComment);
        }

        if ($validatorNote !== '') {
            $lines[] = 'Catatan Validator: ' . $validatorNote;
        }

        return implode("\n\n", $lines);
    }

    private function extractReviewScoreFromNotes(string $notes): ?float
    {
        if (preg_match('/^Nilai:\s*([0-9]+(?:[\.,][0-9]+)?)/m', $notes, $matches) !== 1) {
            return null;
        }

        return (float) str_replace(',', '.', $matches[1]);
    }

    private function stripHtmlToText(string $value): string
    {
        $value = trim(strip_tags($value));

        return preg_replace('/\s+/', ' ', $value) ?: '';
    }

    private function mapRecommendationByScore(float $score): string
    {
        if ($score >= 80) {
            return 'recommended';
        }

        if ($score >= 60) {
            return 'revision';
        }

        return 'rejected';
    }

    private function syncProposalReviewStatus(int $proposalId): void
    {
        $assignedCount = $this->db->table('proposal_reviewer_assignments')
            ->where('proposal_id', $proposalId)
            ->where('deleted_at', null)
            ->whereIn('status', ['assigned', 'reviewed'])
            ->countAllResults();

        $reviewedCount = $this->db->table('proposal_reviewer_assignments')
            ->where('proposal_id', $proposalId)
            ->where('deleted_at', null)
            ->where('status', 'reviewed')
            ->countAllResults();

        if ($assignedCount > 0 && $assignedCount === $reviewedCount) {
            $this->proposalModel->update($proposalId, ['status' => 'reviewed']);
            return;
        }

        $this->proposalModel->update($proposalId, ['status' => 'assigned']);
    }

    private function hasReviewScoreColumn(): bool
    {
        if ($this->hasReviewScoreColumn !== null) {
            return $this->hasReviewScoreColumn;
        }

        $this->hasReviewScoreColumn = $this->db->fieldExists('review_score', 'proposal_reviewer_assignments');

        return $this->hasReviewScoreColumn;
    }

    private function getCurrentUserId(): int
    {
        $user = service('auth')->user();

        return (int) ($user['id'] ?? 0);
    }

    private function buildProposalScoringPayload(array $row, array $assessment, array $totals): array
    {
        $aspects = [];
        $options = $this->buildProposalScaleOptions();

        foreach (self::PROPOSAL_SCORE_ASPECTS as $key => $aspect) {
            $aspects[] = [
                'field_name' => 'scores[' . $key . ']',
                'label' => $aspect['number'] . '. ' . $aspect['label'] . ' (Bobot: ' . $aspect['weight'] . ')',
                'selected_value' => (string) ($assessment['scores'][$key] ?? ''),
                'options' => $options,
            ];
        }

        return [
            'card_title' => 'Skor Penilaian',
            'aspects' => $aspects,
            'general_comment' => [
                'label' => 'Komentar Umum Proposal',
                'field_name' => 'general_comment',
                'value' => (string) ($assessment['general_comment'] ?? ''),
                'editor_id' => 'reviewer-general-comment-' . $row['item_key'],
                'editor_name' => 'reviewer-general-comment-' . $row['item_key'],
                'input_id' => 'reviewer-general-comment-input-' . $row['item_key'],
            ],
            'validator_note' => [
                'label' => 'Catatan Validator (Ringkasan untuk Peneliti)',
                'field_name' => 'validator_note',
                'value' => (string) ($assessment['validator_note'] ?? ''),
                'hint' => 'Catatan singkat ini akan ditampilkan kepada peneliti. Contoh: "Lanjut ke tahap berikutnya, judul perlu direvisi".',
            ],
            'totals' => [
                'raw_label' => 'Total Bobot x Skor',
                'raw_value' => (string) $totals['raw_total'],
                'normalized_label' => 'Nilai Akhir Reviewer',
                'normalized_value' => $this->formatScore($totals['normalized_total']),
                'hint' => 'Nilai akhir reviewer ditampilkan pada daftar antrian dengan konversi total rumus dibagi 5 agar kembali ke skala 100.',
            ],
        ];
    }

    private function buildDetailTabs(string $activeTab): array
    {
        $tabs = [];

        foreach (self::DETAIL_TAB_DEFINITIONS as $key => $definition) {
            $isActive = $key === $activeTab;
            $tabs[] = [
                'label' => $definition['label'],
                'icon' => $definition['icon'],
                'button_class' => $isActive ? 'nav-link active' : 'nav-link',
                'button_id' => 'reviewer-detail-tab-' . $key,
                'aria_selected' => $isActive ? 'true' : 'false',
                'pane_id' => 'reviewer-detail-pane-' . $key,
                'pane_class' => $isActive ? 'tab-pane fade show active' : 'tab-pane fade',
            ];
        }

        return $tabs;
    }

    private function normalizeDetailTab(?string $tab): string
    {
        $tab = strtolower(trim((string) $tab));

        return array_key_exists($tab, self::DETAIL_TAB_DEFINITIONS) ? $tab : 'review';
    }

    private function buildProposalScaleOptions(): array
    {
        $options = [];

        foreach (range(1, 5) as $scale) {
            $options[] = [
                'value' => (string) $scale,
                'label' => (string) $scale,
            ];
        }

        return $options;
    }

    private function calculateProposalScoreTotals(array $scores): array
    {
        $rawTotal = 0;

        foreach (self::PROPOSAL_SCORE_ASPECTS as $key => $aspect) {
            $rawTotal += ((int) ($scores[$key] ?? 0)) * (int) $aspect['weight'];
        }

        return [
            'raw_total' => $rawTotal,
            'normalized_total' => round($rawTotal / 5, 2),
        ];
    }

    private function mergeProposalAssessmentState(array $row): array
    {
        $defaultAssessment = is_array($row['proposal_assessment'] ?? null) ? $row['proposal_assessment'] : [];
        $storedAssessment = $this->getStoredAssessmentState('proposal', (string) ($row['item_key'] ?? ''));

        $scores = $this->normalizeProposalScores(array_merge(
            $defaultAssessment['scores'] ?? [],
            $storedAssessment['scores'] ?? []
        ));

        $comments = $this->normalizeSectionComments(array_merge(
            $defaultAssessment['comments'] ?? [],
            $storedAssessment['comments'] ?? []
        ));

        $generalComment = $storedAssessment['general_comment'] ?? ($defaultAssessment['general_comment'] ?? '');
        $validatorNote = $storedAssessment['validator_note'] ?? ($defaultAssessment['validator_note'] ?? '');
        $reviewStatus = (string) ($storedAssessment['review_status'] ?? ($row['review_status'] ?? 'pending'));
        $scoreValue = null;

        if (array_key_exists('score_value', $storedAssessment) && is_numeric($storedAssessment['score_value'])) {
            $scoreValue = (float) $storedAssessment['score_value'];
        } elseif (is_numeric($row['score_value'] ?? null)) {
            $scoreValue = (float) $row['score_value'];
        }

        return [
            'scores' => $scores,
            'comments' => $comments,
            'general_comment' => $this->cleanHtml((string) $generalComment),
            'validator_note' => trim((string) $validatorNote),
            'review_status' => $reviewStatus,
            'score_value' => $scoreValue,
        ];
    }

    private function normalizeProposalScores(array $scores): array
    {
        $normalized = [];

        foreach (array_keys(self::PROPOSAL_SCORE_ASPECTS) as $key) {
            $value = (int) ($scores[$key] ?? 0);
            $normalized[$key] = $value >= 1 && $value <= 5 ? $value : 0;
        }

        return $normalized;
    }

    private function normalizeSectionComments(array $comments): array
    {
        $normalized = [];

        foreach ($comments as $key => $comment) {
            $normalized[(string) $key] = $this->cleanHtml((string) $comment);
        }

        return $normalized;
    }

    private function applyStoredAssessmentState(string $category, array $row): array
    {
        if ($category === 'proposal') {
            $assessment = $this->mergeProposalAssessmentState($row);

            $row['score_value'] = $assessment['score_value'];
            $row['review_status'] = $assessment['review_status'];

            return $row;
        }

        if ($category === 'presentasi') {
            $assessment = $this->mergePresentationAssessmentState($row);

            $row['score_value'] = $assessment['score_value'];
            $row['review_status'] = $assessment['review_status'];

            return $row;
        }

        return $row;
    }

    private function getStoredAssessmentState(string $category, string $itemKey): array
    {
        $storage = $this->getSessionStorage();

        return is_array($storage[$category][$itemKey] ?? null) ? $storage[$category][$itemKey] : [];
    }

    private function getSessionStorage(): array
    {
        $storage = session()->get(self::SESSION_KEY);

        return is_array($storage) ? $storage : [];
    }

    private function findAssessmentRow(string $category, string $itemKey): ?array
    {
        foreach ($this->getRowsForCategory($category) as $row) {
            if (($row['item_key'] ?? '') === $itemKey) {
                return $row;
            }
        }

        return null;
    }

    private function cleanHtml(string $value): string
    {
        $value = trim($value);

        if ($value === '') {
            return '';
        }

        return strip_tags($value, '<p><br><strong><em><u><ol><ul><li><blockquote><a><h1><h2><h3><span>');
    }

    private function formatCurrency(int $amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    private function mapProposalSummaryStatus(string $status): string
    {
        return match ($status) {
            'draft' => 'draft',
            'completed' => 'sudah dinilai',
            default => 'ditugaskan',
        };
    }

    private function filterRowsByStatus(array $rows, string $selectedStatus): array
    {
        if ($selectedStatus === '') {
            return $rows;
        }

        return array_values(array_filter(
            $rows,
            static fn(array $row): bool => (string) ($row['review_status'] ?? 'pending') === $selectedStatus
        ));
    }

    private function buildStatusOptions(): array
    {
        $options = [];

        foreach (self::STATUS_OPTIONS as $value => $label) {
            $options[] = [
                'value' => $value,
                'label' => $label,
            ];
        }

        return $options;
    }

    private function normalizeStatusFilter(?string $status): string
    {
        $status = strtolower(trim((string) $status));

        return array_key_exists($status, self::STATUS_OPTIONS) ? $status : '';
    }

    private function formatScore(?float $score): string
    {
        if ($score === null) {
            return '-';
        }

        return number_format($score, 2, ',', '.');
    }

    private function mapStatusLabel(string $status): string
    {
        return match ($status) {
            'draft' => 'Draft',
            'completed' => 'Sudah Dinilai',
            default => 'Belum Dinilai',
        };
    }

    private function mapStatusBadgeClass(string $status): string
    {
        return match ($status) {
            'draft' => 'text-bg-primary',
            'completed' => 'text-bg-success',
            default => 'text-bg-warning',
        };
    }

    private function collectAllRows(): array
    {
        $rows = [];

        foreach (array_keys(self::TAB_DEFINITIONS) as $category) {
            $rows = array_merge($rows, $this->getRowsForCategory($category));
        }

        return $rows;
    }
}
