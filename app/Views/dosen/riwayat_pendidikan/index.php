<?php
/** @var string $title */
/** @var array<int,array<string,mixed>> $tableRows */

$this->extend('layouts/main');

$this->section('content');
?>

<div class="row g-4 admin-page">
    <div class="col-12 animate-fade-up">
        <?= view('components/ui-hero', [
            'type' => 'dosen',
            'title' => $title ?? 'Riwayat Pendidikan Saya',
            'subtitle' => 'Kelola jenjang pendidikan, program studi, dan dokumen ijazah dengan tampilan yang lebih rapi.',
            'badges' => [
                ['label' => 'Riwayat Pendidikan', 'class' => 'text-bg-light border shadow-sm'],
                ['label' => 'Akademik', 'class' => 'text-bg-primary shadow-sm']
            ],
            'actions' => [
                [
                    'label' => 'Tambah Riwayat',
                    'class' => 'btn btn-primary rounded-pill px-4 shadow-sm',
                    'icon' => 'bi bi-plus-lg',
                    'url' => site_url('dosen/riwayat-pendidikan/create')
                ]
            ]
        ]) ?>
    </div>

    <div class="col-12 animate-fade-up delay-1">
        <?php if (empty($tableRows)): ?>
            <?= view('components/ui-empty-state', [
                'icon' => 'bi bi-mortarboard-fill',
                'title' => 'Belum Ada Riwayat Pendidikan',
                'desc' => 'Daftar riwayat pendidikan Anda masih kosong. Silakan tambahkan data pendidikan Anda.',
                'action_label' => 'Tambah Riwayat Pertama',
                'action_url' => site_url('dosen/riwayat-pendidikan/create'),
                'action_icon' => 'bi bi-plus-lg'
            ]) ?>
        <?php else: ?>
            <?php ob_start(); ?>
            <thead>
                <tr>
                    <th style="width: 60px" class="text-center py-3">#</th>
                    <th class="py-3">Jenjang</th>
                    <th class="py-3">Program Studi</th>
                    <th class="py-3">Institusi</th>
                    <th style="width: 90px" class="text-center py-3">Masuk</th>
                    <th style="width: 90px" class="text-center py-3">Lulus</th>
                    <th style="width: 70px" class="text-center py-3">IPK</th>
                    <th style="width: 140px" class="text-center py-3">Dokumen</th>
                    <th style="width: 120px" class="text-center py-3">Aksi</th>
                </tr>
            </thead>
            <?php $header = ob_get_clean(); ?>

            <?php ob_start(); ?>
            <?php foreach ($tableRows as $index => $row): ?>
                <tr>
                    <td class="text-center text-muted small"><?= $index + 1 ?></td>
                    <td>
                        <span class="badge bg-info-soft text-info rounded-pill px-3 py-2 small fw-bold">
                            <?= esc((string) $row['jenjang']) ?>
                        </span>
                    </td>
                    <td><div class="fw-bold text-primary lh-base"><?= esc((string) $row['program_studi']) ?></div></td>
                    <td><div class="text-dark fw-medium"><?= esc((string) $row['institusi']) ?></div></td>
                    <td class="text-center text-muted"><?= esc((string) $row['tahun_masuk']) ?></td>
                    <td class="text-center fw-bold text-dark"><?= esc((string) $row['tahun_lulus']) ?></td>
                    <td class="text-center fw-bold text-success"><?= esc((string) $row['ipk']) ?></td>
                    <td class="text-center">
                        <?php if ($row['dokumen_url']): ?>
                            <a href="<?= esc((string) $row['dokumen_url']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 small">
                                <i class="bi bi-file-earmark-pdf-fill me-1"></i>Ijazah
                            </a>
                        <?php else: ?>
                            <span class="badge bg-light text-muted border fw-normal">Tidak ada file</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <a href="<?= esc((string) $row['edit_url']) ?>" class="btn-action-sm bg-warning-soft text-warning shadow-sm" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <button class="btn-action-sm bg-danger-soft text-danger shadow-sm btn-delete" title="Hapus"
                                data-href="<?= esc((string) $row['delete_url']) ?>"
                                data-delete-label="riwayat pendidikan ini"
                                data-delete-desc="Data yang dihapus tidak dapat dikembalikan.">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php $body = ob_get_clean(); ?>

            <?= view('components/ui-table-card', [
                'title' => 'Daftar Riwayat Pendidikan',
                'badge' => count($tableRows) . ' Data',
                'tableId' => 'dt-riwayat-pendidikan',
                'header' => $header,
                'body' => $body,
                'icon' => 'bi bi-mortarboard-fill'
            ]) ?>
        <?php endif; ?>
    </div>
</div>

<?php $this->endSection(); ?>