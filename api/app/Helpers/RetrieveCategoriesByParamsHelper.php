<?php

namespace App\Helpers;
class RetrieveCategoriesByParamsHelper
{
    public static function retrieveCategoriesByParams($service, $request)
    {
        $type = RequestHelper::extractQueryParam($request, 'type', '');
        $search = RequestHelper::extractQueryParam($request, 'search', '');
        $perPage = RequestHelper::extractQueryParam($request, 'perPage', 10);
        $sortBy = RequestHelper::extractQueryParam($request, 'sortBy', 'name');

        $validSortDirections = ['asc', 'desc'];
        $sortDirection = RequestHelper::extractQueryParam($request, 'sortDirection', 'asc');
        $sortDirection = in_array($sortDirection, $validSortDirections) ? $sortDirection : 'asc';

        return $service->fetchCategories($type, $search, $perPage, $sortBy, $sortDirection);
    }
}
