<?php

namespace App\Models\Traits;

use Ramsey\Uuid\Uuid;

trait HasUuidTrait
{
    protected function initializeHasUuidTrait()
    {
        $this->allowedFields[] = 'uuid'; // pastikan kolom ada
        // Tambahkan event jika belum ada
        $this->beforeInsert = array_merge($this->beforeInsert ?? [], ['generateUuid']);
    }

    protected function generateUuid(array $data): array
    {
        if (empty($data['data']['uuid'])) {
            $data['data']['uuid'] = Uuid::uuid4()->toString();
        }
        return $data;
    }
}
