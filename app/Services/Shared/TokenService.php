<?php
namespace App\Services\Shared;

use App\Models\User;

class TokenService
{
    public function create(User $user, string $name = 'auth_token'): string
    {
        return $user->createToken($name)->plainTextToken;
    }

    public function refresh(User $user, string $name = 'auth_token'): string
    {
        $user->tokens()->delete();
        return $this->create($user, $name);
    }

    public function revokeAll(User $user): void
    {
        $user->tokens()->delete();
    }
}
