<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card card-primary card-outline shadow-sm">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h3 class="card-title mb-0"><?= esc($title) ?></h3>
        <span class="badge text-bg-light border">Silahkan lengkapi dokumen anda</span>
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

        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0" id="kelengkapan-table">
                <thead class="table-light">
                    <tr>
                        <th style="width: 30%;">Jenis Dokumen</th>
                        <th style="width: 15%;">Status</th>
                        <th style="width: 40%;">File</th>
                        <th style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tableRows as $row): ?>
                        <tr>
                            <td>
                                <strong><?= esc($row['jenis_dokumen']) ?></strong>
                            </td>
                            <td>
                                <span class="badge text-bg-<?= esc($row['status_badge']) ?>">
                                    <?= esc($row['status']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($row['is_uploaded']): ?>
                                    <a href="<?= esc($row['dokumen_url']) ?>" target="_blank" class="text-success text-decoration-none">
                                        <i class="bi bi-file-earmark-check"></i>
                                        <?= esc($row['dokumen_label']) ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($row['is_uploaded']): ?>
                                    <a href="<?= esc($row['dokumen_url']) ?>" target="_blank" class="btn btn-sm btn-success" title="Lihat Dokumen">
                                        <i class="bi bi-eye"></i>
                                        <span class="d-none d-sm-inline">Lihat</span>
                                    </a>
                                <?php endif; ?>
                                <a href="<?= esc($row['edit_url']) ?>" class="btn btn-sm btn-warning" title="Upload/Edit Dokumen">
                                    <i class="bi bi-pencil"></i>
                                    <span class="d-none d-sm-inline">Ubah</span>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // DataTables intentionally disabled: use plain table to keep fixed rows visible without plugin dependency.
</script>
<?= $this->endSection() ?>