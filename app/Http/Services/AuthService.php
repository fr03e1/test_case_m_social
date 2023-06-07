<?php

namespace App\Http\Services;

use App\Models\User;

class AuthService
{
    public function __construct(
        private User $user
    )
    {
    }

    public function register(array $data): User
    {
        return $this->user::create($data);
    }
}
