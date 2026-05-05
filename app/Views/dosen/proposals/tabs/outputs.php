                    <?php if (($proposal['status'] ?? '') === 'approved'): ?>
                        <div class="alert alert-light border-0 bg-light rounded-4 p-4 mb-4">
                            <h6 class="fw-bold mb-3">Silahkan unggah berkas Luaran di sini.</h6>
                            <ol class="small text-muted mb-0 ps-3">
                                <li class="mb-1">File yang diperbolehkan hanya dalam format <span class="fw-bold">.pdf</span> dengan ukuran maksimal <span class="fw-bold">10MB</span>.</li>
                                <li class="mb-1">Luaran yang muncul sesuai dengan ceklist Anda saat mengajukan proposal ini.</li>
                                <li>Luaran dipresentasikan dalam seminar luaran sebagai bagian dari tahapan penilaian tahap akhir bantuan.</li>
                            </ol>
                        </div>

                        <div class="table-responsive dosen-table-wrap">
                            <table class="table table-bordered align-middle mb-0">
                                <thead class="bg-light text-center">
                                    <tr>
                                        <th class="py-3" style="width: 300px;">Nama Luaran</th>
                                        <th class="py-3">Berkas Tersimpan</th>
                                        <th class="py-3">Unggah di sini</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($proposal['outputs'] as $output): ?>
                                        <tr>
                                            <td class="fw-bold ps-4"><?= esc($output['kategori']) ?></td>
                                            <td class="text-center">
                                                <?php if ($output['file_url']): ?>
                                                    <a href="<?= $output['file_url'] ?>" target="_blank" class="btn btn-info btn-sm text-white px-3 rounded-2 shadow-sm">
                                                        <i class="bi bi-file-earmark-text me-1"></i>Lihat berkas
                                                    </a>
                                                    <div class="small text-muted mt-1" style="font-size: 0.65rem;">
                                                        Diunggah: <?= format_indo($output['uploaded_at'], true) ?>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-2">Belum ada berkas</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <form action="<?= site_url('dosen/proposals/outputs/upload/' . $proposal['uuid']) ?>" method="post" enctype="multipart/form-data" class="d-flex gap-2 px-3">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="kategori" value="<?= esc($output['kategori']) ?>">
                                                    <input type="file" name="berkas" class="form-control form-control-sm shadow-none" accept=".pdf" required>
                                                    <button type="submit" class="btn btn-success btn-sm px-2 shadow-sm">
                                                        <i class="bi bi-upload"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="empty-pane">
                            <i class="bi bi-lock fs-1 mb-3 d-block text-muted"></i>
                            <h6 class="mb-2">Luaran Terkunci</h6>
                            <p class="mb-0">Fitur luaran hanya dapat diakses setelah proposal Anda disetujui oleh admin.</p>
                        </div>
                    <?php endif; ?>
