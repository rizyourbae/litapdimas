<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row g-3 admin-page">
    <div class="col-12">
        <div class="card admin-hero">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-4 align-items-lg-start">
                    <div>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge text-bg-light border px-3 py-2">Operasional Admin</span>
                            <span class="badge text-bg-primary px-3 py-2">Proposal</span>
                        </div>
                        <h2 class="h3 admin-hero__title mb-2"><?= esc($title) ?></h2>
                        <p class="admin-hero__subtitle mb-0">Buka ajuan yang sudah disubmit dosen, baca detail proposal, lalu tunjuk reviewer yang paling relevan dari panel admin.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php foreach ($metrics as $metric): ?>
        <div class="col-md-6 col-xl-3">
            <div class="card admin-metric-card h-100">
                <div class="card-body">
                    <div class="small text-uppercase text-muted mb-2"><?= esc($metric['label']) ?></div>
                    <div class="admin-metric-card__value <?= esc($metric['tone_class']) ?>"><?= esc($metric['value']) ?></div>
                    <div class="text-muted small"><?= esc($metric['caption']) ?></div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="col-12">
        <div class="card card-primary card-outline admin-table-card">
            <div class="card-header border-0 pb-0 d-flex justify-content-between align-items-center gap-2 flex-wrap">
                <h3 class="card-title mb-0">
                    <i class="bi bi-file-earmark-text me-2"></i>Daftar Ajuan Proposal
                </h3>
                <span class="badge text-bg-light border">Proposal non-draft</span>
            </div>
            <div class="card-body">
                <?php if (empty($tableRows)): ?>
                    <div class="admin-empty-state">
                        <i class="bi bi-inbox"></i>
                        <p class="mb-1 fw-semibold text-body-emphasis">Belum ada ajuan proposal yang perlu dikelola</p>
                        <small class="d-block">Proposal akan muncul di sini setelah dosen melakukan submit.</small>
                    </div>
                <?php else: ?>
                    <div class="dt-skeleton-wrap">
                        <div class="dt-skeleton-overlay" id="sk-admin-proposals">
                            <table class="table table-bordered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:60px"></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th style="width:100px"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ([75, 55, 65, 45, 70, 40] as $width): ?>
                                        <tr>
                                            <td><span class="skeleton-line mx-auto" style="width:24px"></span></td>
                                            <td><span class="skeleton-line" style="width:<?= $width ?>%"></span></td>
                                            <td><span class="skeleton-line" style="width:60%"></span></td>
                                            <td><span class="skeleton-line" style="width:50%;border-radius:20px;height:20px"></span></td>
                                            <td><span class="skeleton-line" style="width:42%"></span></td>
                                            <td><span class="skeleton-btn"></span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="dt-real-wrap" id="rw-admin-proposals">
                            <table id="dt-admin-proposals" class="table table-hover table-bordered align-middle w-100" data-admin-datatable data-admin-datatable-options='{"columnDefs":[{"orderable":false,"targets":[0,5]}]}' data-skeleton-id="sk-admin-proposals" data-real-wrap-id="rw-admin-proposals">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:60px" class="text-center">#</th>
                                        <th>Judul Proposal</th>
                                        <th>Pengusul</th>
                                        <th style="width:170px">Bidang Ilmu</th>
                                        <th style="width:165px">Status</th>
                                        <th style="width:120px" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tableRows as $row): ?>
                                        <tr>
                                            <td class="text-center"><?= esc((string) $row['number']) ?></td>
                                            <td>
                                                <a href="<?= esc($row['show_url']) ?>" class="text-decoration-none text-body-emphasis fw-semibold d-inline-block mb-1">
                                                    <?= esc($row['title']) ?>
                                                </a>
                                                <div class="small text-muted">Update terakhir <?= esc($row['updated_at_label']) ?></div>
                                            </td>
                                            <td>
                                                <div class="fw-semibold"><?= esc($row['owner_name']) ?></div>
                                                <div class="small text-muted"><?= esc($row['owner_email']) ?></div>
                                            </td>
                                            <td><?= esc($row['bidang_ilmu']) ?></td>
                                            <td>
                                                <div class="mb-2">
                                                    <span class="badge <?= esc($row['status_badge_class']) ?> px-3 py-2"><?= esc($row['status_label']) ?></span>
                                                </div>
                                                <div class="small text-muted"><?= esc((string) $row['reviewer_count']) ?> reviewer ditugaskan</div>
                                            </td>
                                            <td class="text-center">
                                                <a href="<?= esc($row['show_url']) ?>" class="btn btn-info btn-sm admin-icon-btn" title="Detail Proposal">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>