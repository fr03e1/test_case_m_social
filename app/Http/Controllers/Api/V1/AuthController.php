<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Http\Resources\V1\UserResource;
use App\Http\Services\AuthService;

class AuthController extends Controller
{

    public function __construct(
        private AuthService $authService,
    )
    {
        $this->middleware('customAuth')->except('register');
    }

    public function register(RegisterRequest $request): UserResource
    {
        $data = $request->validated();
        $user = $this->authService->register($data);
        return UserResource::make($user);
    }
}
