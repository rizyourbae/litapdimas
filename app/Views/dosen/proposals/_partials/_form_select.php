<?php
/**
 * @var string $name
 * @var string $label
 * @var array $options
 * @var mixed $selected
 * @var string $placeholder
 * @var bool $required
 */
?>
<div class="col-md-6">
    <label for="<?= esc($name) ?>" class="form-label"><?= esc($label) ?> <?php if ($required ?? true): ?><span class="text-danger">*</span><?php endif; ?></label>
    <select class="form-select" id="<?= esc($name) ?>" name="<?= esc($name) ?>" <?php if ($required ?? true): ?>required<?php endif; ?>>
        <option value=""><?= esc($placeholder ?? '-- Pilih ' . $label . ' --') ?></option>
        <?php foreach ($options ?? [] as $opt): ?>
            <option value="<?= esc($opt['id']) ?>" <?= (int) ($selected ?? 0) === (int) $opt['id'] ? 'selected' : '' ?>>
                <?= esc($opt['nama']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>
