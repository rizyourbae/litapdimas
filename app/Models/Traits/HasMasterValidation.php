<?php

namespace App\Models\Traits;

use Config\ValidationMessages;

/**
 * HasMasterValidation Trait
 * 
 * Provides centralized validation rules dan messages untuk master models
 * (Profesi, Bidang Ilmu, Fakultas, Program Studi, Jabatan Fungsional, Unit Kerja)
 * 
 * Setiap master model memiliki pattern sama:
 * - 'nama' field dengan unique constraint
 * - Soft delete + timestamps
 * 
 * Trait ini mengabstraksi pattern tersebut agar DRY
 */
trait HasMasterValidation
{
    /**
     * Generate validation rules untuk master model 'nama' field
     * Expects: $this->table dan $this->primaryKey defined
     * 
     * @return array
     */
    protected function getMasterNameValidationRules(): array
    {
        return [
            'nama' => ValidationMessages::getRuleSet('name_field', [
                'table' => $this->table,
                'field' => 'nama',
                'id'    => '{id}',
            ]),
        ];
    }

    /**
     * Generate validation messages untuk master model 'nama' field
     * 
     * @param array $customMessages Custom overrides untuk specific field
     * @return array
     */
    protected function getMasterNameValidationMessages(array $customMessages = []): array
    {
        $fieldMessages = [
            'nama' => [
                'required'   => 'Nama wajib diisi.',
                'min_length' => 'Minimal 3 karakter.',
                'is_unique'  => 'Nama sudah ada.',
            ],
        ];

        // Merge dengan custom messages
        if (!empty($customMessages)) {
            $fieldMessages['nama'] = array_merge($fieldMessages['nama'], $customMessages);
        }

        return $fieldMessages;
    }

    /**
     * Initialize validation rules dan messages
     * Call this in model's __construct() atau property definition
     * 
     * @param array $additionalRules Additional rules beyond 'nama'
     * @param array $additionalMessages Additional messages
     * @return void
     */
    protected function initializeMasterValidation(
        array $additionalRules = [],
        array $additionalMessages = []
    ): void {
        // Base rules
        $this->validationRules = $this->getMasterNameValidationRules();

        // Merge additional rules
        $this->validationRules = array_merge($this->validationRules, $additionalRules);

        // Base messages
        $this->validationMessages = $this->getMasterNameValidationMessages();

        // Merge additional messages
        if (!empty($additionalMessages)) {
            foreach ($additionalMessages as $field => $messages) {
                if (isset($this->validationMessages[$field])) {
                    $this->validationMessages[$field] = array_merge(
                        $this->validationMessages[$field],
                        $messages
                    );
                } else {
                    $this->validationMessages[$field] = $messages;
                }
            }
        }
    }
}
