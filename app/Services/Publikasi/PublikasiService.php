<?php

namespace App\Services\Publikasi;

use App\Models\Publikasi\PublikasiModel;
use Exception;
use Ramsey\Uuid\Uuid;

class PublikasiService
{
    protected $publikasiModel;

    private const ID_KEY_PREFIX = 'id-';

    public function __construct()
    {
        $this->publikasiModel = new PublikasiModel();
    }

    public function simpanPublikasi(array $data)
    {
        // 1. Ekstrak data utama sesuai gambar 1
        $mainData = [
            'user_id'         => $data['user_id'],
            'judul'           => $data['judul'],
            'penulis'         => $data['penulis'] ?? null,
            'klaster'         => $data['klaster'] ?? null,
            'jenis_publikasi' => $data['jenis_publikasi'],
            'tahun'           => $data['tahun'],
        ];

        // 2. Logic Sumber Pembiayaan
        if ($data['jenis_publikasi'] === 'Buku') {
            $mainData['sumber_pembiayaan'] = null; // Buku tidak ada pembiayaan
        } else {
            $mainData['sumber_pembiayaan'] = ($data['sumber_pembiayaan'] === 'Lainnya')
                ? $data['sumber_pembiayaan_lainnya']
                : $data['sumber_pembiayaan'];
        }

        // 3. Pengepakan Metadata (Berdasarkan Gambar 2, 3, 4, 5)
        // Kita saring khusus field yang relevan berdasarkan jenisnya
        $metadata = [];
        if ($data['jenis_publikasi'] === 'Jurnal') {
            $metadata = [
                'nama_jurnal' => $data['nama_jurnal'] ?? null,
                'volume'      => $data['volume'] ?? null,
                'nomor'       => $data['nomor'] ?? null,
                'issn'        => $data['issn'] ?? null,
                'url'         => $data['url_jurnal'] ?? null,
            ];
        } elseif ($data['jenis_publikasi'] === 'HKI') {
            $metadata = [
                'no_hki' => $data['no_hki'] ?? null,
                'url'    => $data['url_hki'] ?? null,
            ];
        } elseif ($data['jenis_publikasi'] === 'Prosiding') {
            $metadata = [
                'nama_prosiding' => $data['nama_prosiding'] ?? null,
                'penyelenggara'  => $data['penyelenggara'] ?? null,
                'isbn'           => $data['isbn_prosiding'] ?? null,
                'url'            => $data['url_prosiding'] ?? null,
            ];
        } elseif ($data['jenis_publikasi'] === 'Buku') {
            $metadata = [
                'penerbit'       => $data['penerbit'] ?? null,
                'isbn'           => $data['isbn_buku'] ?? null,
                'jumlah_halaman' => $data['jumlah_halaman'] ?? null,
                'url'            => $data['url_buku'] ?? null,
            ];
        }

        $mainData['metadata'] = json_encode($metadata);

        // 4. Proses Simpan (Otomatis memicu validasi dari Model)
        if (!$this->publikasiModel->insert($mainData)) {
            // Ambil error dari model jika validasi gagal
            $errors = implode(', ', $this->publikasiModel->errors());
            throw new Exception('Validasi Gagal: ' . $errors);
        }

        return true;
    }

    public function updatePublikasi(string $uuid, array $data)
    {
        return $this->updatePublikasiByKey($uuid, $data);
    }

