<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="reviewer-workspace" id="reviewerWorkspace">
    <!-- LEFT SIDE: PDF VIEWER -->
    <div class="workspace-left" id="workspaceLeft">
        <div class="workspace-card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon-sm bg-danger-soft text-danger rounded-circle">
                        <i class="bi bi-file-pdf-fill"></i>
                    </div>
                    <h6 class="mb-0 fw-bold">Dokumen Proposal</h6>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-light btn-sm rounded-pill border" id="btnToggleLeft">
                        <i class="bi bi-arrows-angle-expand"></i>
                    </button>
                    <a href="<?= $primary_pdf_url ?>" target="_blank" class="btn btn-primary-soft btn-sm rounded-pill">
                        <i class="bi bi-box-arrow-up-right me-1"></i> Full
                    </a>
                </div>
            </div>
            <div class="card-body p-0 bg-secondary-light position-relative">
                <?php if ($primary_pdf_url): ?>
                    <iframe src="<?= $primary_pdf_url ?>#toolbar=0" class="pdf-iframe" id="pdfIframe"></iframe>
                <?php else: ?>
                    <div class="h-100 d-flex flex-column align-items-center justify-content-center text-center p-5">
                        <div class="mb-4">
                            <i class="bi bi-file-earmark-x display-1 text-muted opacity-25"></i>
                        </div>
                        <h5 class="fw-bold">Dokumen Tidak Ditemukan</h5>
                        <p class="text-muted">Pengusul belum mengunggah berkas proposal utama atau format berkas tidak didukung viewer.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- RESIZER BAR -->
    <div class="workspace-resizer" id="workspaceResizer"></div>

    <!-- RIGHT SIDE: ASSESSMENT FORM -->
    <div class="workspace-right" id="workspaceRight">
        <div class="workspace-card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white d-flex flex-column">
            <!-- Header Ringkasan & Tab -->
            <div class="card-header bg-white pt-3 px-4 border-bottom">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="badge <?= $detail['review_status_badge_class'] ?> rounded-pill mb-2"><?= $detail['review_status_label'] ?></span>
                        <h5 class="fw-bold text-dark mb-1 line-clamp-1"><?= esc($detail['title']) ?></h5>
                        <p class="text-muted small mb-0"><i class="bi bi-tag me-1"></i> <?= esc($detail['cluster']) ?></p>
                    </div>
                    <a href="<?= $detail['back_url'] ?>" class="btn btn-light btn-sm rounded-pill border shadow-sm">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>

                <!-- Tabs Navigation -->
                <ul class="nav nav-pills workspace-nav-pills gap-2 pb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-pill px-4" id="pills-scoring-tab" data-bs-toggle="pill" data-bs-target="#pills-scoring" type="button" role="tab">
                            <i class="bi bi-table me-2"></i> Penilaian
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-4" id="pills-substance-tab" data-bs-toggle="pill" data-bs-target="#pills-substance" type="button" role="tab">
                            <i class="bi bi-card-text me-2"></i> Substansi
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-4" id="pills-docs-tab" data-bs-toggle="pill" data-bs-target="#pills-docs" type="button" role="tab">
                            <i class="bi bi-files me-2"></i> Berkas
                        </button>
                    </li>
                </ul>
            </div>

            <!-- Tab Content (Scrollable Area) -->
            <div class="card-body p-0 flex-grow-1 overflow-auto bg-body-tertiary">
                <div class="tab-content h-100" id="pills-tabContent">
                    <!-- SCORING TAB -->
                    <div class="tab-pane fade show active p-4" id="pills-scoring" role="tabpanel">
                        <form action="<?= $proposal['form']['action_url'] ?>" method="POST" id="assessmentForm">
                            <?= csrf_field() ?>
                            
                            <div class="scoring-container">
                                <?php foreach ($proposal['scoring']['sections'] as $section): ?>
                                    <div class="card border-0 shadow-sm rounded-3 mb-4 assessment-item">
                                        <div class="card-body p-4">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="fw-bold text-dark mb-0"><?= $section['title'] ?></h6>
                                                <span class="badge bg-primary-soft text-primary rounded-pill">Bobot: <?= $section['weight'] ?? '10' ?></span>
                                            </div>
                                            
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label class="form-label small text-muted">Skor (1-5)</label>
                                                    <select name="<?= $section['score_field_name'] ?>" class="form-select border-0 bg-light-soft rounded-3 scoring-select" data-weight="<?= $section['weight'] ?? '10' ?>" required>
                                                        <option value="" disabled <?= empty($section['score_value']) ? 'selected' : '' ?>>Pilih Skor</option>
                                                        <?php foreach ($section['options'] as $option): ?>
                                                            <option value="<?= $option['value'] ?>" <?= (string)$section['score_value'] === (string)$option['value'] ? 'selected' : '' ?>>
                                                                <?= $option['label'] ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-8">
                                                    <label class="form-label small text-muted">Komentar Bagian</label>
                                                    <textarea name="<?= $section['comment_field_name'] ?>" class="form-control border-0 bg-light-soft rounded-3" rows="2" placeholder="Catatan untuk aspek ini..."><?= esc($section['comment_value']) ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                                <!-- KOMENTAR UMUM & CATATAN VALIDATOR -->
                                <div class="card border-0 shadow-sm rounded-4 mb-4">
                                    <div class="card-body p-4">
                                        <div class="mb-4">
                                            <label class="form-label fw-bold text-dark"><?= $proposal['scoring']['general_comment']['label'] ?></label>
                                            <textarea name="<?= $proposal['scoring']['general_comment']['field_name'] ?>" class="form-control border-0 bg-light-soft rounded-3" rows="4" placeholder="Berikan ulasan menyeluruh mengenai proposal ini..."><?= esc($proposal['scoring']['general_comment']['value']) ?></textarea>
                                        </div>
                                        <div class="mb-0">
                                            <label class="form-label fw-bold text-dark"><?= $proposal['scoring']['validator_note']['label'] ?></label>
                                            <textarea name="<?= $proposal['scoring']['validator_note']['field_name'] ?>" class="form-control border-0 bg-light-soft rounded-3" rows="2" placeholder="Ringkasan keputusan untuk peneliti..."><?= esc($proposal['scoring']['validator_note']['value']) ?></textarea>
                                            <div class="form-text text-muted small mt-2">
                                                <i class="bi bi-info-circle me-1"></i> <?= $proposal['scoring']['validator_note']['hint'] ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Summary Result Card -->
                                <div class="card border-0 bg-primary shadow-lg rounded-4 mb-5 text-white">
                                    <div class="card-body p-4 d-flex align-items-center justify-content-between">
                                        <div>
                                            <h6 class="mb-1 text-white-50">Estimasi Nilai Akhir</h6>
                                            <h2 class="mb-0 fw-bold" id="totalScore"><?= $proposal['scoring']['totals']['normalized_value'] ?></h2>
                                        </div>
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-white btn-lg rounded-pill px-5 fw-bold shadow-sm">
                                                Simpan Penilaian
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- SUBSTANCE TAB -->
                    <div class="tab-pane fade p-4" id="pills-substance" role="tabpanel">
                        <?php foreach ($proposal['review']['sections'] as $section): ?>
                            <div class="card border-0 shadow-sm rounded-4 mb-4">
                                <div class="card-header bg-white py-3 border-bottom">
                                    <h6 class="mb-0 fw-bold text-primary"><?= $section['title'] ?></h6>
                                </div>
                                <div class="card-body p-4 substance-content text-dark">
                                    <?= $section['content_html'] ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- DOCUMENTS TAB -->
                    <div class="tab-pane fade p-4" id="pills-docs" role="tabpanel">
                        <div class="row g-4">
                            <?php foreach ($proposal['documents']['rows'] as $doc): ?>
                                <div class="col-12 col-xl-6">
                                    <div class="card border shadow-sm rounded-4 h-100">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="stat-icon-sm bg-light-soft text-primary rounded-circle">
                                                    <i class="bi bi-file-earmark-text"></i>
                                                </div>
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <h6 class="mb-1 fw-bold text-truncate"><?= $doc['label'] ?></h6>
                                                    <p class="text-muted small mb-0"><?= $doc['file_size_label'] ?></p>
                                                </div>
                                                <?php if ($doc['has_file']): ?>
                                                    <a href="<?= $doc['view_url'] ?>" target="_blank" class="btn btn-primary-soft btn-sm rounded-circle">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* WORKSPACE CORE LAYOUT */
    .reviewer-workspace {
        display: flex;
        height: calc(100vh - 140px); /* Adjust based on navbar/header height */
        gap: 0;
        margin: -15px -20px; /* Offset parent padding */
        position: relative;
    }

    .workspace-left {
        flex: 1 1 50%;
        min-width: 300px;
        padding: 15px;
        transition: all 0.3s ease;
    }

    .workspace-right {
        flex: 1 1 50%;
        min-width: 400px;
        padding: 15px;
        transition: all 0.3s ease;
    }

    .workspace-resizer {
        width: 10px;
        cursor: col-resize;
        background: transparent;
        transition: background 0.2s;
        z-index: 10;
        margin: 15px 0;
    }

    .workspace-resizer:hover {
        background: rgba(var(--bs-primary-rgb), 0.1);
    }

    /* PDF VIEWER */
    .pdf-iframe {
        width: 100%;
        height: 100%;
        border: none;
    }

    /* CUSTOM UI ELEMENTS */
    .bg-light-soft { background-color: rgba(var(--bs-light-rgb), 0.6); }
    .bg-danger-soft { background-color: rgba(var(--bs-danger-rgb), 0.1); }
    .stat-icon-sm { width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; }
    .btn-primary-soft { background: rgba(var(--bs-primary-rgb), 0.1); color: var(--bs-primary); border: none; }
    .btn-primary-soft:hover { background: var(--bs-primary); color: white; }
    .btn-white { background: white; color: var(--bs-primary); border: none; }
    .btn-white:hover { background: #f8f9fa; }
    
    .workspace-nav-pills .nav-link {
        color: var(--bs-gray-600);
        background: transparent;
        font-weight: 600;
        font-size: 0.9rem;
    }
    .workspace-nav-pills .nav-link.active {
        background: var(--bs-primary);
        color: white;
    }

    .line-clamp-1 { display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
    .substance-content img { max-width: 100%; height: auto; border-radius: 8px; }

    /* STATE MODIFIERS */
    .workspace-full-left .workspace-right { display: none; }
    .workspace-full-left .workspace-left { flex: 1 1 100%; }
    .workspace-full-left .workspace-resizer { display: none; }

    /* SCROLLBAR CUSTOMIZATION */
    .overflow-auto::-webkit-scrollbar { width: 6px; }
    .overflow-auto::-webkit-scrollbar-track { background: transparent; }
    .overflow-auto::-webkit-scrollbar-thumb { background: #ddd; border-radius: 10px; }
    .overflow-auto::-webkit-scrollbar-thumb:hover { background: #ccc; }

    @media (max-width: 991.98px) {
        .reviewer-workspace { flex-direction: column; height: auto; }
        .workspace-left, .workspace-right { flex: 1 1 100%; height: 600px; }
        .workspace-resizer { display: none; }
    }
</style>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const resizer = document.getElementById('workspaceResizer');
    const leftSide = document.getElementById('workspaceLeft');
    const rightSide = document.getElementById('workspaceRight');
    const workspace = document.getElementById('reviewerWorkspace');
    const btnToggle = document.getElementById('btnToggleLeft');

    // RESIZER LOGIC
    let isResizing = false;

    resizer.addEventListener('mousedown', function(e) {
        isResizing = true;
        document.body.style.cursor = 'col-resize';
        document.body.style.userSelect = 'none';
    });

    document.addEventListener('mousemove', function(e) {
        if (!isResizing) return;

        const containerRect = workspace.getBoundingClientRect();
        const relativeX = e.clientX - containerRect.left;
        const percentage = (relativeX / containerRect.width) * 100;

        if (percentage > 20 && percentage < 80) {
            leftSide.style.flex = `1 1 ${percentage}%`;
            rightSide.style.flex = `1 1 ${100 - percentage}%`;
        }
    });

    document.addEventListener('mouseup', function() {
        if (isResizing) {
            isResizing = false;
            document.body.style.cursor = 'default';
            document.body.style.userSelect = 'auto';
        }
    });

    // TOGGLE FULL VIEW LOGIC
    btnToggle.addEventListener('click', function() {
        workspace.classList.toggle('workspace-full-left');
        const icon = btnToggle.querySelector('i');
        if (workspace.classList.contains('workspace-full-left')) {
            icon.classList.replace('bi-arrows-angle-expand', 'bi-arrows-angle-contract');
        } else {
            icon.classList.replace('bi-arrows-angle-contract', 'bi-arrows-angle-expand');
        }
    });

    // AUTO SCORE CALCULATION
    const scoringSelects = document.querySelectorAll('.scoring-select');
    const totalScoreDisplay = document.getElementById('totalScore');

    function calculateTotal() {
        let totalWeighted = 0;
        let totalWeight = 0;

        scoringSelects.forEach(select => {
            const weight = parseFloat(select.dataset.weight || 0);
            const score = parseFloat(select.value || 0);
            
            totalWeighted += (score * weight);
            totalWeight += weight;
        });

        if (totalWeight > 0) {
            // Skala 100 (Max score 5)
            const normalized = (totalWeighted / (totalWeight * 5)) * 100;
            totalScoreDisplay.textContent = normalized.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
    }

    scoringSelects.forEach(select => {
        select.addEventListener('change', calculateTotal);
    });
});
</script>
<?= $this->endSection() ?>
