<?php

namespace App\Services\User;

use App\Models\User\SintaProfileModel;
use DateTimeImmutable;
use Exception;

class SintaProfileService
{
    private SintaProfileModel $model;

    public function __construct()
    {
        $this->model = new SintaProfileModel();
    }

    public function getIndexPayload(int $userId): array
    {
        $profile = $this->model->where('user_id', $userId)->first();

        return [
            'profile' => $profile,
            'formValues' => [
                'id_sinta' => old('id_sinta', $profile->id_sinta ?? ''),
            ],
            'syncInfo' => [
                'status_label' => $profile->status_validasi_sinta ?? 'Belum Sinkron',
                'status_badge' => $this->resolveStatusBadge($profile->sync_status ?? 'never'),
                'last_synced_at' => $this->formatDatetime($profile->last_synced_at ?? null),
                'is_synced' => ($profile->sync_status ?? 'never') === 'success',
            ],
        ];
    }

    public function sync(int $userId, string $idSinta): void
    {
        $normalizedId = trim($idSinta);
        if ($normalizedId === '') {
            throw new Exception('ID SINTA wajib diisi.');
        }

        $fetchedData = $this->fetchSintaProfile($normalizedId);
        $existing = $this->model->where('user_id', $userId)->first();

        $payload = [
            'user_id'               => $userId,
            'id_sinta'              => $normalizedId,
            'nama_sinta'            => $fetchedData['nama_sinta'] ?? null,
            'sinta_score_all_years' => $fetchedData['sinta_score_all_years'] ?? null,
            'sinta_score_3_years'   => $fetchedData['sinta_score_3_years'] ?? null,
            'sinta_profile_url'     => $fetchedData['sinta_profile_url'] ?? null,
            'status_validasi_sinta' => $fetchedData['status_validasi_sinta'] ?? 'Tersinkronisasi',
            'sync_status'           => 'success',
            'sync_error_message'    => null,
            'raw_payload_json'      => json_encode($fetchedData, JSON_UNESCAPED_UNICODE),
            'last_synced_at'        => (new DateTimeImmutable())->format('Y-m-d H:i:s'),
        ];

        if ($existing) {
            $payload['id'] = $existing->id;
            if (!$this->model->save($payload)) {
                throw new Exception('Gagal menyimpan sinkronisasi: ' . implode(', ', $this->model->errors()));
            }
            return;
        }

        if (!$this->model->insert($payload)) {
            throw new Exception('Gagal menyimpan sinkronisasi: ' . implode(', ', $this->model->errors()));
        }
    }

    public function markSyncFailed(int $userId, string $idSinta, string $errorMessage): void
    {
        $existing = $this->model->where('user_id', $userId)->first();

        $payload = [
            'user_id'               => $userId,
            'id_sinta'              => trim($idSinta),
            'status_validasi_sinta' => 'Gagal Sinkronisasi',
            'sync_status'           => 'failed',
            'sync_error_message'    => $errorMessage,
            'last_synced_at'        => (new DateTimeImmutable())->format('Y-m-d H:i:s'),
        ];

        if ($existing) {
            $payload['id'] = $existing->id;
            $this->model->save($payload);
            return;
        }

        $this->model->insert($payload);
    }