    public function updatePublikasiByKey(string $key, array $data)
    {
        $publikasiLama = $this->findPublikasiRowByKey($key);
        if (!$publikasiLama) {
            throw new Exception('Data tidak ditemukan.');
        }

        $mainData = [
            'id'              => $publikasiLama->id, // Harus disertakan untuk trigger update CI4
            'user_id'         => $data['user_id'],
            'judul'           => $data['judul'],
            'penulis'         => $data['penulis'] ?? null,
            'klaster'         => $data['klaster'] ?? null,
            'jenis_publikasi' => $data['jenis_publikasi'],
            'tahun'           => $data['tahun'],
        ];

        if ($data['jenis_publikasi'] === 'Buku') {
            $mainData['sumber_pembiayaan'] = null;
        } else {
            $mainData['sumber_pembiayaan'] = ($data['sumber_pembiayaan'] === 'Lainnya')
                ? $data['sumber_pembiayaan_lainnya']
                : $data['sumber_pembiayaan'];
        }

        // Pengepakan Metadata (Sama seperti fungsi simpan)
        $metadata = [];
        if ($data['jenis_publikasi'] === 'Jurnal') {
            $metadata = ['nama_jurnal' => $data['nama_jurnal'] ?? null, 'volume' => $data['volume'] ?? null, 'nomor' => $data['nomor'] ?? null, 'issn' => $data['issn'] ?? null, 'url' => $data['url_jurnal'] ?? null];
        } elseif ($data['jenis_publikasi'] === 'HKI') {
            $metadata = ['no_hki' => $data['no_hki'] ?? null, 'url' => $data['url_hki'] ?? null];
        } elseif ($data['jenis_publikasi'] === 'Prosiding') {
            $metadata = ['nama_prosiding' => $data['nama_prosiding'] ?? null, 'penyelenggara' => $data['penyelenggara'] ?? null, 'isbn' => $data['isbn_prosiding'] ?? null, 'url' => $data['url_prosiding'] ?? null];
        } elseif ($data['jenis_publikasi'] === 'Buku') {
            $metadata = ['penerbit' => $data['penerbit'] ?? null, 'isbn' => $data['isbn_buku'] ?? null, 'jumlah_halaman' => $data['jumlah_halaman'] ?? null, 'url' => $data['url_buku'] ?? null];
        }

        $mainData['metadata'] = json_encode($metadata);

        if (!$this->publikasiModel->save($mainData)) {
            throw new Exception('Validasi Gagal: ' . implode(', ', $this->publikasiModel->errors()));
        }

        return true;
    }

    /**
     * Ambil semua publikasi beserta nama dosen (join).
     */
    public function getAllPublikasi(): array
    {
        $publikasiList = $this->publikasiModel
            ->select("publikasi.*, COALESCE(NULLIF(users.nama_lengkap, ''), users.username) as nama_dosen")
            ->join('users', 'users.id = publikasi.user_id')
            ->orderBy('publikasi.created_at', 'DESC')
            ->findAll();

        foreach ($publikasiList as $publikasi) {
            $this->ensurePublikasiUuid($publikasi);
        }

        return $publikasiList;
    }

    public function getIndexPayload(): array
    {
        $rows = [];

        foreach ($this->getAllPublikasi() as $publikasi) {
            $publikasiKey = $publikasi->uuid ?: ('id-' . $publikasi->id);

            $rows[] = [
                'key'               => $publikasiKey,
                'dosen_name'        => $publikasi->nama_dosen,
                'judul'             => $publikasi->judul,
                'jenis_label'       => $publikasi->jenis_publikasi,
                'jenis_badge_class' => $this->mapJenisBadgeClass($publikasi->jenis_publikasi),
                'tahun'             => $publikasi->tahun,
                'show_url'          => site_url('admin/publikasi/show/' . $publikasiKey),
                'edit_url'          => site_url('admin/publikasi/edit/' . $publikasiKey),
                'delete_url'        => site_url('admin/publikasi/delete/' . $publikasiKey),
            ];
        }

        return [
            'tableRows' => $rows,
        ];
    }

    public function getCreateFormPayload(): array
    {
        return $this->buildFormPayload(null, site_url('admin/publikasi/store'), 'Simpan Publikasi');
    }

    public function getEditFormPayload(string $key): ?array
    {
        $publikasi = $this->getPublikasiByKey($key);
        if (!$publikasi) {
            return null;
        }

        $publikasiKey = $publikasi->uuid ?: ('id-' . $publikasi->id);

        return $this->buildFormPayload(
            $publikasi,
            site_url('admin/publikasi/update/' . $publikasiKey),
            'Update Data',
            $publikasiKey
        );
    }

