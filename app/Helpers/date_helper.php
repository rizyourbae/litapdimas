<?php

if (!function_exists('format_indo')) {
    /**
     * Format tanggal ke bahasa Indonesia
     * 
     * @param string $date Tanggal (YYYY-MM-DD atau format lain yang bisa di-parse)
     * @param bool $showTime Sertakan waktu (HH:mm)
     * @param bool $showDay Sertakan nama hari
     * @return string
     */
    function format_indo($date, $showTime = false, $showDay = false): string
    {
        if (empty($date)) {
            return '-';
        }

        try {
            $dateTime = new DateTime($date);
        } catch (Exception $e) {
            return $date;
        }

        $hari = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu'
        ];

        $bulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        $numHari  = $dateTime->format('N');
        $tgl      = $dateTime->format('d');
        $numBulan = $dateTime->format('n');
        $thn      = $dateTime->format('Y');

        $result = $tgl . ' ' . $bulan[$numBulan] . ' ' . $thn;

        if ($showDay) {
            $result = $hari[$numHari] . ', ' . $result;
        }

        if ($showTime) {
            $result .= ' ' . $dateTime->format('H:i');
        }

        return $result;
    }
}

if (!function_exists('time_ago')) {
    /**
     * Format waktu "beberapa waktu yang lalu"
     */
    function time_ago($date): string
    {
        if (empty($date)) return '-';
        
        $time = is_numeric($date) ? $date : strtotime($date);
        $diff = time() - $time;

        if ($diff < 1) return 'baru saja';

        $intervals = [
            31536000 => 'tahun',
            2592000  => 'bulan',
            604800   => 'minggu',
            86400    => 'hari',
            3600     => 'jam',
            60       => 'menit',
            1        => 'detik'
        ];

        foreach ($intervals as $secs => $label) {
            $d = $diff / $secs;
            if ($d >= 1) {
                $r = round($d);
                return $r . ' ' . $label . ' yang lalu';
            }
        }

        return date('d/m/Y', $time);
    }
}
