<?php

namespace App\Services\KegiatanMandiri;

use App\Models\KegiatanMandiri\KegiatanMandiriModel;
use Exception;

class KegiatanMandiriService
{
    private KegiatanMandiriModel $kegiatanMandiriModel;

    public function __construct()
    {
        $this->kegiatanMandiriModel = new KegiatanMandiriModel();
    }

    public function getAllKegiatan(): array
    {
        return $this->kegiatanMandiriModel
            ->select('kegiatan_mandiri.*, users.username, users.nama_lengkap')
            ->join('users', 'users.id = kegiatan_mandiri.user_id')
            ->orderBy('kegiatan_mandiri.created_at', 'DESC')
            ->findAll();
    }

    public function getIndexPayload(): array
    {
        $rows = [];

        foreach ($this->getAllKegiatan() as $kegiatan) {
            $rows[] = [
                'uuid'               => $kegiatan->uuid,
                'display_name'       => $kegiatan->nama_lengkap ?: $kegiatan->username,
                'judul_kegiatan'     => $kegiatan->judul_kegiatan,
                'jenis_kegiatan'     => $kegiatan->jenis_kegiatan,
                'jenis_badge_class'  => $this->mapJenisBadgeClass($kegiatan->jenis_kegiatan),
                'klaster_label'      => $kegiatan->klaster_skala_kegiatan,
                'klaster_badge_class' => $this->mapKlasterBadgeClass($kegiatan->klaster_skala_kegiatan),
                'tahun'              => $kegiatan->tahun,
                'show_url'           => site_url('admin/kegiatan-mandiri/show/' . $kegiatan->uuid),
                'edit_url'           => site_url('admin/kegiatan-mandiri/edit/' . $kegiatan->uuid),
                'delete_url'         => site_url('admin/kegiatan-mandiri/delete/' . $kegiatan->uuid),
            ];
        }

        return [
            'tableRows' => $rows,
        ];
    }

    public function getDetailPayload(string $uuid): ?array
    {
        $kegiatan = $this->getKegiatanByUuid($uuid);
        if (!$kegiatan) {
            return null;
        }

        $displayName = $kegiatan->nama_lengkap ?? $kegiatan->username ?? null;
        if ($displayName === null) {
            $withUser = $this->kegiatanMandiriModel
                ->select('kegiatan_mandiri.*, users.username, users.nama_lengkap')
                ->join('users', 'users.id = kegiatan_mandiri.user_id')
                ->where('kegiatan_mandiri.id', $kegiatan->id)
                ->first();
            $kegiatan = $withUser ?? $kegiatan;
            $displayName = $kegiatan->nama_lengkap ?? $kegiatan->username ?? '-';
        }

        return [
            'hero' => [
                'title'              => $kegiatan->judul_kegiatan,
                'subtitle'           => $displayName,
                'jenis_label'        => $kegiatan->jenis_kegiatan,
                'jenis_badge_class'  => $this->mapJenisBadgeClass($kegiatan->jenis_kegiatan),
                'klaster_label'      => $kegiatan->klaster_skala_kegiatan,
                'klaster_badge_class' => $this->mapKlasterBadgeClass($kegiatan->klaster_skala_kegiatan),
                'tahun'              => $kegiatan->tahun,
            ],
            'summaryItems' => [
                ['label' => 'Dosen', 'value' => $displayName],
                ['label' => 'Tahun Pelaksanaan', 'value' => (string) $kegiatan->tahun],
                ['label' => 'Jenis Kegiatan', 'value' => $kegiatan->jenis_kegiatan],
                ['label' => 'Klaster/Skala', 'value' => $kegiatan->klaster_skala_kegiatan],
                ['label' => 'Anggota Terlibat', 'value' => $this->valueOrDash($kegiatan->anggota_terlibat ?? '')],
            ],
            'detailItems' => [
                ['label' => 'Unit Pelaksana', 'value' => $this->valueOrDash($kegiatan->unit_pelaksana_kegiatan ?? '')],
                ['label' => 'Mitra Kolaborasi', 'value' => $this->valueOrDash($kegiatan->mitra_kolaborasi ?? '')],
                ['label' => 'Sumber Dana', 'value' => $this->valueOrDash($kegiatan->sumber_dana ?? '')],
                ['label' => 'Besaran Dana', 'value' => $this->formatCurrency($kegiatan->besaran_dana ?? null)],
            ],
            'resumeHtml' => $this->formatMultilineHtml($kegiatan->resume_kegiatan ?? ''),
            'evidence' => [
                'url'   => $kegiatan->tautan_bukti_dukung,
                'label' => $this->shortenUrl($kegiatan->tautan_bukti_dukung ?? ''),
            ],
            'actions' => [
                'back_url'   => site_url('admin/kegiatan-mandiri'),
                'edit_url'   => site_url('admin/kegiatan-mandiri/edit/' . $kegiatan->uuid),
                'delete_url' => site_url('admin/kegiatan-mandiri/delete/' . $kegiatan->uuid),
            ],
        ];
    }

