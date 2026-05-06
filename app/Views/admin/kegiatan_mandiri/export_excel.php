<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .border { border: 1px solid #000; }
        table { border-collapse: collapse; width: 100%; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <table>
        <tr>
            <td colspan="7" style="font-size: 16pt; font-weight: bold; text-align: center;">
                <?= strtoupper($title) ?>
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: center;">
                Dicetak pada: <?= $date ?>
            </td>
        </tr>
        <tr><td colspan="7"></td></tr>
        <thead>
            <tr>
                <th class="border" style="width: 50px;">No</th>
                <th class="border">Nama Dosen</th>
                <th class="border">Judul Kegiatan</th>
                <th class="border">Jenis Kegiatan</th>
                <th class="border">Klaster / Skala</th>
                <th class="border">Tahun</th>
                <th class="border">Sumber Dana</th>
                <th class="border">Besaran Dana</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($items)): ?>
                <tr>
                    <td colspan="7" class="border text-center">Tidak ada data</td>
                </tr>
            <?php else: ?>
                <?php foreach ($items as $index => $row): ?>
                    <tr>
                        <td class="border text-center"><?= $index + 1 ?></td>
                        <td class="border"><?= esc($row->nama_lengkap ?: $row->username) ?></td>
                        <td class="border"><?= esc($row->judul_kegiatan) ?></td>
                        <td class="border"><?= esc($row->jenis_kegiatan) ?></td>
                        <td class="border"><?= esc($row->klaster_skala_kegiatan) ?></td>
                        <td class="border text-center"><?= esc($row->tahun) ?></td>
                        <td class="border"><?= esc($row->sumber_dana ?: '-') ?></td>
                        <td class="border text-right">
                            <?= $row->besaran_dana ? 'Rp ' . number_format($row->besaran_dana, 0, ',', '.') : '-' ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
