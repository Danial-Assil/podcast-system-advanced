<?php
namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Services\Shared\CodeService;
use App\Services\Shared\MailService;
use App\Exceptions\InvalidCredentialsException;
use App\Exceptions\EmailNotVerifiedException;

class LoginService
{
    public function __construct(
        protected CodeService $codeService,
        protected MailService $mailService
    ) {}

    public function attempt(array $data): void
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw new InvalidCredentialsException();
        }

        if (!$user->email_verified_at) {
            throw new EmailNotVerifiedException();
        }

        $code = $this->codeService->generate('2fa_', $user->email);

        $this->mailService->sendRaw(
            $user->email,
            'Your 2FA Verification Code',
            "Your verification code is: {$code}"
        );
    }
}
