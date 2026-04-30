<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php
/** @var string $title */
/** @var string $action */
/** @var array<int, array<string,mixed>> $fields */
/** @var array<string, array<int, array<string,mixed>>> $dropdowns */
/** @var array<string,mixed> $item */
/** @var string $routePrefix */
?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= esc((string) $title) ?></h3>
            </div>
            <form action="<?= esc((string) $action) ?>" method="post">
                <?= csrf_field() ?>
                <div class="card-body">
                    <?php foreach ((array) $fields as $field): ?>
                        <?php
                        $field = (array) $field;
                        $fName = (string) ($field['name'] ?? '');
                        $fType = (string) ($field['type'] ?? 'text');
                        $fLabel = (string) ($field['label'] ?? '');
                        ?>
                        <div class="mb-3">
                            <label class="form-label"><?= esc($fLabel) ?></label>
                            <?php if ($fType === 'dropdown'): ?>
                                <?php $options = $dropdowns[$fName] ?? []; ?>
                                <?php $selected = (string) ($item[$fName] ?? ''); ?>
                                <select name="<?= esc($fName) ?>" class="form-select" <?= !empty($field['required']) ? 'required' : '' ?>>
                                    <option value="">-- Pilih --</option>
                                    <?php foreach ((array) $options as $opt): $opt = (array) $opt; ?>
                                        <option value="<?= esc((string) ($opt['value'] ?? '')) ?>" <?= $selected == ($opt['value'] ?? '') ? 'selected' : '' ?>>
                                            <?= esc((string) ($opt['label'] ?? '')) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <input type="<?= esc($fType) ?>"
                                    name="<?= esc($fName) ?>"
                                    class="form-control"
                                    value="<?= esc((string) ($item[$fName] ?? '')) ?>"
                                    <?= !empty($field['required']) ? 'required' : '' ?>>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="card-footer text-end">
                    <a href="<?= site_url($routePrefix) ?>" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>