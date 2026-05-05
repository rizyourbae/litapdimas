<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Litapdimas') ?> - Sistem Informasi Penelitian & Pengabdian</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --primary-emerald: #064e3b;
            --primary-light: #065f46;
            --accent-gold: #fbbf24;
            --bg-soft: #f8fafc;
            --text-dark: #1e293b;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: white;
            color: var(--text-dark);
            overflow-x: hidden;
        }

        h1, h2, h3, h4, .navbar-brand {
            font-family: 'Outfit', sans-serif;
        }

        /* --- NAVBAR --- */
        .navbar {
            padding: 1.5rem 0;
            transition: all 0.3s ease;
            background: transparent;
        }
        
        .navbar.scrolled {
            padding: 0.8rem 0;
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            letter-spacing: -0.5px;
            color: white !important;
        }

        .navbar.scrolled .navbar-brand {
            color: var(--primary-emerald) !important;
        }

        .nav-link {
            font-weight: 500;
            color: rgba(255,255,255,0.85) !important;
            margin: 0 0.5rem;
            transition: all 0.2s;
        }

        .navbar.scrolled .nav-link {
            color: var(--text-dark) !important;
        }

        .nav-link:hover {
            color: white !important;
            transform: translateY(-1px);
        }

        .navbar.scrolled .nav-link:hover {
            color: var(--primary-emerald) !important;
        }

        .btn-login {
            background: rgba(255,255,255,0.15);
            color: white !important;
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 50px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            backdrop-filter: blur(5px);
        }

        .navbar.scrolled .btn-login {
            background: var(--bg-soft);
            color: var(--primary-emerald) !important;
            border-color: #e2e8f0;
        }

        /* --- HERO --- */
        .hero-section {
            background: linear-gradient(135deg, rgba(6, 78, 59, 0.9) 0%, rgba(6, 95, 70, 0.8) 100%), 
                        url('<?= base_url('assets/adminlte/assets/img/logo/UINSI.jpeg') ?>');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            padding: 200px 0 160px;
            position: relative;
            color: white;
            clip-path: ellipse(150% 100% at 50% 0%);
        }

        .hero-content h1 {
            font-weight: 800;
            font-size: clamp(2.5rem, 5vw, 4rem);
            line-height: 1.1;
            margin-bottom: 1.5rem;
            letter-spacing: -1px;
        }

        .hero-content p {
            font-size: 1.2rem;
            opacity: 0.9;
            max-width: 600px;
            margin-bottom: 2.5rem;
            line-height: 1.6;
        }

        .btn-cta-primary {
            background: var(--accent-gold);
            color: #451a03;
            font-weight: 700;
            padding: 1rem 2.5rem;
            border-radius: 50px;
            box-shadow: 0 10px 25px rgba(251, 191, 36, 0.3);
            transition: all 0.3s;
            border: none;
        }

        .btn-cta-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(251, 191, 36, 0.4);
            background: #f59e0b;
        }

        .btn-cta-outline {
            background: transparent;
            color: white;
            font-weight: 600;
            padding: 1rem 2rem;
            border-radius: 50px;
            border: 2px solid rgba(255,255,255,0.3);
            margin-left: 1rem;
            transition: all 0.3s;
        }

        .btn-cta-outline:hover {
            background: rgba(255,255,255,0.1);
            border-color: white;
            color: white;
        }

        /* --- STATS CARDS --- */
        .stats-container {
            margin-top: -80px;
            position: relative;
            z-index: 10;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.5);
            border-radius: 24px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-10px);
            background: white;
            box-shadow: 0 30px 60px rgba(0,0,0,0.12);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: var(--bg-soft);
            color: var(--primary-emerald);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin: 0 auto 1.5rem;
        }

        .stat-number {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 2rem;
            display: block;
            color: var(--primary-emerald);
        }

        .stat-label {
            color: var(--text-muted);
            font-weight: 500;
            font-size: 0.9rem;
        }

        /* --- TIMELINE --- */
        .section-title {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-title h2 {
            font-weight: 800;
            color: var(--primary-emerald);
            margin-bottom: 1rem;
        }

        .section-title p {
            color: var(--text-muted);
            max-width: 600px;
            margin: 0 auto;
        }

        .timeline-wrapper {
            position: relative;
            padding: 2rem 0;
        }

        .timeline-line {
            position: absolute;
            top: 50px;
            left: 0;
            right: 0;
            height: 2px;
            background: #e2e8f0;
            z-index: 1;
        }

        .timeline-item {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .timeline-dot {
            width: 60px;
            height: 60px;
            background: white;
            border: 2px solid #e2e8f0;
            color: var(--text-muted);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin: 0 auto 1.5rem;
            transition: all 0.3s;
        }

        .timeline-item:hover .timeline-dot {
            border-color: var(--primary-emerald);
            color: var(--primary-emerald);
            transform: scale(1.1);
            box-shadow: 0 0 20px rgba(6, 78, 59, 0.2);
        }

        .timeline-item h5 {
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .timeline-item p {
            font-size: 0.85rem;
            color: var(--text-muted);
            padding: 0 1rem;
        }

        /* --- THEME CARDS --- */
        .theme-card {
            border: none;
            border-radius: 20px;
            background: var(--bg-soft);
            transition: all 0.3s;
            padding: 1.5rem;
        }

        .theme-card:hover {
            background: white;
            box-shadow: 0 15px 30px rgba(0,0,0,0.05);
            transform: translateY(-5px);
        }

        .theme-card h6 {
            font-weight: 700;
            margin: 0;
            color: var(--primary-emerald);
        }

        /* --- FOOTER --- */
        footer {
            background: #022c22;
            color: white;
            padding: 80px 0 40px;
        }

        footer h5 {
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--accent-gold);
        }

        .footer-link {
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            display: block;
            margin-bottom: 0.8rem;
            transition: all 0.2s;
        }

        .footer-link:hover {
            color: white;
            padding-left: 5px;
        }

        .social-btn {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-right: 0.5rem;
            transition: all 0.3s;
        }

        .social-btn:hover {
            background: var(--accent-gold);
            color: #451a03;
            transform: translateY(-3px);
        }

        /* Animations */
        .animate-up {
            animation: fadeInUp 0.8s ease-out forwards;
            opacity: 0;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .timeline-line { display: none; }
            .btn-cta-outline { margin-left: 0; margin-top: 1rem; width: 100%; }
            .btn-cta-primary { width: 100%; }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="<?= site_url() ?>">
                <img src="<?= base_url('assets/adminlte/assets/img/logo/logo-uinsi.png') ?>" alt="Logo UINSI" class="me-3" style="height: 50px; width: auto; filter: drop-shadow(0 0 10px rgba(0,0,0,0.1));">
                <div class="d-flex flex-column lh-1">
                    <span class="fs-4 fw-bold">LITAPDIMAS</span>
                    <span class="small opacity-75 fw-normal" style="font-size: 0.7rem; letter-spacing: 1px;">UINSI SAMARINDA</span>
                </div>
            </a>
            <button class="navbar-toggler shadow-none border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="bi bi-list text-white fs-1"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="<?= site_url() ?>">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#alur">Alur Pengajuan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tema">Tema Prioritas</a></li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-login" href="<?= site_url('login') ?>">
                            <i class="bi bi-person-circle me-2"></i>Area Peneliti
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-7 hero-content animate-up">
                        <span class="badge bg-warning text-dark fw-bold rounded-pill px-3 py-2 mb-3 shadow-sm">Portal Resmi Litapdimas 2024</span>
                        <h1><?= esc($hero['hero_title'] ?? 'Sistem Informasi Penelitian & Pengabdian Masyarakat') ?></h1>
                        <p><?= esc($hero['hero_subtitle'] ?? 'Platform terintegrasi untuk pengelolaan, review, dan publikasi hasil penelitian akademik.') ?></p>
                        <div class="d-flex flex-wrap">
                            <a href="<?= site_url('register') ?>" class="btn btn-cta-primary">
                                <i class="bi bi-rocket-takeoff me-2"></i><?= esc($hero['hero_cta_primary'] ?? 'Mulai Pengajuan') ?>
                            </a>
                            <a href="#" class="btn btn-cta-outline">
                                <i class="bi bi-journal-text me-2"></i><?= esc($hero['hero_cta_outline'] ?? 'Panduan Juknis') ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="stats-container container">
            <div class="row g-4 justify-content-center">
                <div class="col-md-4 col-lg-3 animate-up" style="animation-delay: 0.2s;">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="bi bi-file-earmark-text-fill"></i></div>
                        <span class="stat-number"><?= number_format($stats['total_proposal'] ?? 0, 0, ',', '.') ?>+</span>
                        <span class="stat-label">Proposal Masuk</span>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3 animate-up" style="animation-delay: 0.3s;">
                    <div class="stat-card border-warning" style="border-width: 2px !important;">
                        <div class="stat-icon text-warning"><i class="bi bi-people-fill"></i></div>
                        <span class="stat-number"><?= number_format($stats['total_peneliti'] ?? 0, 0, ',', '.') ?>+</span>
                        <span class="stat-label">Peneliti Aktif</span>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3 animate-up" style="animation-delay: 0.4s;">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="bi bi-journal-bookmark-fill"></i></div>
                        <span class="stat-number"><?= number_format($stats['total_publikasi'] ?? 0, 0, ',', '.') ?>+</span>
                        <span class="stat-label">Publikasi Terindeks</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Timeline Section -->
        <section class="py-5 mt-5 bg-white overflow-hidden" id="alur">
            <div class="container py-5">
                <div class="section-title animate-up">
                    <span class="text-primary fw-bold text-uppercase small" style="letter-spacing: 2px;">Step-by-Step</span>
                    <h2>Alur Pengajuan Bantuan</h2>
                    <p>Proses yang transparan dan terukur untuk memastikan kualitas riset terbaik.</p>
                </div>

                <div class="timeline-wrapper animate-up" style="animation-delay: 0.2s;">
                    <div class="timeline-line d-none d-lg-block"></div>
                    <div class="row g-4">
                        <div class="col-lg-3 col-md-6 timeline-item">
                            <div class="timeline-dot"><i class="bi bi-pencil-square"></i></div>
                            <h5>1. Pendaftaran</h5>
                            <p>Lengkapi profil peneliti dan unggah proposal sesuai template.</p>
                        </div>
                        <div class="col-lg-3 col-md-6 timeline-item">
                            <div class="timeline-dot"><i class="bi bi-search"></i></div>
                            <h5>2. Reviewer</h5>
                            <p>Proposal akan dinilai oleh pakar secara double-blind review.</p>
                        </div>
                        <div class="col-lg-3 col-md-6 timeline-item">
                            <div class="timeline-dot"><i class="bi bi-trophy"></i></div>
                            <h5>3. Penetapan</h5>
                            <p>Pengumuman proposal yang lolos pendanaan (nominator).</p>
                        </div>
                        <div class="col-lg-3 col-md-6 timeline-item">
                            <div class="timeline-dot"><i class="bi bi-file-check"></i></div>
                            <h5>4. Pelaporan</h5>
                            <p>Pelaksanaan kegiatan dan pengunggahan laporan hasil.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Themes Section -->
        <section class="py-5 bg-light" id="tema">
            <div class="container py-5">
                <div class="d-flex justify-content-between align-items-end mb-5 flex-wrap gap-3">
                    <div class="section-title text-start mb-0">
                        <span class="text-primary fw-bold text-uppercase small" style="letter-spacing: 2px;">Research Priorities</span>
                        <h2>Tema Prioritas Riset</h2>
                    </div>
                    <a href="#" class="btn btn-outline-primary rounded-pill px-4">Lihat Selengkapnya <i class="bi bi-arrow-right ms-2"></i></a>
                </div>

                <div class="row g-3">
                    <?php foreach ($temaRiset as $index => $t): ?>
                        <div class="col-lg-3 col-md-6 animate-up" style="animation-delay: <?= 0.1 * ($index + 1) ?>s;">
                            <div class="theme-card d-flex align-items-center gap-3">
                                <div class="fs-4"><i class="<?= esc($t['icon']) ?>"></i></div>
                                <h6><?= esc($t['nama']) ?></h6>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-5 bg-white">
            <div class="container py-5">
                <div class="bg-primary p-5 rounded-5 text-center text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #064e3b 0%, #065f46 100%) !important;">
                    <div class="position-absolute top-0 start-0 opacity-10" style="transform: translate(-20%, -20%) scale(2.5);">
                        <i class="bi bi-mortarboard" style="font-size: 15rem;"></i>
                    </div>
                    <div class="position-relative z-1">
                        <h2 class="fw-bold mb-3">Siap Untuk Berkontribusi?</h2>
                        <p class="opacity-75 mb-4 mx-auto" style="max-width: 600px;">Bergabunglah dengan ribuan peneliti lainnya dan wujudkan kontribusi nyata bagi masyarakat.</p>
                        <a href="<?= site_url('register') ?>" class="btn btn-warning btn-lg px-5 rounded-pill fw-bold">Daftar Sekarang</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center mb-4">
                        <img src="<?= base_url('assets/adminlte/assets/img/logo/logo-uinsi.png') ?>" alt="Logo UINSI" class="me-3" style="height: 60px;">
                        <img src="<?= base_url('assets/adminlte/assets/img/logo/logo_kemenag.png') ?>" alt="Logo Kemenag" style="height: 60px;">
                    </div>
                    <h5 class="mb-3 fw-bold text-white">LP2M UINSI Samarinda</h5>
                    <p class="text-white-50 mb-4">Lembaga Penelitian dan Pengabdian Kepada Masyarakat Universitas Islam Negeri Sultan Aji Muhammad Idris Samarinda.</p>
                    <div class="d-flex">
                        <a href="#" class="social-btn"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="social-btn"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="social-btn"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="social-btn"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4">
                    <h5>Layanan</h5>
                    <a href="#" class="footer-link">Penelitian</a>
                    <a href="#" class="footer-link">Pengabdian</a>
                    <a href="#" class="footer-link">Publikasi</a>
                    <a href="#" class="footer-link">HKI & Paten</a>
                </div>
                <div class="col-lg-2 col-md-4">
                    <h5>Tautan Cepat</h5>
                    <a href="#" class="footer-link">Panduan Juknis</a>
                    <a href="#" class="footer-link">Berita Terbaru</a>
                    <a href="#" class="footer-link">Cek Pengumuman</a>
                    <a href="#" class="footer-link">FAQ</a>
                </div>
                <div class="col-lg-4 col-md-4">
                    <h5>Kontak Kami</h5>
                    <p class="text-white-50 mb-2"><i class="bi bi-geo-alt me-2 text-warning"></i> Jl. Pendidikan No. 123, Kampus Utama</p>
                    <p class="text-white-50 mb-2"><i class="bi bi-telephone me-2 text-warning"></i> (021) 1234-5678</p>
                    <p class="text-white-50 mb-0"><i class="bi bi-envelope me-2 text-warning"></i> info@university.ac.id</p>
                </div>
            </div>
            <hr class="my-5 opacity-10">
            <div class="text-center text-white-50 small">
                <p class="mb-0">&copy; <?= date('Y') ?> Litapdimas. Dikembangkan untuk kemajuan riset Indonesia.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                document.getElementById('mainNav').classList.add('scrolled');
            } else {
                document.getElementById('mainNav').classList.remove('scrolled');
            }
        });

        // Simple Reveal Animation
        const observerOptions = {
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = "1";
                    entry.target.style.transform = "translateY(0)";
                }
            });
        }, observerOptions);

        document.querySelectorAll('.animate-up').forEach(el => observer.observe(el));
    </script>
</body>

</html>