    public function getKegiatanByUuid(string $uuid): ?object
    {
        return $this->kegiatanMandiriModel->where('uuid', $uuid)->first();
    }

    public function createKegiatan(array $input): bool
    {
        $data = $this->normalizePayload($input);

        if (!$this->kegiatanMandiriModel->insert($data)) {
            throw new Exception('Validasi Gagal: ' . implode(', ', $this->kegiatanMandiriModel->errors()));
        }

        return true;
    }

    public function updateKegiatan(string $uuid, array $input): bool
    {
        $existing = $this->getKegiatanByUuid($uuid);
        if (!$existing) {
            throw new Exception('Data kegiatan mandiri tidak ditemukan.');
        }

        $data = $this->normalizePayload($input);
        $data['id'] = $existing->id;

        if (!$this->kegiatanMandiriModel->save($data)) {
            throw new Exception('Validasi Gagal: ' . implode(', ', $this->kegiatanMandiriModel->errors()));
        }

        return true;
    }

    public function deleteKegiatan(string $uuid): void
    {
        $existing = $this->getKegiatanByUuid($uuid);
        if (!$existing) {
            throw new Exception('Data kegiatan mandiri tidak ditemukan.');
        }

        $this->kegiatanMandiriModel->delete($existing->id);
    }

    public function getCreateFormPayload(): array
    {
        return $this->buildFormPayload(null);
    }

    public function getEditFormPayload(object $kegiatan): array
    {
        return $this->buildFormPayload($kegiatan);
    }

    public function getDosenList(): array
    {
        return $this->kegiatanMandiriModel->db
            ->table('users')
            ->select('users.id, users.username, users.nama_lengkap')
            ->join('user_roles', 'user_roles.user_id = users.id', 'inner')
            ->join('roles', 'roles.id = user_roles.role_id', 'inner')
            ->where('roles.name', 'dosen')
            ->where('users.deleted_at', null)
            ->groupBy('users.id')
            ->orderBy('users.username', 'ASC')
            ->get()
            ->getResult();
    }

    private function buildFormPayload(?object $kegiatan): array
    {
        $values = $this->resolveFormValues($kegiatan);

        return [
            'formValues'    => $values,
            'dosenOptions'  => $this->buildDosenOptions($values['user_id']),
            'jenisOptions'  => $this->buildStaticOptions($this->jenisKegiatanOptions(), $values['jenis_kegiatan']),
            'klasterOptions' => $this->buildStaticOptions($this->klasterSkalaOptions(), $values['klaster_skala_kegiatan']),
        ];
    }

    private function resolveFormValues(?object $kegiatan): array
    {
        $old = $this->getOldPostInput();

        return [
            'user_id'                 => $this->pick($old, 'user_id', $kegiatan->user_id ?? ''),
            'tahun'                   => $this->pick($old, 'tahun', $kegiatan->tahun ?? ''),
            'jenis_kegiatan'          => $this->pick($old, 'jenis_kegiatan', $kegiatan->jenis_kegiatan ?? ''),
            'klaster_skala_kegiatan'  => $this->pick($old, 'klaster_skala_kegiatan', $kegiatan->klaster_skala_kegiatan ?? ''),
            'judul_kegiatan'          => $this->pick($old, 'judul_kegiatan', $kegiatan->judul_kegiatan ?? ''),
            'anggota_terlibat'        => $this->pick($old, 'anggota_terlibat', $kegiatan->anggota_terlibat ?? ''),
            'resume_kegiatan'         => $this->pick($old, 'resume_kegiatan', $kegiatan->resume_kegiatan ?? ''),
            'unit_pelaksana_kegiatan' => $this->pick($old, 'unit_pelaksana_kegiatan', $kegiatan->unit_pelaksana_kegiatan ?? ''),
            'mitra_kolaborasi'        => $this->pick($old, 'mitra_kolaborasi', $kegiatan->mitra_kolaborasi ?? ''),
            'sumber_dana'             => $this->pick($old, 'sumber_dana', $kegiatan->sumber_dana ?? ''),
            'besaran_dana'            => $this->pick($old, 'besaran_dana', $kegiatan->besaran_dana ?? ''),
            'tautan_bukti_dukung'     => $this->pick($old, 'tautan_bukti_dukung', $kegiatan->tautan_bukti_dukung ?? ''),
        ];
    }

