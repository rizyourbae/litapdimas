                    <?php if (($proposal['status'] ?? '') === 'approved'): ?>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h6 class="fw-bold mb-0">Logbook Penelitian</h6>
                                <p class="text-muted small mb-0">Catat setiap aktivitas penelitian yang Anda lakukan.</p>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm rounded-3 px-3" data-bs-toggle="modal" data-bs-target="#addLogbookModal">
                                <i class="bi bi-plus-lg me-1"></i>Tambah Logbook
                            </button>
                        </div>

                        <div class="table-responsive dosen-table-wrap">
                            <table class="table table-sm table-bordered align-middle summary-table mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-center" style="width: 50px;">No</th>
                                        <th style="width: 120px;">Tanggal</th>
                                        <th style="width: 150px;">Tempat</th>
                                        <th>Kegiatan (Teknik)</th>
                                        <th>Deskripsi</th>
                                        <th class="text-center" style="width: 120px;">Berkas</th>
                                        <th class="text-center" style="width: 80px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($proposal['logbooks'])): ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-5 text-muted">
                                                <i class="bi bi-journal-x fs-2 d-block mb-2"></i>
                                                Belum ada data logbook.
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($proposal['logbooks'] as $index => $log): ?>
                                            <tr>
                                                <td class="text-center"><?= $index + 1 ?></td>
                                                <td><?= format_indo($log['tanggal']) ?></td>
                                                <td><?= esc($log['tempat']) ?></td>
                                                <td>
                                                    <div class="fw-bold"><?= esc($log['nama_kegiatan']) ?></div>
                                                    <span class="badge bg-info-subtle text-info border border-info-subtle small"><?= esc($log['teknik']) ?></span>
                                                </td>
                                                <td><div class="small text-wrap" style="max-width: 300px;"><?= esc($log['deskripsi']) ?></div></td>
                                                <td class="text-center">
                                                    <?php if ($log['berkas_url']): ?>
                                                        <a href="<?= $log['berkas_url'] ?>" target="_blank" class="btn btn-xs btn-outline-primary py-0 px-2 small">
                                                            <i class="bi bi-file-earmark-arrow-down"></i> Lihat
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-muted small">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-link text-danger p-0 border-0" 
                                                        onclick="SwalDelete('<?= $log['delete_url'] ?>', 'Logbook <?= esc($log['tanggal']) ?>', 'Kegiatan: <?= esc($log['nama_kegiatan']) ?>')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="empty-pane">
                            <i class="bi bi-lock fs-1 mb-3 d-block text-muted"></i>
                            <h6 class="mb-2">Logbook Terkunci</h6>
                            <p class="mb-0">Fitur logbook hanya dapat diakses setelah proposal Anda disetujui oleh admin.</p>
                        </div>
                    <?php endif; ?>
