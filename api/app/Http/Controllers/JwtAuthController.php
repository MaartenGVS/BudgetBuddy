<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Core\ApiServiceController;
use App\Modules\Auth\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class JwtAuthController extends ApiServiceController
{

    public function __construct(AuthService $authService)
    {
        $this->service = $authService;
    }

    public function register(Request $request)
    {
        $user = $this->service->register($request->all());

        if ($this->service->hasErrors()) {
            $errors = $this->service->getErrors();
            return $this->badRequestResponse($errors);
        }

        return $this->createdResponse(
            [
                "status" => true, "message" => "User registered successfully", "userName" => $user->name
            ]
        );
    }

    public function login(Request $request): Response|JsonResponse
    {
        $tokens = $this->service->login($request->all());

        if ($this->service->hasErrors()) {
            $errors = $this->service->getErrors();
            return $this->badRequestResponse($errors);
        }

        if ($tokens["token"] === false) {
            return $this->unauthorizedResponse(["status" => false, "message" => "Invalid credentials"]);
        }

        return $this->respondWithToken(
            $tokens["token"],
            $tokens["csrfToken"],
            [
                "status" => true, "message" => "User profile fetched successfully", "user" => auth()->user()
            ]
        );
    }

    public function profile(): JsonResponse
    {
        $userdata = auth()->user();
        return $this->okResponse(
            [
                "status" => true, "message" => "User profile fetched successfully", "data" => $userdata
            ]
        );
    }

    public function logout(): JsonResponse
    {
        auth()->logout();
        return $this->okResponse(
            [
                "status" => true, "message" => "User logged out succcessfully"
            ]
        );
    }


    private function respondWithToken($token, $csrfToken, $message): Response
    {
        $ttl = env("JWT_COOKIE_TTL");
        $tokenCookie = cookie("token", $token, $ttl);
        $csrfCookie = cookie("X-XSRF-TOKEN", $csrfToken, $ttl);

        return response($message)
            ->withCookie($tokenCookie)
            ->withCookie($csrfCookie);
    }
}