    private function fetchSintaProfile(string $idSinta): array
    {
        $profileUrl = 'https://sinta.kemdiktisaintek.go.id/authors/profile/' . rawurlencode($idSinta);
        $client = service('curlrequest');

        $response = $client->request('GET', $profileUrl, [
            'http_errors' => false,
            'timeout' => 20,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (compatible; litapdimas-bot/1.0)',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            ],
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new Exception('Gagal mengambil data SINTA. HTTP ' . $response->getStatusCode());
        }

        $html = (string) $response->getBody();
        if (trim($html) === '') {
            throw new Exception('Respons SINTA kosong.');
        }

        $parsed = $this->parseSintaHtml($html, $idSinta);
        $parsed['sinta_profile_url'] = $profileUrl;

        if (empty($parsed['nama_sinta']) && $parsed['sinta_score_all_years'] === null && $parsed['sinta_score_3_years'] === null) {
            throw new Exception('Data SINTA tidak ditemukan atau struktur halaman berubah.');
        }

        return $parsed;
    }

    private function parseSintaHtml(string $html, string $idSinta): array
    {
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();

        $xpath = new \DOMXPath($dom);

        // Name: inside h3 > a in the profile header area
        $nama = $this->extractXpathText($xpath, "//div[contains(@class,'content-box')]//h3/a[1]");

        // SINTA Score Overall: div.pr-num immediately before div.pr-txt "SINTA Score Overall"
        $scoreAll = $this->extractScoreByLabel($xpath, 'SINTA Score Overall');

        // SINTA Score 3Yr: same pattern
        $score3 = $this->extractScoreByLabel($xpath, 'SINTA Score 3Yr');

        // SINTA ID from meta-profile link text: "SINTA ID : 6824588"
        $extractedId = $this->extractSintaId($xpath);
        if ($extractedId !== null && $extractedId !== $idSinta) {
            throw new Exception('ID SINTA dari sumber tidak sesuai dengan input.');
        }

        return [
            'nama_sinta'            => $nama,
            'id_sinta'              => $extractedId ?: $idSinta,
            'sinta_score_all_years' => $this->toDecimalOrNull($scoreAll),
            'sinta_score_3_years'   => $this->toDecimalOrNull($score3),
            'status_validasi_sinta' => 'Tersinkronisasi',
        ];
    }

    private function extractXpathText(\DOMXPath $xpath, string $query): ?string
    {
        $nodes = $xpath->query($query);
        if ($nodes === false || $nodes->length === 0) {
            return null;
        }

        $value = trim(preg_replace('/\s+/', ' ', $nodes->item(0)->textContent));
        return $value !== '' ? $value : null;
    }

    private function extractScoreByLabel(\DOMXPath $xpath, string $label): ?string
    {
        // Structure: <div class="pr-num">43</div><div class="pr-txt">SINTA Score Overall</div>
        $query = sprintf(
            "//div[contains(@class,'pr-txt') and contains(normalize-space(.), '%s')]/preceding-sibling::div[contains(@class,'pr-num')][1]",
            $label
        );

        $nodes = $xpath->query($query);
        if ($nodes === false || $nodes->length === 0) {
            return null;
        }

        $value = trim($nodes->item(0)->textContent);
        return $value !== '' ? $value : null;
    }

    private function extractSintaId(\DOMXPath $xpath): ?string
    {
        // Structure: <a href="#!"><i class="..."></i> SINTA ID : 6824588</a>
        $nodes = $xpath->query("//div[contains(@class,'meta-profile')]//a");
        if ($nodes === false) {
            return null;
        }

        foreach ($nodes as $node) {
            $text = trim($node->textContent);
            if (preg_match('/SINTA\s+ID\s*:\s*(\d+)/i', $text, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    private function toDecimalOrNull(?string $value): ?float
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        $normalized = str_replace(',', '.', trim($value));
        $normalized = preg_replace('/[^0-9\.\-]/', '', $normalized);

        if ($normalized === '' || !is_numeric($normalized)) {
            return null;
        }

        return (float) $normalized;
    }

    private function resolveStatusBadge(string $syncStatus): string
    {
        if ($syncStatus === 'success') {
            return 'success';
        }

        if ($syncStatus === 'failed') {
            return 'danger';
        }

        if ($syncStatus === 'partial') {
            return 'warning';
        }

        return 'secondary';
    }

    private function formatDatetime(?string $datetime): string
    {
        if (empty($datetime)) {
            return '-';
        }

        try {
            return (new DateTimeImmutable($datetime))->format('d M Y H:i');
        } catch (\Throwable $e) {
            return (string) $datetime;
        }
    }
}
