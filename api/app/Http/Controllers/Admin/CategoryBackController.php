<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\RetrieveCategoriesByParamsHelper;
use App\Http\Controllers\Core\ApiServiceController;
use App\Modules\Categories\Services\CategoryBackService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryBackController extends ApiServiceController
{

    public function __construct(CategoryBackService $service)
    {
        $this->service = $service;
    }

    public function get(int $categoryId): JsonResponse
    {
        $category = $this->service->get($categoryId);
        if (!$category) return $this->notFoundResponse();
        return $this->okResponse($category);
    }

    public function getAll(Request $request): JsonResponse
    {
        $categories = RetrieveCategoriesByParamsHelper::retrieveCategoriesByParams($this->service, $request);
        return $this->okResponse($categories);
    }

    public function create(Request $request): JsonResponse
    {
        $category = $this->service->create($request->all(), 'create-update');

        if ($this->service->hasErrors()) {
            $errors = $this->service->getErrors();
            return $this->badRequestResponse($errors);
        }

        return $this->createdResponse($category);
    }

    public function update(Request $request, int $categoryId): JsonResponse
    {
        $category = $this->service->get($categoryId);
        if (!$category) return $this->notFoundResponse();

        $category = $this->service->update($categoryId, $request->all(), 'create-update');

        if ($this->service->hasErrors()) {
            $errors = $this->service->getErrors();
            return $this->badRequestResponse($errors);
        }

        return $this->okResponse($category);
    }

    public function delete(int $categoryId)
    {
        $category = $this->service->get($categoryId);
        if (!$category) return $this->notFoundResponse();

        $deleteSucceeded = $this->service->delete($categoryId);
        if (!$deleteSucceeded) return $this->badRequestResponse("Category could not be deleted.");

        return $this->noContentResponse();
    }
}
