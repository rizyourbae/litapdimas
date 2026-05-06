<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row g-4 admin-page">
    <div class="col-12 animate-fade-up">
        <?= view('components/ui-hero', [
            'type' => 'admin',
            'title' => 'Analisa & Statistik Data',
            'subtitle' => 'Visualisasi data riset dan pengabdian untuk monitoring performa sistem.',
            'badges' => [
                ['label' => 'Analytics', 'class' => 'text-bg-info shadow-sm'],
                ['label' => 'Real-time', 'class' => 'text-bg-light border']
            ]
        ]) ?>
    </div>

    <!-- Data Jembatan untuk JS (Zero Logic in View) -->
    <div id="analytics-data" 
         data-chart-status='<?= json_encode($chartData['status']) ?>'
         data-chart-trend='<?= json_encode($chartData['trend']) ?>'
         data-chart-cluster='<?= json_encode($chartData['cluster']) ?>'
         style="display: none;"></div>

    <div class="col-lg-8 animate-fade-up delay-1">
        <div class="card shadow-sm border-0 rounded-4 h-100">
            <div class="card-header bg-white border-0 py-3 px-4 mt-2">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="stat-icon-sm bg-primary-soft text-primary"><i class="bi bi-graph-up-arrow"></i></div>
                        <h6 class="fw-bold mb-0 text-dark">Tren Pengajuan Proposal</h6>
                    </div>
                    <span class="badge bg-light text-muted border small">6 Bulan Terakhir</span>
                </div>
            </div>
            <div class="card-body p-4">
                <div id="chart-proposal-trend" style="min-height: 350px;"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 animate-fade-up delay-2">
        <div class="card shadow-sm border-0 rounded-4 h-100">
            <div class="card-header bg-white border-0 py-3 px-4 mt-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="stat-icon-sm bg-info-soft text-info"><i class="bi bi-pie-chart-fill"></i></div>
                    <h6 class="fw-bold mb-0 text-dark">Distribusi Status</h6>
                </div>
            </div>
            <div class="card-body p-4">
                <div id="chart-status-distribution" style="min-height: 350px;"></div>
            </div>
        </div>
    </div>

    <div class="col-12 animate-fade-up delay-3">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-white border-0 py-3 px-4 mt-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="stat-icon-sm bg-success-soft text-success"><i class="bi bi-bar-chart-fill"></i></div>
                    <h6 class="fw-bold mb-0 text-dark">Top 5 Klaster Penelitian</h6>
                </div>
            </div>
            <div class="card-body p-4">
                <div id="chart-cluster-distribution" style="min-height: 350px;"></div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- ApexCharts Library -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<!-- Custom Analytics JS -->
<script src="<?= base_url('custom/js/admin-analytics.js') ?>"></script>
<?= $this->endSection() ?>
