/**
 * Admin Analytics JS
 * =================
 * Menangani inisialisasi grafik menggunakan ApexCharts.
 * Logic dipisahkan sepenuhnya dari View.
 */
$(document).ready(function() {
    const dataContainer = document.getElementById('analytics-data');
    if (!dataContainer) return;

    // Ambil data dari atribut HTML
    const statusData = JSON.parse(dataContainer.getAttribute('data-chart-status'));
    const trendData = JSON.parse(dataContainer.getAttribute('data-chart-trend'));
    const clusterData = JSON.parse(dataContainer.getAttribute('data-chart-cluster'));

    // --- 1. Tren Proposal (Area Chart) ---
    const trendOptions = {
        series: trendData.series,
        chart: {
            type: 'area',
            height: 350,
            toolbar: { show: false },
            zoom: { enabled: false },
            fontFamily: 'Inter, sans-serif'
        },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 3 },
        colors: ['#0d6efd'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.45,
                opacityTo: 0.05,
                stops: [20, 100, 100, 100]
            }
        },
        xaxis: {
            categories: trendData.categories,
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: {
            labels: {
                formatter: function(val) { return val.toFixed(0); }
            }
        },
        grid: {
            borderColor: '#f1f1f1',
            strokeDashArray: 4
        },
        tooltip: {
            theme: 'light',
            x: { show: true },
            marker: { show: false }
        }
    };
    new ApexCharts(document.querySelector("#chart-proposal-trend"), trendOptions).render();

    // --- 2. Distribusi Status (Donut Chart) ---
    const statusOptions = {
        series: statusData.series,
        chart: {
            type: 'donut',
            height: 350,
            fontFamily: 'Inter, sans-serif'
        },
        labels: statusData.labels,
        colors: ['#0d6efd', '#ffc107', '#198754', '#dc3545', '#6c757d'],
        legend: {
            position: 'bottom',
            offsetY: 0
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '75%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total',
                            formatter: function (w) {
                                return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                            }
                        }
                    }
                }
            }
        },
        dataLabels: { enabled: false },
        stroke: { show: false }
    };
    new ApexCharts(document.querySelector("#chart-status-distribution"), statusOptions).render();

    // --- 3. Klaster Distribution (Bar Chart) ---
    const clusterOptions = {
        series: clusterData.series,
        chart: {
            type: 'bar',
            height: 350,
            toolbar: { show: false },
            fontFamily: 'Inter, sans-serif'
        },
        colors: ['#198754'],
        plotOptions: {
            bar: {
                borderRadius: 8,
                horizontal: true,
                barHeight: '45%'
            }
        },
        dataLabels: { enabled: false },
        xaxis: {
            categories: clusterData.labels,
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        grid: {
            borderColor: '#f1f1f1',
            strokeDashArray: 4,
            xaxis: { lines: { show: true } },
            yaxis: { lines: { show: false } }
        }
    };
    new ApexCharts(document.querySelector("#chart-cluster-distribution"), clusterOptions).render();
});
