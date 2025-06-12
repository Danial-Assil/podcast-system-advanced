<?php

namespace App\Exceptions;

use Exception;

class Invalid2FACodeException extends Exception
{
    protected $message = 'Invalid or expired two-factor authentication code.';
}
