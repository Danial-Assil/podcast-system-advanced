<?php

namespace App\Exceptions;

use Exception;

class EmailNotVerifiedException extends Exception
{
    protected $message = 'Email address has not been verified.';
}
