<?php
namespace App\Validation;

use CodeIgniter\Database\Database;

class CustomRules
{
    public function is_exist(string $str, string $fields, array $data): bool
    {
        if (!is_string($str) | $str == '') {
            return false;  // or true, depending on whether you want “required” to catch it
        }

        list($table, $column) = explode('.', $fields);
        $db = \Config\Database::connect();

        $result = $db->table($table)->where($column, $str)->get()->getRowArray();
        return !empty($result);
    }

    /**
     * Validates a URL-safe path string
     * Example of valid: "admin-panel/dashboard_123"
     */
    public function validPath(string $str, ?string $fields = null, array $data = []): bool
    {
        // Only allow: letters, numbers, dash, underscore, slash
        return (bool) preg_match('#^[a-zA-Z0-9/_-]+$#', $str);
    }
}

