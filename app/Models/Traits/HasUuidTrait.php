<?php

namespace App\Models\Traits;

use Ramsey\Uuid\Uuid;

/**
 * Trait HasUuidTrait
 *
 * @property array $allowedFields
 * @property array $beforeInsert
 */
trait HasUuidTrait
{
    protected function initializeHasUuidTrait()
    {
        // Pastikan properti ada dan aman untuk dimodifikasi sebelum mengaksesnya
        if (property_exists($this, 'allowedFields')) {
            if (!is_array($this->allowedFields)) {
                $this->allowedFields = (array) ($this->allowedFields ?? []);
            }
            if (!in_array('uuid', $this->allowedFields, true)) {
                $this->allowedFields[] = 'uuid'; // pastikan kolom ada
            }
        }

        // Tambahkan callback beforeInsert jika properti tersedia
        if (property_exists($this, 'beforeInsert')) {
            $this->beforeInsert = array_merge($this->beforeInsert ?? [], ['generateUuid']);
        }
    }

    protected function generateUuid(array $data): array
    {
        if (empty($data['data']['uuid'])) {
            $data['data']['uuid'] = Uuid::uuid4()->toString();
        }
        return $data;
    }
}
