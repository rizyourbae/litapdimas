<?php

namespace Config;

/**
 * ValidationMessages Configuration
 * 
 * Centralized validation messages untuk konsistensi across models
 * Mengurangi duplikasi dan memudahkan maintenance
 */

class ValidationMessages
{
    /**
     * Global validation messages (dapat digunakan semua model)
     * Pattern: field => rule => message
     */
    public static array $messages = [
        // Required field messages
        'required' => [
            'required'   => 'Wajib diisi.',
            'required_if' => 'Wajib diisi jika {param} diisi.',
            'required_unless' => 'Wajib diisi.',
        ],

        // Length messages
        'length' => [
            'min_length' => 'Minimal {param} karakter.',
            'max_length' => 'Maksimal {param} karakter.',
            'exact_length' => 'Harus {param} karakter.',
        ],

        // Format messages
        'format' => [
            'valid_email' => 'Format email tidak valid.',
            'valid_url' => 'Format URL tidak valid.',
            'valid_ip' => 'Format IP address tidak valid.',
            'alpha' => 'Hanya huruf yang diizinkan.',
            'numeric' => 'Hanya angka yang diizinkan.',
            'alpha_numeric' => 'Hanya huruf dan angka yang diizinkan.',
            'alpha_dash' => 'Hanya huruf, angka, dash, dan underscore yang diizinkan.',
        ],

        // Uniqueness messages
        'uniqueness' => [
            'is_unique' => 'Sudah ada di database.',
        ],

        // Date messages
        'date' => [
            'valid_date' => 'Format tanggal tidak valid.',
        ],
    ];

    /**
     * Common validation rule sets untuk reuse
     * Mengurangi duplikasi validation rules
     */
    public static array $ruleSets = [
        // Name field: required, min 3 chars, must be unique
        'name_field' => 'required|min_length[3]|is_unique[{table}.{field},id,{id}]',

        // Email field: required, valid format, must be unique
        'email_field' => 'required|valid_email|is_unique[{table}.{field},id,{id}]',

        // Username field: required, min 3 chars, alphanumeric dash, must be unique
        'username_field' => 'required|min_length[3]|alpha_dash|is_unique[{table}.{field},id,{id}]',

        // Password field: optional for updates, min 6 chars when provided
        'password_field' => 'permit_empty|min_length[6]',

        // ID field: permit empty for placeholder substitution
        'id_field' => 'permit_empty',
    ];

    /**
     * Get messages untuk specific field/rule combination
     * 
     * @param string $field Field name (required, length, format, uniqueness, date)
     * @param string|null $rule Specific rule atau null untuk semua
     * @return array
     */
    public static function getMessages(string $field, ?string $rule = null): array
    {
        if ($rule) {
            return [self::$messages[$field][$rule] ?? ''];
        }
        return self::$messages[$field] ?? [];
    }

    /**
     * Get rule set dan replace placeholders
     * 
     * @param string $key Rule set key (name_field, email_field, etc)
     * @param array $params Placeholders: table, field, id
     * @return string
     */
    public static function getRuleSet(string $key, array $params = []): string
    {
        $rule = self::$ruleSets[$key] ?? '';

        foreach ($params as $placeholder => $value) {
            $rule = str_replace('{' . $placeholder . '}', $value, $rule);
        }

        return $rule;
    }

    /**
     * Generate validation messages untuk model field
     * Merge global messages dengan custom overrides
     * 
     * @param string $field Field name
     * @param string|array $rules Rules applied to field (string with | separator)
     * @param array $customMessages Custom message overrides
     * @return array
     */
    public static function mergeMessages(
        string $field,
        $rules,
        array $customMessages = []
    ): array {
        $messages = [];

        // Convert to string if array
        if (is_array($rules)) {
            $rules = implode('|', $rules);
        }

        $ruleList = explode('|', $rules);

        foreach ($ruleList as $rule) {
            // Parse rule: min_length[3] => min_length
            $ruleName = explode('[', $rule)[0];

            // Check each message category
            foreach (self::$messages as $category => $categoryMessages) {
                if (isset($categoryMessages[$ruleName])) {
                    if ($field === '_generic') {
                        $messages[$ruleName] = $categoryMessages[$ruleName];
                    } else {
                        $messages[$field . '.' . $ruleName] = $categoryMessages[$ruleName];
                    }
                    break;
                }
            }
        }

        // Override dengan custom messages
        foreach ($customMessages as $key => $message) {
            $messages[$key] = $message;
        }

        return $messages;
    }
}
