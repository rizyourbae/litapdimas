<?php
/**
 * Component: Dosen Table Card
 * ==========================
 * Wrapper standar untuk menampilkan tabel data di modul dosen.
 * 
 * @var string $title        Judul tabel
 * @var string $total        Label total data (misal: "Total: 5")
 * @var string $tableId      ID elemen tabel
 * @var string $content      Isi tabel (biasanya <thead> dan <tbody>)
 * @var string|null $icon    Ikon di samping judul
 */
?>
<div class="card card-primary card-outline shadow-sm dosen-table-card">
    <div class="card-header border-0 pb-0">
        <div class="d-flex flex-column flex-md-row justify-content-between gap-2 align-items-md-center">
            <h3 class="card-title mb-0">
                <?php if (!empty($icon)): ?>
                    <i class="<?= esc($icon) ?> me-2"></i>
                <?php endif; ?>
                <?= esc($title) ?>
            </h3>
            <span class="badge text-bg-light border"><?= esc($total) ?></span>
        </div>
    </div>
    <div class="card-body">
        <div class="dt-skeleton-wrap">
            <div class="table-responsive dosen-table-wrap">
                <table id="<?= esc($tableId) ?>" class="table table-hover table-bordered align-middle mb-0 w-100" data-dosen-datatable>
                    <?= $content ?>
                </table>
            </div>
        </div>
    </div>
</div>
