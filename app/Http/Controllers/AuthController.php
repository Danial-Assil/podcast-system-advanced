<?php
namespace App\Http\Controllers;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\{
    ForgetPasswordAuthRequest,
    RegisterAuthRequest,
    VerifyCodeAuthRequest,
    ResendCodeAuthRequest,
    LogInAuthRequest,
    ResetPasswordAuthRequest,
    Verify2FAAuthRequest
};
use App\Services\Auth\{
    RegisterService,
    VerificationService,
    LoginService,
    TwoFactorAuthService,
    PasswordResetService
};
use App\Services\Shared\TokenService;
use App\Traits\ApiResponseTrait;

class AuthController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected UserRepository $userRepository,
        protected VerificationService $verificationService,
        protected LoginService $loginService,
        protected TwoFactorAuthService $twoFAService,
        protected PasswordResetService $resetService,
        protected TokenService $tokenService
    ) {
    }

    public function register(RegisterAuthRequest $request)
{
    $user = $this->userRepository->create($request->validated());
    $code = $this->verificationService->resend($user->email);

    return $this->successResponse([
        'code' => $code
    ], 'User registered successfully. Verification code sent.');
}

    public function verifyCode(VerifyCodeAuthRequest $request)
    {
//        dd($request);
        $this->verificationService->verify(
            $request->validated()['email'],
            $request->validated()['code']
        );

        return $this->successResponse(null, 'Email verified successfully.');
    }

    public function resendCode(ResendCodeAuthRequest $request)
    {
        $code = $this->verificationService->resend($request->validated()['email']);

        return $this->successResponse([
            'code' => $code
        ], 'Verification code resent successfully.');
    }

    public function login(LogInAuthRequest $request)
    {
        $this->loginService->attempt($request->validated());

        return $this->successResponse(null, '2FA code sent to your email.');
    }

    public function verify2FA(Verify2FAAuthRequest $request)
    {
        $token = $this->twoFAService->verify(
            $request->validated()['email'],
            $request->validated()['code']
        );

        return $this->successResponse([
            'token' => $token
        ], 'Login successful.');
    }

    public function refreshToken()
{
    $user = Auth::guard('sanctum')->user();

    $newToken = $this->tokenService->refresh($user);

    return $this->successResponse([
        'token' => $newToken
    ], 'Token refreshed successfully.');
}

    public function sendPasswordResetLink(ForgetPasswordAuthRequest $request)
    {
        $this->resetService->sendResetLink($request->validated()['email']);

        return $this->successResponse(null, 'Password reset link sent successfully.');
    }

    public function resetPassword(ResetPasswordAuthRequest $request)
    {
        $this->resetService->resetPassword($request->validated());

        return $this->successResponse(null, 'Password reset successfully.');
    }
}
