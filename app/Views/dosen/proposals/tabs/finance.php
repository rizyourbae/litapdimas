                    <?php if (($proposal['status'] ?? '') === 'approved'): ?>
                        <div class="alert alert-light border-0 bg-light rounded-4 p-4 mb-4">
                            <h6 class="fw-bold mb-3">Silahkan unggah berkas Laporan Kegiatan Penelitian di sini.</h6>
                            <ol class="small text-muted mb-0 ps-3">
                                <li class="mb-1">File yang diperbolehkan hanya dalam format <span class="fw-bold">.pdf</span> dengan ukuran maksimal <span class="fw-bold">10MB</span>.</li>
                                <li class="mb-1">Laporan Antara/Progres dapat berisi laporan perkembangan kegiatan bantuan ataupun revisi proposal berdasarkan masukan reviewer.</li>
                                <li class="mb-1">Laporan keuangan disusun dengan mengacu kepada SBM & SBK Kemenkeu yang berlaku pada tahun pelaksanaan.</li>
                                <li class="mb-1">Laporan keuangan memuat Cash Flow dan bukti transaksi terscan, Laporan keuangan sementara dilaporkan dalam seminar luaran.</li>
                                <li>Laporan akhir merupakan laporan final berdasarkan hasil review seminar luaran, berisi <span class="fw-bold">Laporan Keuangan</span> dan <span class="fw-bold">Laporan Akademik</span> final.</li>
                            </ol>
                        </div>

                        <!-- 1. Laporan Antara / Progress -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3"><i class="bi bi-arrow-right-circle me-2 text-primary"></i>Laporan Antara / Progress</h6>
                            <div class="table-responsive dosen-table-wrap">
                                <table class="table table-bordered align-middle mb-0">
                                    <thead class="bg-light text-center">
                                        <tr>
                                            <th class="py-3" style="width: 300px;">Nama Laporan</th>
                                            <th class="py-3">Berkas Tersimpan</th>
                                            <th class="py-3">Unggah di sini</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $report = $proposal['reports']['Laporan Antara']; ?>
                                        <tr>
                                            <td class="fw-bold ps-4">Laporan Antara</td>
                                            <td class="text-center">
                                                <?php if ($report['file_url']): ?>
                                                    <a href="<?= $report['file_url'] ?>" target="_blank" class="btn btn-info btn-sm text-white px-3 rounded-2 shadow-sm">
                                                        <i class="bi bi-file-earmark-text me-1"></i>Lihat berkas
                                                    </a>
                                                    <div class="small text-muted mt-1" style="font-size: 0.65rem;">
                                                        Diunggah: <?= format_indo($report['uploaded_at'], true) ?>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-2">Belum ada berkas</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <form action="<?= site_url('dosen/proposals/reports/upload/' . $proposal['uuid']) ?>" method="post" enctype="multipart/form-data" class="d-flex gap-2 px-3">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="kategori" value="Laporan Antara">
                                                    <input type="file" name="berkas" class="form-control form-control-sm shadow-none" accept=".pdf" required>
                                                    <button type="submit" class="btn btn-success btn-sm px-2 shadow-sm">
                                                        <i class="bi bi-upload"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- 2. Laporan Keuangan Sementara -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3"><i class="bi bi-cash-stack me-2 text-primary"></i>Laporan Keuangan Sementara</h6>
                            <div class="table-responsive dosen-table-wrap">
                                <table class="table table-bordered align-middle mb-0">
                                    <thead class="bg-light text-center">
                                        <tr>
                                            <th class="py-3">Usulan Biaya</th>
                                            <th class="py-3">Biaya Disetujui</th>
                                            <th class="py-3">Berkas Tersimpan</th>
                                            <th class="py-3">Unggah di sini</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $report = $proposal['reports']['Laporan Keuangan Sementara']; ?>
                                        <tr>
                                            <td class="text-center fw-bold">Rp. <?= $proposal['finance']['proposed_formatted'] ?></td>
                                            <td class="text-center fw-bold text-success">
                                                Rp. <?= $proposal['finance']['approved_amount'] > 0 ? $proposal['finance']['approved_formatted'] : '-' ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($report['file_url']): ?>
                                                    <a href="<?= $report['file_url'] ?>" target="_blank" class="btn btn-info btn-sm text-white px-3 rounded-2 shadow-sm">
                                                        <i class="bi bi-file-earmark-text me-1"></i>Lihat berkas
                                                    </a>
                                                    <div class="small text-muted mt-1" style="font-size: 0.65rem;">
                                                        Diunggah: <?= format_indo($report['uploaded_at'], true) ?>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-2">Belum ada berkas</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <form action="<?= site_url('dosen/proposals/reports/upload/' . $proposal['uuid']) ?>" method="post" enctype="multipart/form-data" class="d-flex gap-2 px-3">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="kategori" value="Laporan Keuangan Sementara">
                                                    <input type="file" name="berkas" class="form-control form-control-sm shadow-none" accept=".pdf" required>
                                                    <button type="submit" class="btn btn-success btn-sm px-2 shadow-sm">
                                                        <i class="bi bi-upload"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- 3. Laporan Akhir -->
                        <div class="mb-0">
                            <h6 class="fw-bold mb-3"><i class="bi bi-flag-fill me-2 text-primary"></i>Laporan Akhir</h6>
                            <div class="table-responsive dosen-table-wrap">
                                <table class="table table-bordered align-middle mb-0">
                                    <thead class="bg-light text-center">
                                        <tr>
                                            <th class="py-3" style="width: 300px;">Nama Laporan</th>
                                            <th class="py-3">Berkas Tersimpan</th>
                                            <th class="py-3">Unggah di sini</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $akhirCategories = ['Laporan Akademik', 'Laporan Keuangan'];
                                        foreach ($akhirCategories as $kat): 
                                            $report = $proposal['reports'][$kat];
                                        ?>
                                        <tr>
                                            <td class="fw-bold ps-4"><?= $kat ?></td>
                                            <td class="text-center">
                                                <?php if ($report['file_url']): ?>
                                                    <a href="<?= $report['file_url'] ?>" target="_blank" class="btn btn-info btn-sm text-white px-3 rounded-2 shadow-sm">
                                                        <i class="bi bi-file-earmark-text me-1"></i>Lihat berkas
                                                    </a>
                                                    <div class="small text-muted mt-1" style="font-size: 0.65rem;">
                                                        Diunggah: <?= format_indo($report['uploaded_at'], true) ?>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-2">Belum ada berkas</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <form action="<?= site_url('dosen/proposals/reports/upload/' . $proposal['uuid']) ?>" method="post" enctype="multipart/form-data" class="d-flex gap-2 px-3">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="kategori" value="<?= $kat ?>">
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
                        </div>

                    <?php else: ?>
                        <div class="empty-pane">
                            <i class="bi bi-lock fs-1 mb-3 d-block text-muted"></i>
                            <h6 class="mb-2">Laporan Terkunci</h6>
                            <p class="mb-0">Fitur laporan hanya dapat diakses setelah proposal Anda disetujui oleh admin.</p>
                        </div>
                    <?php endif; ?>
