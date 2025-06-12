<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'sanctum' => \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e) {
            return match (true) {
                $e instanceof \App\Exceptions\EmailNotVerifiedException =>
                response()->json(['status' => false, 'message' => $e->getMessage()], 403),

                $e instanceof \App\Exceptions\Invalid2FACodeException,
                $e instanceof \App\Exceptions\InvalidPasswordResetTokenException,
                $e instanceof \App\Exceptions\InvalidVerificationCodeException =>
                response()->json(['status' => false, 'message' => $e->getMessage()], 422),
                $e instanceof \App\Exceptions\InvalidCredentialsException =>
                response()->json(['status' => false, 'message' => $e->getMessage()], 401),

                $e instanceof \App\Exceptions\UserNotFoundException =>
                response()->json(['status' => false, 'message' => $e->getMessage()], 404),
                default => null
               // response()->json([
                //    'status' => false,
                 //   'message' => 'An unexpected error occurred. Please try again later.'
                //], 500),
            };
        });
    })->create();