    private function buildDosenOptions(string $selected): array
    {
        $options = [];
        foreach ($this->getDosenList() as $dosen) {
            $name = $dosen->nama_lengkap ?: $dosen->username;
            $options[] = [
                'value'        => (string) $dosen->id,
                'label'        => sprintf('%s (%s)', $name, $dosen->username),
                'selectedAttr' => ((string) $dosen->id === (string) $selected) ? 'selected' : '',
            ];
        }

        return $options;
    }

    private function buildStaticOptions(array $values, string $selected): array
    {
        $options = [];
        foreach ($values as $value => $label) {
            $options[] = [
                'value'        => $value,
                'label'        => $label,
                'selectedAttr' => ($value === $selected) ? 'selected' : '',
            ];
        }

        return $options;
    }

    private function jenisKegiatanOptions(): array
    {
        return [
            'Penelitian Mandiri' => 'Penelitian Mandiri',
            'Pengabdian Mandiri' => 'Pengabdian Mandiri',
            'Pelatihan'          => 'Pelatihan',
            'Seminar/Workshop'   => 'Seminar / Workshop',
            'Kegiatan Ilmiah'    => 'Kegiatan Ilmiah',
            'Lainnya'            => 'Lainnya',
        ];
    }

    private function klasterSkalaOptions(): array
    {
        return [
            'Lokal'         => 'Lokal',
            'Regional'      => 'Regional',
            'Nasional'      => 'Nasional',
            'Internasional' => 'Internasional',
        ];
    }

