<?php

namespace App\Helpers;

use Illuminate\Http\Request;

class RequestHelper
{
    public static function extractMonthAndYear(Request $request): array
    {
        $month = (int)self::extractQueryParam($request, 'month');
        $year = (int)self::extractQueryParam($request, 'year');

        return compact('month', 'year');
    }

    public static function extractQueryParam(Request $request, $paramName, $defaultValue = null): array|string|null
    {
        return $request->query($paramName, $defaultValue);
    }
}
