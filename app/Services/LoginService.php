<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Tymon\JWTAuth\JWTAuth;

class LoginService
{
    public function generateUserStorageData($user, $jwt)
    {
        $token = $jwt->fromUser($user);

        return [
            'data' => [
                'user' => $user->only('id', 'name'),
            ],
            'meta' => [
                'code' => '10200',
                'token' => $token,
            ]
        ];
    }
}