    /**
     * Ambil satu publikasi berdasarkan UUID, metadata di-unpack ke key biasa.
     * Mengembalikan null jika tidak ditemukan.
     */
    public function getPublikasiByUuid(string $uuid): ?object
    {
        return $this->getPublikasiByKey($uuid);
    }

    public function getPublikasiByKey(string $key): ?object
    {
        $publikasi = $this->findPublikasiRowByKey($key);
        if (!$publikasi) {
            return null;
        }

        $this->ensurePublikasiUuid($publikasi);

        $metadata = json_decode($publikasi->metadata ?? '{}', true);
        foreach ($metadata as $key => $value) {
            $publikasi->{$key} = $value;
        }

        return $publikasi;
    }

    public function getPublikasiDetailPayload(string $key): ?array
    {
        $publikasi = $this->getPublikasiByKey($key);
        if (!$publikasi) {
            return null;
        }

        $publikasiKey = $publikasi->uuid ?: ('id-' . $publikasi->id);
        $metadataItems = $this->buildMetadataItems($publikasi);

        return [
            'hero' => [
                'title' => $publikasi->judul,
                'subtitle' => $publikasi->nama_dosen ?? '-',
                'jenis_label' => $publikasi->jenis_publikasi,
                'jenis_badge_class' => $this->mapJenisBadgeClass($publikasi->jenis_publikasi),
                'klaster_label' => $this->valueOrDash($publikasi->klaster ?? ''),
                'klaster_badge_class' => $this->mapKlasterBadgeClass($publikasi->klaster ?? ''),
                'tahun' => $publikasi->tahun,
            ],
            'summaryItems' => [
                ['label' => 'Dosen', 'value' => $publikasi->nama_dosen ?? '-'],
                ['label' => 'Penulis', 'value' => $this->valueOrDash($publikasi->penulis ?? '')],
                ['label' => 'Jenis Publikasi', 'value' => $publikasi->jenis_publikasi],
                ['label' => 'Klaster / Skala', 'value' => $this->valueOrDash($publikasi->klaster ?? '')],
                ['label' => 'Tahun', 'value' => (string) $publikasi->tahun],
                ['label' => 'Sumber Pembiayaan', 'value' => $this->valueOrDash($publikasi->sumber_pembiayaan ?? '')],
            ],
            'metadataTitle' => 'Detail ' . $publikasi->jenis_publikasi,
            'metadataItems' => $metadataItems,
            'actions' => [
                'back_url' => site_url('admin/publikasi'),
                'edit_url' => site_url('admin/publikasi/edit/' . $publikasiKey),
                'delete_url' => site_url('admin/publikasi/delete/' . $publikasiKey),
            ],
        ];
    }

    /**
     * Ambil daftar semua dosen (user) untuk dropdown form.
     */
    public function getDosenList(): array
    {
        return $this->publikasiModel->db
            ->table('users')
            ->select('users.id, users.username, users.nama_lengkap')
            ->join('user_roles', 'user_roles.user_id = users.id', 'inner')
            ->join('roles', 'roles.id = user_roles.role_id', 'inner')
            ->where('roles.name', 'dosen')
            ->where('users.deleted_at', null)
            ->groupBy('users.id')
            ->orderBy('username', 'ASC')
            ->get()
            ->getResult();
    }

    /**
     * Hapus publikasi berdasarkan UUID.
     *
     * @throws Exception jika data tidak ditemukan
     */
    public function deletePublikasi(string $uuid): void
    {
        $this->deletePublikasiByKey($uuid);
    }

    public function deletePublikasiByKey(string $key): void
    {
        $row = $this->findPublikasiRowByKey($key);
        if (!$row) {
            throw new Exception('Data tidak ditemukan.');
        }

        $this->publikasiModel->delete($row->id);
    }

