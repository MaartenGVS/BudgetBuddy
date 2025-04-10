<?php

namespace App\Helpers;

class ErrorFormatter
{
    public static function format($errors): array
    {
        return [
            "success" => false,
            "errors" => $errors
        ];
    }
}
