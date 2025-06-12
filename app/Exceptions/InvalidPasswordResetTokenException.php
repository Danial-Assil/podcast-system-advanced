<?php

namespace App\Exceptions;

use Exception;

class InvalidPasswordResetTokenException extends Exception
{
    protected $message = 'Invalid or expired password reset token.';
}
