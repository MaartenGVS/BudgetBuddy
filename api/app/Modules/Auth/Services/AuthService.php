<?php

namespace App\Modules\Auth\Services;

use App\Models\User;
use App\Modules\Core\Services\Service;
use Illuminate\Support\Facades\Hash;
use Nette\Utils\Random;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService extends Service
{

    protected $rules = [
        "register" => [
            "name" => "required|string",
            "email" => "required|email|unique:users",
            "password" => "required|string|confirmed"
        ],
        "login" => [
            "email" => "required|email",
            "password" => "required"
        ]
    ];


    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function register(array $data, $ruleKey = "register")
    {
        $this->validate($data, $ruleKey);

        if ($this->hasErrors()) return;

        return User::create([
                "name" => $data["name"],
                "email" => $data["email"],
                "password" => Hash::make($data["password"])
            ]
        );
    }

    public function login(array $data, $ruleKey = "login")
    {
        $this->validate($data, $ruleKey);

        if ($this->hasErrors()) return;

        $csrfToken = $this->generateCsrfToken();
        $credentials = [
            "email" => $data["email"],
            "password" => $data["password"]
        ];

        return [
            "token" => $this->attemptLogin($csrfToken, $credentials),
            "csrfToken" => $csrfToken
        ];
    }

    public function generateCsrfToken(): string
    {
        $csrfLength = env("CSRF_TOKEN_LENGTH");
        return Random::generate($csrfLength);
    }


    private function attemptLogin($csrfToken, array $credentials)
    {
        return JWTAuth::claims($this->defaultClaims($csrfToken))->attempt(
            [
                "email" => $credentials["email"],
                "password" => $credentials["password"]
            ]
        );
    }

    private function defaultClaims($csrfToken): array
    {
        return ['X-XSRF-TOKEN' => $csrfToken];
    }
}
