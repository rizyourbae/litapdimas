                    <?php if (($proposal['status'] ?? '') === 'approved'): ?>
                        <!-- Jurnal Section -->
                        <div class="mb-5">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold mb-0">Publikasi sebagai Artikel Jurnal</h6>
                                <button type="button" class="btn btn-info btn-sm text-white px-3 rounded-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modal-add-jurnal">
                                    <i class="bi bi-plus-lg me-1"></i>Tambah
                                </button>
                            </div>
                            <div class="table-responsive dosen-table-wrap">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light text-center">
                                        <tr>
                                            <th class="py-3">Judul Artikel</th>
                                            <th class="py-3">Nama Jurnal</th>
                                            <th class="py-3" style="width: 80px;">Hapus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($proposal['outcomes']['journals'])): ?>
                                            <tr>
                                                <td colspan="3" class="text-center py-4 text-muted small">Maaf, belum ada data publikasi dalam bentuk artikel jurnal.</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($proposal['outcomes']['journals'] as $jurnal): ?>
                                                <tr>
                                                    <td class="ps-3 small"><?= esc($jurnal['judul']) ?></td>
                                                    <td class="text-center">
                                                        <a href="https://<?= esc($jurnal['url']) ?>" target="_blank" class="text-decoration-none">
                                                            <?= esc($jurnal['nama_penerbit_jurnal']) ?>
                                                            <div class="small text-muted"><?= esc($jurnal['volume_nomor']) ?></div>
                                                        </a>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-danger btn-sm px-2" onclick="SwalDelete('<?= site_url('dosen/proposals/outcomes/delete/' . $jurnal['uuid']) ?>', 'Outcome ini')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Buku Section -->
                        <div class="mb-5">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold mb-0">Publikasi sebagai Buku</h6>
                                <button type="button" class="btn btn-info btn-sm text-white px-3 rounded-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modal-add-buku">
                                    <i class="bi bi-plus-lg me-1"></i>Tambah
                                </button>
                            </div>
                            <div class="table-responsive dosen-table-wrap">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light text-center">
                                        <tr>
                                            <th class="py-3">Judul Buku</th>
                                            <th class="py-3">Penerbit</th>
                                            <th class="py-3" style="width: 80px;">Hapus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($proposal['outcomes']['books'])): ?>
                                            <tr>
                                                <td colspan="3" class="text-center py-4 text-muted small">Maaf, belum ada data publikasi dalam bentuk buku.</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($proposal['outcomes']['books'] as $buku): ?>
                                                <tr>
                                                    <td class="ps-3 small"><?= esc($buku['judul']) ?></td>
                                                    <td class="text-center small">
                                                        <?= esc($buku['nama_penerbit_jurnal']) ?>
                                                        <div class="text-muted">ISBN: <?= esc($buku['isbn']) ?> (<?= esc($buku['tahun_terbit']) ?>)</div>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-danger btn-sm px-2" onclick="SwalDelete('<?= site_url('dosen/proposals/outcomes/delete/' . $buku['uuid']) ?>', 'Outcome ini')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Notes Section -->
                        <div class="mt-4 p-4 bg-light rounded-4 border-0">
                            <h6 class="fw-bold mb-2">Catatan validator outcome :</h6>
                            <p class="mb-0 text-secondary"><?= $proposal['admin_decision']['outcome_notes'] ?: 'Belum ada catatan dari validator.' ?></p>
                        </div>
                    <?php else: ?>
                        <div class="empty-pane">
                            <i class="bi bi-lock fs-1 mb-3 d-block text-muted"></i>
                            <h6 class="mb-2">Outcomes Terkunci</h6>
                            <p class="mb-0">Fitur hasil penelitian hanya dapat diakses setelah proposal Anda disetujui oleh admin.</p>
                        </div>
                    <?php endif; ?>
