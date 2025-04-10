<?php

namespace App\Http\Controllers\Core;

class ApiServiceController extends Controller
{
    protected $service;


    protected function badRequestResponse($errors)
    {
        return response()->badRequest($errors);
    }

    protected function notFoundResponse()
    {
        return response()->notFound();
    }

    protected function okResponse($data)
    {
        return response()->ok($data);
    }

    protected function createdResponse($data)
    {
        return response()->created($data);
    }

    protected function noContentResponse()
    {
        return response()->noContent();
    }

    protected function unauthorizedResponse($data)
    {
        return response()->unauthorized($data);
    }
}