    private function normalizePayload(array $input): array
    {
        $besaranDana = preg_replace('/\D+/', '', (string) ($input['besaran_dana'] ?? ''));

        return [
            'user_id'                 => (int) ($input['user_id'] ?? 0),
            'tahun'                   => trim((string) ($input['tahun'] ?? '')),
            'jenis_kegiatan'          => trim((string) ($input['jenis_kegiatan'] ?? '')),
            'klaster_skala_kegiatan'  => trim((string) ($input['klaster_skala_kegiatan'] ?? '')),
            'judul_kegiatan'          => trim((string) ($input['judul_kegiatan'] ?? '')),
            'anggota_terlibat'        => trim((string) ($input['anggota_terlibat'] ?? '')),
            'resume_kegiatan'         => trim((string) ($input['resume_kegiatan'] ?? '')),
            'unit_pelaksana_kegiatan' => trim((string) ($input['unit_pelaksana_kegiatan'] ?? '')),
            'mitra_kolaborasi'        => trim((string) ($input['mitra_kolaborasi'] ?? '')),
            'sumber_dana'             => trim((string) ($input['sumber_dana'] ?? '')),
            'besaran_dana'            => $besaranDana === '' ? null : (int) $besaranDana,
            'tautan_bukti_dukung'     => trim((string) ($input['tautan_bukti_dukung'] ?? '')),
        ];
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

    private function mapJenisBadgeClass(string $jenis): string
    {
        return match ($jenis) {
            'Penelitian Mandiri' => 'text-bg-primary',
            'Pengabdian Mandiri' => 'text-bg-success',
            'Pelatihan' => 'text-bg-warning',
            'Seminar/Workshop' => 'text-bg-info',
            'Kegiatan Ilmiah' => 'text-bg-dark',
            default => 'text-bg-secondary',
        };
    }

    private function mapKlasterBadgeClass(string $klaster): string
    {
        return match ($klaster) {
            'Lokal' => 'bg-light text-dark border',
            'Regional' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
            'Nasional' => 'bg-primary-subtle text-primary-emphasis border border-primary-subtle',
            'Internasional' => 'bg-success-subtle text-success-emphasis border border-success-subtle',
            default => 'bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle',
        };
    }

    private function valueOrDash(string $value): string
    {
        $trimmed = trim($value);
        return $trimmed === '' ? '-' : $trimmed;
    }

    private function formatCurrency($value): string
    {
        if ($value === null || $value === '') {
            return '-';
        }

        return 'Rp ' . number_format((int) $value, 0, ',', '.');
    }

    private function formatMultilineHtml(string $value): string
    {
        $trimmed = trim($value);
        if ($trimmed === '') {
            return '<p class="text-muted mb-0">Belum ada resume kegiatan.</p>';
        }

        return nl2br(htmlspecialchars($trimmed, ENT_QUOTES, 'UTF-8'));
    }

    private function shortenUrl(string $value): string
    {
        $trimmed = trim($value);
        if ($trimmed === '') {
            return '-';
        }

        return strlen($trimmed) > 60 ? substr($trimmed, 0, 57) . '...' : $trimmed;
    }

    // ================================================================
    // Dosen-scoped public methods (filter by user_id)
    // ================================================================

    public function getAllByUserId(int $userId): array
    {
        return $this->kegiatanMandiriModel
            ->select('kegiatan_mandiri.*, users.username, users.nama_lengkap')
            ->join('users', 'users.id = kegiatan_mandiri.user_id')
            ->where('kegiatan_mandiri.user_id', $userId)
            ->orderBy('kegiatan_mandiri.created_at', 'DESC')
            ->findAll();
    }

    public function getIndexPayloadByUser(int $userId): array
    {
        $rows = [];

        foreach ($this->getAllByUserId($userId) as $kegiatan) {
            $rows[] = [
                'uuid'                => $kegiatan->uuid,
                'judul_kegiatan'      => $kegiatan->judul_kegiatan,
                'jenis_kegiatan'      => $kegiatan->jenis_kegiatan,
                'jenis_badge_class'   => $this->mapJenisBadgeClass($kegiatan->jenis_kegiatan),
                'klaster_label'       => $kegiatan->klaster_skala_kegiatan,
                'klaster_badge_class' => $this->mapKlasterBadgeClass($kegiatan->klaster_skala_kegiatan),
                'tahun'               => $kegiatan->tahun,
                'show_url'            => site_url('dosen/kegiatan-mandiri/show/' . $kegiatan->uuid),
                'edit_url'            => site_url('dosen/kegiatan-mandiri/edit/' . $kegiatan->uuid),
                'delete_url'          => site_url('dosen/kegiatan-mandiri/delete/' . $kegiatan->uuid),
            ];
        }

        return ['tableRows' => $rows];
    }

    public function getKegiatanByUuidAndUser(string $uuid, int $userId): ?object
    {
        return $this->kegiatanMandiriModel
            ->where('uuid', $uuid)
            ->where('user_id', $userId)
            ->first();
    }

    public function getDetailPayloadForDosen(string $uuid, int $userId): ?array
    {
        $kegiatan = $this->getKegiatanByUuidAndUser($uuid, $userId);
        if (!$kegiatan) {
            return null;
        }

        $withUser = $this->kegiatanMandiriModel
            ->select('kegiatan_mandiri.*, users.username, users.nama_lengkap')
            ->join('users', 'users.id = kegiatan_mandiri.user_id')
            ->where('kegiatan_mandiri.id', $kegiatan->id)
            ->first();
        $kegiatan = $withUser ?? $kegiatan;
        $displayName = $kegiatan->nama_lengkap ?? $kegiatan->username ?? '-';

        return [
            'hero' => [
                'title'               => $kegiatan->judul_kegiatan,
                'subtitle'            => $displayName,
                'jenis_label'         => $kegiatan->jenis_kegiatan,
                'jenis_badge_class'   => $this->mapJenisBadgeClass($kegiatan->jenis_kegiatan),
                'klaster_label'       => $kegiatan->klaster_skala_kegiatan,
                'klaster_badge_class' => $this->mapKlasterBadgeClass($kegiatan->klaster_skala_kegiatan),
                'tahun'               => $kegiatan->tahun,
            ],
            'summaryItems' => [
                ['label' => 'Tahun Pelaksanaan', 'value' => (string) $kegiatan->tahun],
                ['label' => 'Jenis Kegiatan', 'value' => $kegiatan->jenis_kegiatan],
                ['label' => 'Klaster/Skala', 'value' => $kegiatan->klaster_skala_kegiatan],
                ['label' => 'Anggota Terlibat', 'value' => $this->valueOrDash($kegiatan->anggota_terlibat ?? '')],
            ],
            'detailItems' => [
                ['label' => 'Unit Pelaksana', 'value' => $this->valueOrDash($kegiatan->unit_pelaksana_kegiatan ?? '')],
                ['label' => 'Mitra Kolaborasi', 'value' => $this->valueOrDash($kegiatan->mitra_kolaborasi ?? '')],
                ['label' => 'Sumber Dana', 'value' => $this->valueOrDash($kegiatan->sumber_dana ?? '')],
                ['label' => 'Besaran Dana', 'value' => $this->formatCurrency($kegiatan->besaran_dana ?? null)],
            ],
            'resumeHtml' => $this->formatMultilineHtml($kegiatan->resume_kegiatan ?? ''),
            'evidence' => [
                'url'   => $kegiatan->tautan_bukti_dukung,
                'label' => $this->shortenUrl($kegiatan->tautan_bukti_dukung ?? ''),
            ],
            'actions' => [
                'back_url'   => site_url('dosen/kegiatan-mandiri'),
                'edit_url'   => site_url('dosen/kegiatan-mandiri/edit/' . $kegiatan->uuid),
                'delete_url' => site_url('dosen/kegiatan-mandiri/delete/' . $kegiatan->uuid),
            ],
        ];
    }

    public function getCreateFormPayloadForDosen(): array
    {
        return $this->buildFormPayload(null);
    }

    public function getEditFormPayloadForDosen(object $kegiatan): array
    {
        return $this->buildFormPayload($kegiatan);
    }

    // ================================================================
}