    private function findPublikasiRowByKey(string $key): ?object
    {
        $builder = $this->publikasiModel
            ->select("publikasi.*, COALESCE(NULLIF(users.nama_lengkap, ''), users.username) as nama_dosen")
            ->join('users', 'users.id = publikasi.user_id', 'left');

        if (str_starts_with($key, self::ID_KEY_PREFIX)) {
            $id = (int) substr($key, strlen(self::ID_KEY_PREFIX));
            return $id > 0 ? $builder->where('publikasi.id', $id)->first() : null;
        }

        return $builder->where('publikasi.uuid', $key)->first();
    }

    private function ensurePublikasiUuid(object $publikasi): void
    {
        if (!empty($publikasi->uuid)) {
            return;
        }

        $publikasi->uuid = Uuid::uuid4()->toString();
        $this->publikasiModel->db
            ->table('publikasi')
            ->where('id', $publikasi->id)
            ->update(['uuid' => $publikasi->uuid]);
    }

    private function buildMetadataItems(object $publikasi): array
    {
        return match ($publikasi->jenis_publikasi) {
            'Jurnal' => [
                ['label' => 'Nama Jurnal', 'value' => $this->valueOrDash($publikasi->nama_jurnal ?? '')],
                ['label' => 'Volume', 'value' => $this->valueOrDash($publikasi->volume ?? '')],
                ['label' => 'Nomor', 'value' => $this->valueOrDash($publikasi->nomor ?? '')],
                ['label' => 'ISSN', 'value' => $this->valueOrDash($publikasi->issn ?? '')],
                $this->buildLinkMetadataItem('URL / DOI', $publikasi->url ?? ''),
            ],
            'HKI' => [
                ['label' => 'Nomor HKI', 'value' => $this->valueOrDash($publikasi->no_hki ?? '')],
                $this->buildLinkMetadataItem('URL', $publikasi->url ?? ''),
            ],
            'Prosiding' => [
                ['label' => 'Nama Prosiding', 'value' => $this->valueOrDash($publikasi->nama_prosiding ?? '')],
                ['label' => 'Penyelenggara', 'value' => $this->valueOrDash($publikasi->penyelenggara ?? '')],
                ['label' => 'ISBN', 'value' => $this->valueOrDash($publikasi->isbn ?? '')],
                $this->buildLinkMetadataItem('URL', $publikasi->url ?? ''),
            ],
            'Buku' => [
                ['label' => 'Penerbit', 'value' => $this->valueOrDash($publikasi->penerbit ?? '')],
                ['label' => 'ISBN', 'value' => $this->valueOrDash($publikasi->isbn ?? '')],
                ['label' => 'Jumlah Halaman', 'value' => $this->valueOrDash((string) ($publikasi->jumlah_halaman ?? ''))],
                $this->buildLinkMetadataItem('URL', $publikasi->url ?? ''),
            ],
            default => [],
        };
    }

    private function buildLinkMetadataItem(string $label, string $url): array
    {
        return [
            'label' => $label,
            'value' => $this->valueOrDash($url),
            'url' => trim($url),
        ];
    }

    private function buildFormPayload(?object $publikasi, string $actionUrl, string $submitLabel, ?string $formKey = null): array
    {
        $values = $this->resolveFormValues($publikasi);
        $pembiayaanIsLainnya = $values['sumber_pembiayaan'] === 'Lainnya';

        return [
            'formState' => [
                'is_edit' => $publikasi !== null,
                'action_url' => $actionUrl,
                'submit_label' => $submitLabel,
                'form_key' => $formKey,
                'selected_jenis' => $values['jenis_publikasi'],
                'show_pembiayaan' => $values['jenis_publikasi'] !== 'Buku',
                'pembiayaan_lainnya_class' => $pembiayaanIsLainnya ? '' : 'd-none',
                'pembiayaan_lainnya_required' => $pembiayaanIsLainnya ? 'required' : '',
            ],
            'formValues' => $values,
            'dosenOptions' => $this->buildDosenOptions($values['user_id']),
            'jenisOptions' => $this->buildJenisOptions($values['jenis_publikasi']),
            'pembiayaanOptions' => $this->buildPembiayaanOptions($values['sumber_pembiayaan']),
        ];
    }

