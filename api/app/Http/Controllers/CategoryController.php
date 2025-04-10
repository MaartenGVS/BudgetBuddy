<?php

namespace App\Http\Controllers;

use App\Helpers\RetrieveCategoriesByParamsHelper;
use App\Http\Controllers\Core\ApiServiceController;
use App\Modules\Categories\Services\CategoryFrontService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class CategoryController extends ApiServiceController
{
    public function __construct(CategoryFrontService $service)
    {
        $this->service = $service;
    }

    public function getAll(Request $request): JsonResponse
    {
        $categories = RetrieveCategoriesByParamsHelper::retrieveCategoriesByParams($this->service, $request);
        return $this->okResponse($categories);
    }
}
