<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\User;

class TooManyOtpAttemptsException extends Exception
{
    public function __construct(public int $retryAfter)
    {
        parent::__construct('Too many OTP attempts.');
    }
}
