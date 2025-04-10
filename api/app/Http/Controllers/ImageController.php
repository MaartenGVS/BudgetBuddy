<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Core\ApiServiceController;
use App\Modules\Images\Services\ImageService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ImageController extends ApiServiceController
{

    public function __construct(ImageService $service)
    {
        $this->service = $service;
    }

    public function getCategoryIcon($fileName): BinaryFileResponse|JsonResponse
    {
        $path = $this->service->getCategoryIconPath($fileName);
        if (!$path) return $this->notFoundResponse();
        return response()->file($path);
    }
}