    private function resolveFormValues(?object $publikasi): array
    {
        $old = $this->getOldPostInput();
        $basePembiayaan = $this->pick($old, 'sumber_pembiayaan', $publikasi->sumber_pembiayaan ?? '');
        $knownPembiayaan = ['', 'Mandiri', 'DIPA UINSI', 'Lainnya'];
        $isLainnya = $basePembiayaan !== '' && !in_array($basePembiayaan, ['Mandiri', 'DIPA UINSI', 'Lainnya'], true);

        return [
            'user_id' => $this->pick($old, 'user_id', $publikasi->user_id ?? ''),
            'tahun' => $this->pick($old, 'tahun', $publikasi->tahun ?? ''),
            'judul' => $this->pick($old, 'judul', $publikasi->judul ?? ''),
            'penulis' => $this->pick($old, 'penulis', $publikasi->penulis ?? ''),
            'klaster' => $this->pick($old, 'klaster', $publikasi->klaster ?? ''),
            'jenis_publikasi' => $this->pick($old, 'jenis_publikasi', $publikasi->jenis_publikasi ?? ''),
            'nama_jurnal' => $this->pick($old, 'nama_jurnal', $publikasi->nama_jurnal ?? ''),
            'volume' => $this->pick($old, 'volume', $publikasi->volume ?? ''),
            'nomor' => $this->pick($old, 'nomor', $publikasi->nomor ?? ''),
            'issn' => $this->pick($old, 'issn', $publikasi->issn ?? ''),
            'url_jurnal' => $this->pick($old, 'url_jurnal', $publikasi->url ?? ''),
            'no_hki' => $this->pick($old, 'no_hki', $publikasi->no_hki ?? ''),
            'url_hki' => $this->pick($old, 'url_hki', $publikasi->url ?? ''),
            'nama_prosiding' => $this->pick($old, 'nama_prosiding', $publikasi->nama_prosiding ?? ''),
            'penyelenggara' => $this->pick($old, 'penyelenggara', $publikasi->penyelenggara ?? ''),
            'isbn_prosiding' => $this->pick($old, 'isbn_prosiding', $publikasi->isbn ?? ''),
            'url_prosiding' => $this->pick($old, 'url_prosiding', $publikasi->url ?? ''),
            'penerbit' => $this->pick($old, 'penerbit', $publikasi->penerbit ?? ''),
            'isbn_buku' => $this->pick($old, 'isbn_buku', $publikasi->isbn ?? ''),
            'jumlah_halaman' => $this->pick($old, 'jumlah_halaman', $publikasi->jumlah_halaman ?? ''),
            'url_buku' => $this->pick($old, 'url_buku', $publikasi->url ?? ''),
            'sumber_pembiayaan' => $isLainnya ? 'Lainnya' : (in_array($basePembiayaan, $knownPembiayaan, true) ? $basePembiayaan : ''),
            'sumber_pembiayaan_lainnya' => $isLainnya ? $basePembiayaan : $this->pick($old, 'sumber_pembiayaan_lainnya', ''),
        ];
    }

    private function buildDosenOptions(string $selected): array
    {
        $options = [];

        foreach ($this->getDosenList() as $dosen) {
            $namaDosen = $dosen->nama_lengkap ?: $dosen->username;
            $options[] = [
                'value' => (string) $dosen->id,
                'label' => sprintf('%s (%s)', $namaDosen, $dosen->username),
                'selected_attr' => ((string) $dosen->id === (string) $selected) ? 'selected' : '',
            ];
        }

        return $options;
    }

