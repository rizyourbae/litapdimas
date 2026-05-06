<?php

namespace App\Services\Admin;

use App\Models\Proposal\ProposalPengajuan;
use App\Models\Proposal\KlasterBantuan;

class AnalyticsService
{
    protected $proposalModel;
    protected $klasterModel;

    public function __construct()
    {
        $this->proposalModel = new ProposalPengajuan();
        $this->klasterModel = new KlasterBantuan();
    }

    /**
     * Mendapatkan data distribusi status proposal untuk Donut Chart
     */
    public function getStatusDistribution(): array
    {
        $db = \Config\Database::connect();
        $data = $db->table('proposal_pengajuan')
            ->select('status, COUNT(*) as total')
            ->groupBy('status')
            ->get()
            ->getResultArray();

        $labels = [];
        $series = [];

        foreach ($data as $row) {
            $labels[] = ucfirst($row['status']);
            $series[] = (int) $row['total'];
        }

        return [
            'labels' => $labels,
            'series' => $series
        ];
    }

    /**
     * Mendapatkan tren pengajuan proposal 6 bulan terakhir untuk Area Chart
     */
    public function getProposalTrend(): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table('proposal_pengajuan');
        
        // Ambil data 6 bulan terakhir
        $data = $builder
            ->select("DATE_FORMAT(created_at, '%Y-%m') as sort_key, DATE_FORMAT(created_at, '%b %Y') as month, COUNT(*) as total")
            ->where('created_at >=', date('Y-m-d', strtotime('-6 months')))
            ->groupBy('sort_key, month')
            ->orderBy('sort_key', 'ASC')
            ->get()
            ->getResultArray();

        $categories = [];
        $seriesData = [];

        foreach ($data as $row) {
            $categories[] = $row['month'];
            $seriesData[] = (int) $row['total'];
        }

        return [
            'categories' => $categories,
            'series' => [
                [
                    'name' => 'Total Proposal',
                    'data' => $seriesData
                ]
            ]
        ];
    }

    /**
     * Mendapatkan sebaran proposal per Klaster Bantuan untuk Bar Chart
     */
    public function getClusterDistribution(): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table('proposal_pengajuan p');
        
        $data = $builder
            ->select('k.nama as klaster, COUNT(*) as total')
            ->join('klaster_bantuan k', 'k.id = p.klaster_bantuan_id')
            ->groupBy('k.nama')
            ->orderBy('total', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        $labels = [];
        $seriesData = [];

        foreach ($data as $row) {
            $labels[] = $row['klaster'];
            $seriesData[] = (int) $row['total'];
        }

        return [
            'labels' => $labels,
            'series' => [
                [
                    'name' => 'Jumlah Proposal',
                    'data' => $seriesData
                ]
            ]
        ];
    }
}
