<?php

namespace App\Models\Master;

use App\Models\BaseModel;

class LandingSettingModel extends BaseModel
{
    protected $table            = 'landing_settings';
    protected $allowedFields    = ['key', 'value', 'group'];

    /**
     * Ambil value berdasarkan key.
     */
    public function getVal(string $key, $default = '')
    {
        $row = $this->where('key', $key)->first();
        return $row ? $row['value'] : $default;
    }

    /**
     * Ambil semua setting dalam satu group menjadi array asosiatif.
     */
    public function getGroup(string $group): array
    {
        $rows = $this->where('group', $group)->findAll();
        $result = [];
        foreach ($rows as $row) {
            $result[$row['key']] = $row['value'];
        }
        return $result;
    }

    /**
     * Update atau Insert setting berdasarkan key.
     */
    public function setVal(string $key, $value, string $group = 'general')
    {
        $existing = $this->where('key', $key)->first();
        if ($existing) {
            return $this->update($existing['id'], ['value' => $value, 'group' => $group]);
        }
        return $this->insert(['key' => $key, 'value' => $value, 'group' => $group]);
    }
}
