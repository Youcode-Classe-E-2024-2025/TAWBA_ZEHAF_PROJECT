<?php

namespace App\Helpers;

class SecurityHelper {
    public static function escapeHtml($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::escapeHtml($value);
            }
        } else {
            $data = htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
        return $data;
    }
}