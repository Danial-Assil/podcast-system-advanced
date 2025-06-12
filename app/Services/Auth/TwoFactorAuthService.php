<?php
namespace App\Services\Auth;

use App\Models\User;
use App\Services\Shared\CodeService;
use App\Services\Shared\TokenService;
use App\Exceptions\Invalid2FACodeException;

class TwoFactorAuthService
{
    public function __construct(
        protected CodeService $codeService,
        protected TokenService $tokenService
    ) {}

    public function verify(string $email, int $code): string
    {
        $cachedCode = $this->codeService->get('2fa_', $email);

        if (!$cachedCode || $cachedCode != $code) {
            throw new Invalid2FACodeException();
        }

        $user = User::where('email', $email)->firstOrFail();

        $this->codeService->forget('2fa_', $email);

        return $this->tokenService->create($user);
    }
}
