<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php $tableRows = $proposals ?? []; ?>

<div class="row g-3">
    <div class="col-12">
        <div class="card dosen-hero">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-start">
                    <div>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge text-bg-light border px-3 py-2">Aktivitas Dosen</span>
                            <span class="badge text-bg-primary px-3 py-2">Proposal</span>
                        </div>
                        <h2 class="h3 dosen-hero__title mb-2"><?= esc($title ?? 'Proposal Saya') ?></h2>
                        <p class="dosen-hero__subtitle mb-0">Kelola proposal Anda dalam tampilan tabel yang sederhana, konsisten, dan mudah dipindai.</p>
                    </div>

                    <div class="dosen-hero__actions d-flex flex-wrap gap-2">
                        <a href="<?= site_url('dosen/proposals/create') ?>" class="btn btn-primary">
                            <i class="bi bi-plus-lg me-1"></i>Tambah Proposal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card card-primary card-outline shadow-sm dosen-table-card">
            <div class="card-header">
                <div class="d-flex flex-column flex-md-row justify-content-between gap-2 align-items-md-center">
                    <div>
                        <h3 class="card-title mb-1">Daftar Proposal</h3>
                    </div>
                    <span class="badge text-bg-light border">Total data: <?= esc(count($tableRows)) ?></span>
                </div>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-1"></i>
                        <?= esc(session()->getFlashdata('success')) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                        <?= esc(session()->getFlashdata('error')) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (empty($tableRows)): ?>
                    <div class="dosen-empty-state">
                        <i class="bi bi-folder2-open"></i>
                        <h4 class="h5 text-body mb-2">Belum ada data proposal</h4>
                        <p class="mb-0">Klik tombol Tambah Proposal untuk memulai pengajuan pertama Anda.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive dosen-table-wrap">
                        <table id="dt-proposal-dosen" class="table table-striped table-hover table-bordered align-middle mb-0 w-100" data-dosen-datatable>
                            <thead class="table-light">
                                <tr>
                                    <th style="width:60px" class="text-center">#</th>
                                    <th>Judul Proposal</th>
                                    <th style="width:150px" class="text-center">Status</th>
                                    <th style="width:140px" class="text-center">Dibuat</th>
                                    <th style="width:110px" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tableRows as $index => $row): ?>
                                    <tr>
                                        <td class="text-center"><?= $index + 1 ?></td>
                                        <td>
                                            <a href="<?= esc($row['show_url']) ?>" class="text-decoration-none fw-semibold text-body-emphasis">
                                                <?= esc($row['judul']) ?>
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge <?= esc($row['status_badge_class']) ?>">
                                                <?= esc($row['status_label']) ?>
                                            </span>
                                        </td>
                                        <td class="text-center"><?= esc($row['created_at_formatted']) ?></td>
                                        <td class="text-center">
                                            <div class="dosen-action-group">
                                                <a href="<?= esc($row['show_url']) ?>" class="btn btn-info dosen-icon-btn" title="Detail">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="<?= esc($row['edit_url']) ?>" class="btn btn-warning dosen-icon-btn" title="Edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <button class="btn btn-danger dosen-icon-btn btn-delete" title="Hapus"
                                                    data-href="<?= esc($row['delete_url']) ?>"
                                                    data-delete-label="proposal ini"
                                                    data-delete-desc="Data yang dihapus tidak dapat dikembalikan.">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>