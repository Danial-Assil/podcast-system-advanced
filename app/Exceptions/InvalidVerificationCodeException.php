<?php

namespace App\Exceptions;

use Exception;

class InvalidVerificationCodeException extends Exception
{
    protected $message = 'Invalid or expired verification code.';
}
