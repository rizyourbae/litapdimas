<?php

namespace App\Models\Traits;

trait SoftDeleteTrait
{
    protected $softDeleteField = 'deleted_at';

    protected function initializeSoftDeleteTrait()
    {
        $this->allowedFields[] = $this->softDeleteField;
        $this->allowCallbacks = true;

        // Tambahkan event untuk menangkap delete dan mengubahnya menjadi soft delete
        $this->beforeDelete = array_merge($this->beforeDelete ?? [], ['softDeleteCallback']);

        // Tambahkan global scope: setiap find akan mengecualikan data yang sudah dihapus
        $this->beforeFind = array_merge($this->beforeFind ?? [], ['addSoftDeleteCondition']);
    }

    protected function addSoftDeleteCondition(array $data): array
    {
        // Jika flag withDeleted tidak diset ke true, tambahkan kondisi deleted_at IS NULL
        if (empty($this->withDeleted)) {
            $this->where($this->table . '.' . $this->softDeleteField, null);
        }
        return $data;
    }

    protected function softDeleteCallback(array $data): array
    {
        // Ambil ID dari data yang akan dihapus
        $ids = $data['id'] ?? [];
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        foreach ($ids as $id) {
            // Update kolom deleted_at, bukan hapus permanen
            $this->update($id, [$this->softDeleteField => date('Y-m-d H:i:s')]);
        }

        // Batalkan penghapusan asli dengan mengosongkan data
        $data['id'] = [];
        return $data;
    }
}
