<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= esc($title) ?></h3>
                <div class="card-tools">
                    <?php if ($useModal): ?>
                        <button type="button" class="btn btn-primary btn-sm btn-tambah">
                            <i class="bi bi-plus"></i> Tambah <?= esc($title) ?>
                        </button>
                    <?php else: ?>
                        <a href="<?= $addUrl ?>" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus"></i> Tambah <?= esc($title) ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <?php foreach ($fields as $field): ?>
                                    <th><?= esc($field['label']) ?></th>
                                <?php endforeach; ?>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $index => $item): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <?php foreach ($fields as $field): ?>
                                        <td><?= esc($item[$field['name']] ?? '') ?></td>
                                    <?php endforeach; ?>
                                    <td>
                                        <?php if ($useModal): ?>
                                            <a href="#" class="btn btn-warning btn-sm btn-edit"
                                                data-id="<?= $item['id'] ?>"
                                                data-bs-toggle="modal" data-bs-target="#modalForm">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= $editUrl . $item['id'] ?>" class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (!empty($item['deleted_at'])): ?>
                                            <a href="<?= $restoreUrl . $item['id'] ?>" class="btn btn-success btn-sm">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= $deleteUrl . $item['id'] ?>"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Hapus data?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($useModal): ?>
    <!-- Modal Form -->
    <div class="modal fade" id="modalForm" tabindex="-1">
        <div class="modal-dialog">
            <form id="masterForm" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Tambah <?= esc($title) ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <?php foreach ($fields as $field): ?>
                            <div class="mb-3">
                                <label class="form-label"><?= esc($field['label']) ?></label>
                                <input type="<?= $field['type'] ?? 'text' ?>"
                                    name="<?= $field['name'] ?>"
                                    class="form-control"
                                    <?= !empty($field['required']) ? 'required' : '' ?>
                                    id="field_<?= $field['name'] ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('modalForm');
            const form = document.getElementById('masterForm');
            const modalTitle = document.getElementById('modalTitle');
            const jsonUrl = '<?= $jsonUrl ?>';

            // Tambah baru
            document.querySelector('.btn-tambah').addEventListener('click', function() {
                form.action = '<?= site_url($routePrefix . 'store') ?>';
                form._method.value = 'POST';
                modalTitle.innerText = 'Tambah <?= esc($title) ?>';
                // Reset form
                form.reset();
            });

            // Edit item — Event Delegation
            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.btn-edit');
                if (!btn) return;

                e.preventDefault();
                const id = btn.getAttribute('data-id');
                fetch(jsonUrl + id)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            alert('Data tidak ditemukan');
                            return;
                        }
                        form.action = '<?= site_url($routePrefix . 'update/') ?>' + id;
                        form._method.value = 'PUT';
                        modalTitle.innerText = 'Edit <?= esc($title) ?>';
                        // Isi field
                        <?php foreach ($fields as $field): ?>
                            document.getElementById('field_<?= $field['name'] ?>').value = data.<?= $field['name'] ?> || '';
                        <?php endforeach; ?>
                    });
            });
        });
    </script>
<?php endif; ?>
<?= $this->endSection() ?>