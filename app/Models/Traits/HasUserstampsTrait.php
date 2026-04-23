<?php

namespace App\Models\Traits;

trait HasUserstampsTrait
{
    protected function initializeHasUserstampsTrait()
    {
        $this->allowedFields = array_merge($this->allowedFields, ['created_by', 'updated_by']);
        $this->beforeInsert = array_merge($this->beforeInsert ?? [], ['setCreatedBy']);
        $this->beforeUpdate = array_merge($this->beforeUpdate ?? [], ['setUpdatedBy']);
    }

    protected function setCreatedBy(array $data): array
    {
        if (!isset($data['data']['created_by'])) {
            $userId = null;
            if (service('auth')) {
                $userId = service('auth')->userId();
            }
            $data['data']['created_by'] = $userId;
        }
        return $data;
    }

    protected function setUpdatedBy(array $data): array
    {
        $userId = null;
        if (service('auth')) {
            $userId = service('auth')->userId();
        }
        $data['data']['updated_by'] = $userId;
        return $data;
    }
}
