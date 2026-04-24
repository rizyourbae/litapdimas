<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= $title ?></h3>
            </div>
            <form action="<?= $action ?>" method="post">
                <?= csrf_field() ?>
                <div class="card-body">
                    <?php foreach ($fields as $field): ?>
                        <div class="mb-3">
                            <label class="form-label"><?= $field['label'] ?></label>
                            <?php if ($field['type'] === 'dropdown'): ?>
                                <select name="<?= $field['name'] ?>" class="form-select" <?= !empty($field['required']) ? 'required' : '' ?>>
                                    <option value="">-- Pilih --</option>
                                    <?php
                                    $options = $dropdowns[$field['name']] ?? [];
                                    $selected = isset($item) ? $item[$field['name']] : '';
                                    foreach ($options as $opt): ?>
                                        <option value="<?= $opt['value'] ?>" <?= $selected == $opt['value'] ? 'selected' : '' ?>>
                                            <?= $opt['label'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <input type="<?= $field['type'] ?? 'text' ?>"
                                    name="<?= $field['name'] ?>"
                                    class="form-control"
                                    value="<?= isset($item) ? esc($item[$field['name']] ?? '') : '' ?>"
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