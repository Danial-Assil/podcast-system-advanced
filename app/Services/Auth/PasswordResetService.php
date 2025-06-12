<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Mail\PasswordResetMail;
use App\Services\Shared\MailService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\InvalidVerificationCodeException;

class PasswordResetService
{
    public function __construct(protected MailService $mailService) {}

    public function sendResetLink(string $email): void
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            throw new UserNotFoundException();
        }

        $token = bin2hex(random_bytes(32));
        Cache::put('password_reset_' . $email, $token, now()->addMinutes(60));

        $link = url("/api/reset-password?token={$token}&email=" . urlencode($email));

        $this->mailService->sendMailable($email, new PasswordResetMail($link));
    }

    public function resetPassword(array $data): void
    {
        $email = $data['email'];
        $token = $data['token'];
        $newPassword = $data['password'];

        $cachedToken = Cache::get('password_reset_' . $email);

        if (!$cachedToken || $cachedToken !== $token) {
            throw new InvalidVerificationCodeException();
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            throw new UserNotFoundException();
        }

        $user->password = Hash::make($newPassword);
        $user->save();

        Cache::forget('password_reset_' . $email);
    }
}