    private function buildJenisOptions(string $selected): array
    {
        $values = ['Jurnal', 'HKI', 'Prosiding', 'Buku'];
        $options = [];

        foreach ($values as $value) {
            $options[] = [
                'value' => $value,
                'label' => $value,
                'checked_attr' => $value === $selected ? 'checked' : '',
                'id' => 'jenis-' . strtolower($value),
            ];
        }

        return $options;
    }

    private function buildPembiayaanOptions(string $selected): array
    {
        $values = [
            '' => '-- Pilih Sumber Pembiayaan --',
            'Mandiri' => 'Mandiri',
            'DIPA UINSI' => 'DIPA UINSI',
            'Lainnya' => 'Lainnya...',
        ];
        $options = [];

        foreach ($values as $value => $label) {
            $options[] = [
                'value' => $value,
                'label' => $label,
                'selected_attr' => $value === $selected ? 'selected' : '',
            ];
        }

        return $options;
    }

    private function mapJenisBadgeClass(string $jenis): string
    {
        return match ($jenis) {
            'Jurnal' => 'text-bg-primary',
            'HKI' => 'text-bg-success',
            'Prosiding' => 'text-bg-warning',
            'Buku' => 'text-bg-info',
            default => 'text-bg-secondary',
        };
    }

    private function mapKlasterBadgeClass(string $klaster): string
    {
        $normalized = strtolower(trim($klaster));

        return match (true) {
            $normalized === '' => 'bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle',
            str_contains($normalized, 'internasional') => 'bg-success-subtle text-success-emphasis border border-success-subtle',
            str_contains($normalized, 'nasional') => 'bg-primary-subtle text-primary-emphasis border border-primary-subtle',
            str_contains($normalized, 'regional') => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
            default => 'bg-light text-dark border',
        };
    }

    // ================================================================
    // Dosen-scoped public methods (filter by user_id)
    // ================================================================

    public function getAllByUserId(int $userId): array
    {
        $publikasiList = $this->publikasiModel
            ->select("publikasi.*, COALESCE(NULLIF(users.nama_lengkap, ''), users.username) as nama_dosen")
            ->join('users', 'users.id = publikasi.user_id')
            ->where('publikasi.user_id', $userId)
            ->orderBy('publikasi.created_at', 'DESC')
            ->findAll();

        foreach ($publikasiList as $publikasi) {
            $this->ensurePublikasiUuid($publikasi);
        }

        return $publikasiList;
    }

    public function getIndexPayloadByUser(int $userId): array
    {
        $rows = [];

        foreach ($this->getAllByUserId($userId) as $publikasi) {
            $key = $publikasi->uuid ?: ('id-' . $publikasi->id);

            $rows[] = [
                'key'               => $key,
                'judul'             => $publikasi->judul,
                'jenis_label'       => $publikasi->jenis_publikasi,
                'jenis_badge_class' => $this->mapJenisBadgeClass($publikasi->jenis_publikasi),
                'tahun'             => $publikasi->tahun,
                'show_url'          => site_url('dosen/publikasi/show/' . $key),
                'edit_url'          => site_url('dosen/publikasi/edit/' . $key),
                'delete_url'        => site_url('dosen/publikasi/delete/' . $key),
            ];
        }

        return ['tableRows' => $rows];
    }

    public function getCreateFormPayloadForDosen(): array
    {
        return $this->buildFormPayloadForDosen(null, site_url('dosen/publikasi/store'), 'Simpan Publikasi');
    }

    public function getEditFormPayloadForDosen(string $key, int $userId): ?array
    {
        $publikasi = $this->findByKeyAndUser($key, $userId);
        if (!$publikasi) {
            return null;
        }

        $publikasiKey = $publikasi->uuid ?: ('id-' . $publikasi->id);

        return $this->buildFormPayloadForDosen(
            $publikasi,
            site_url('dosen/publikasi/update/' . $publikasiKey),
            'Update Data',
            $publikasiKey
        );
    }

