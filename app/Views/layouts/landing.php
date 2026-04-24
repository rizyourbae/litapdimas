<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Litapdimas') ?></title>
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #007bff, #28a745);
            color: white;
            padding: 40px 0;
            margin-bottom: 30px;
        }

        .theme-card {
            transition: 0.3s;
            cursor: pointer;
        }

        .theme-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .card-icon {
            font-size: 2rem;
        }
    </style>
</head>

<body>
    <!-- Navbar Publik -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="<?= site_url() ?>">
                <i class="bi bi-database"></i> Litapdimas
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="<?= site_url() ?>">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tema">Tema Prioritas</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= site_url('login') ?>">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Konten Utama -->
    <main>
        <!-- Hero / Search Section -->
        <section class="hero-section">
            <div class="container text-center">
                <h1 class="mb-3">Direktori Data Litapdimas</h1>
                <p class="lead">Sebelum mengusulkan bantuan, pastikan Anda membaca dan memahami juknis terkait bantuan yang akan diusulkan. Jadilah pengusul yang Cerdas dan Bijaksana</p>
                <div class="row justify-content-center mt-4">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Masukkan kata kunci pencarian (judul / abstrak bantuan)">
                            <button class="btn btn-light" type="button"><i class="bi bi-search"></i> Cari</button>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="badge bg-light text-dark me-2"><i class="bi bi-building"></i> PTKI</span>
                    <span class="badge bg-light text-dark me-2"><i class="bi bi-person"></i> Peneliti</span>
                    <span class="badge bg-light text-dark me-2"><i class="bi bi-check-circle"></i> Reviewer</span>
                    <span class="badge bg-light text-dark"><i class="bi bi-file-earmark"></i> Data Paten</span>
                </div>
            </div>
        </section>

        <!-- Kartu Bantuan -->
        <div class="container">
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="card theme-card text-center p-4">
                        <div class="card-body">
                            <i class="bi bi-search card-icon text-primary"></i>
                            <h5 class="card-title mt-2">Bantuan Penelitian</h5>
                            <p class="card-text">Cari proposal penelitian yang didanai.</p>
                            <a href="#" class="btn btn-outline-primary">Lihat</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card theme-card text-center p-4">
                        <div class="card-body">
                            <i class="bi bi-people card-icon text-success"></i>
                            <h5 class="card-title mt-2">Bantuan PKM</h5>
                            <p class="card-text">Pengabdian kepada Masyarakat.</p>
                            <a href="#" class="btn btn-outline-success">Lihat</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card theme-card text-center p-4">
                        <div class="card-body">
                            <i class="bi bi-journal card-icon text-warning"></i>
                            <h5 class="card-title mt-2">Bantuan Publikasi</h5>
                            <p class="card-text">Publikasi ilmiah hasil penelitian.</p>
                            <a href="#" class="btn btn-outline-warning">Lihat</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tema Prioritas Riset -->
            <h3 class="mb-4" id="tema">Tema Prioritas Riset Kemenag</h3>
            <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-3 mb-5">
                <?php
                $tema = [
                    ['id' => '01', 'nama' => 'Ekoteologi Pangan-Pertanian'],
                    ['id' => '02', 'nama' => 'Kurikulum Berbasis Cinta Energi'],
                    ['id' => '03', 'nama' => 'Moderasi Beragama'],
                    ['id' => '04', 'nama' => 'Digital Islam'],
                    ['id' => '05', 'nama' => 'Integrasi Islam dan Sains'],
                    ['id' => '06', 'nama' => 'Kajian Manuskrip dan Turats'],
                    ['id' => '07', 'nama' => 'Sekolah Rakyat'],
                    ['id' => '08', 'nama' => 'Pengalaman Perbicara'],
                ];
                foreach ($tema as $t):
                ?>
                    <div class="col">
                        <div class="card theme-card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><span class="badge bg-primary me-2"><?= $t['id'] ?></span><?= $t['nama'] ?></h5>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-light text-center py-3 mt-5">
        <div class="container">
            <span class="text-muted">&copy; <?= date('Y') ?> Litapdimas. All rights reserved.</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>