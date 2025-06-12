<?php

namespace App\Services\Auth;

use App\Exceptions\InvalidVerificationCodeException;
use App\Exceptions\UserNotFoundException;
use App\Models\User;
use App\Services\Shared\CodeService;
// use Illuminate\Support\Facades\Mail;
// use App\Mail\VerificationCodeMail;

class VerificationService
{
    public function __construct(protected CodeService $codeService) {}

    /**
     * تحقق من كود التفعيل المرسل إلى البريد
     */
    public function verify(string $email, string $code): void
    {
        $cachedCode = $this->codeService->get('verification_code_', $email);

        if (!$cachedCode) {
            throw new InvalidVerificationCodeException('Verification code expired or missing.');
        }

        if ($cachedCode !== $code) {
            throw new InvalidVerificationCodeException('Incorrect verification code.');
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            throw new UserNotFoundException();
        }

        $user->email_verified_at = now();
        $user->save();

        $this->codeService->forget('verification_code_', $email);
    }

    /**
     * إرسال كود التحقق مجددًا وتخزينه لمدة 10 دقائق
     */
    public function resend(string $email): string
    {
        $this->codeService->forget('verification_code_', $email);

        $code = $this->codeService->generate('verification_code_', $email, 600); // 600 ثانية = 10 دقائق

        // إرسال الكود بالبريد إن أردت
        // Mail::to($email)->send(new VerificationCodeMail($code));

        return $code;
    }
}