    public function getPublikasiDetailPayloadForDosen(string $key, int $userId): ?array
    {
        $publikasi = $this->findByKeyAndUser($key, $userId);
        if (!$publikasi) {
            return null;
        }

        $publikasiKey = $publikasi->uuid ?: ('id-' . $publikasi->id);
        $metadataItems = $this->buildMetadataItems($publikasi);

        return [
            'hero' => [
                'title'             => $publikasi->judul,
                'subtitle'          => $publikasi->nama_dosen ?? '-',
                'jenis_label'       => $publikasi->jenis_publikasi,
                'jenis_badge_class' => $this->mapJenisBadgeClass($publikasi->jenis_publikasi),
                'klaster_label'     => $this->valueOrDash($publikasi->klaster ?? ''),
                'klaster_badge_class' => $this->mapKlasterBadgeClass($publikasi->klaster ?? ''),
                'tahun'             => $publikasi->tahun,
            ],
            'summaryItems' => [
                ['label' => 'Penulis', 'value' => $this->valueOrDash($publikasi->penulis ?? '')],
                ['label' => 'Jenis Publikasi', 'value' => $publikasi->jenis_publikasi],
                ['label' => 'Klaster / Skala', 'value' => $this->valueOrDash($publikasi->klaster ?? '')],
                ['label' => 'Tahun', 'value' => (string) $publikasi->tahun],
                ['label' => 'Sumber Pembiayaan', 'value' => $this->valueOrDash($publikasi->sumber_pembiayaan ?? '')],
            ],
            'metadataTitle' => 'Detail ' . $publikasi->jenis_publikasi,
            'metadataItems' => $metadataItems,
            'actions' => [
                'back_url'   => site_url('dosen/publikasi'),
                'edit_url'   => site_url('dosen/publikasi/edit/' . $publikasiKey),
                'delete_url' => site_url('dosen/publikasi/delete/' . $publikasiKey),
            ],
        ];
    }

    public function findByKeyAndUser(string $key, int $userId): ?object
    {
        $row = $this->findPublikasiRowByKey($key);
        if (!$row || (int) $row->user_id !== $userId) {
            return null;
        }

        $this->ensurePublikasiUuid($row);

        $metadata = json_decode($row->metadata ?? '{}', true);
        foreach ($metadata as $k => $v) {
            $row->{$k} = $v;
        }

        return $row;
    }

    private function buildFormPayloadForDosen(?object $publikasi, string $actionUrl, string $submitLabel, ?string $formKey = null): array
    {
        $values = $this->resolveFormValues($publikasi);
        $pembiayaanIsLainnya = $values['sumber_pembiayaan'] === 'Lainnya';

        return [
            'formState' => [
                'is_edit'                     => $publikasi !== null,
                'action_url'                  => $actionUrl,
                'submit_label'                => $submitLabel,
                'form_key'                    => $formKey,
                'selected_jenis'              => $values['jenis_publikasi'],
                'show_pembiayaan'             => $values['jenis_publikasi'] !== 'Buku',
                'pembiayaan_lainnya_class'    => $pembiayaanIsLainnya ? '' : 'd-none',
                'pembiayaan_lainnya_required' => $pembiayaanIsLainnya ? 'required' : '',
            ],
            'formValues'        => $values,
            'jenisOptions'      => $this->buildJenisOptions($values['jenis_publikasi']),
            'pembiayaanOptions' => $this->buildPembiayaanOptions($values['sumber_pembiayaan']),
        ];
    }

    // ================================================================

    private function valueOrDash(string $value): string
    {
        $trimmed = trim($value);
        return $trimmed === '' ? '-' : $trimmed;
    }

    private function getOldPostInput(): array
    {
        $oldInput = session('_ci_old_input');
        if (!is_array($oldInput)) {
            return [];
        }

        return is_array($oldInput['post'] ?? null) ? $oldInput['post'] : [];
    }

    private function pick(array $source, string $key, $fallback): string
    {
        if (array_key_exists($key, $source)) {
            return (string) $source[$key];
        }

        return (string) ($fallback ?? '');
    }
}
