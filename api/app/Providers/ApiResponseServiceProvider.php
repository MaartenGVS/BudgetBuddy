<?php

namespace App\Providers;

use App\Helpers\ErrorFormatter;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ApiResponseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Register any services if needed
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerErrorResponseMacros();
        $this->registerSuccessResponseMacros();
    }

    /**
     * Register custom response macros.
     *
     * @return void
     */

    private function registerSuccessResponseMacros(): void
    {
        response()->macro('created', function ($data) {
            return response()->json($data, ResponseAlias::HTTP_CREATED);
        });

        response()->macro('noContent', function () {
            return response()->json(null, ResponseAlias::HTTP_NO_CONTENT);
        });

        response()->macro('ok', function ($data) {
            return response()->json($data, ResponseAlias::HTTP_OK);
        });
    }

    private function registerErrorResponseMacros(): void
    {
        function errorResponse($errors, $status): JsonResponse
        {
            return response()->json(ErrorFormatter::format($errors), $status);
        }

        response()->macro('badRequest', function ($errors) {
            return errorResponse($errors, ResponseAlias::HTTP_BAD_REQUEST);
        });

        response()->macro('notFound', function () {
            return errorResponse('Resource not found', ResponseAlias::HTTP_NOT_FOUND);
        });

        response()->macro('created', function ($data) {
            return response()->json($data, ResponseAlias::HTTP_CREATED);
        });

        response()->macro('unauthorized', function ($data) {
            return response()->json($data, ResponseAlias::HTTP_UNAUTHORIZED);
        });
    }
}
