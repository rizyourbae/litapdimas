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
            <td colspan="6" style="font-size: 16pt; font-weight: bold; text-align: center;">
                <?= strtoupper($title) ?>
            </td>
        </tr>
        <tr>
            <td colspan="6" style="text-align: center;">
                Dicetak pada: <?= $date ?>
            </td>
        </tr>
        <tr><td colspan="6"></td></tr>
        <thead>
            <tr>
                <th class="border" style="width: 50px;">No</th>
                <th class="border">Nama Dosen</th>
                <th class="border">Judul Publikasi</th>
                <th class="border">Jenis</th>
                <th class="border">Tahun</th>
                <th class="border">Penulis</th>
                <th class="border">Sumber Pembiayaan</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($items)): ?>
                <tr>
                    <td colspan="6" class="border text-center">Tidak ada data</td>
                </tr>
            <?php else: ?>
                <?php foreach ($items as $index => $row): ?>
                    <tr>
                        <td class="border text-center"><?= $index + 1 ?></td>
                        <td class="border"><?= esc($row->nama_dosen) ?></td>
                        <td class="border"><?= esc($row->judul) ?></td>
                        <td class="border"><?= esc($row->jenis_publikasi) ?></td>
                        <td class="border text-center"><?= esc($row->tahun) ?></td>
                        <td class="border"><?= esc($row->penulis ?: '-') ?></td>
                        <td class="border"><?= esc($row->sumber_pembiayaan ?: '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
