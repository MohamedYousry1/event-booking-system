<?php

// app/Services/OtpService.php
namespace App\Services;

use App\Models\User;
use App\Models\Otp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use App\Exceptions\TooManyOtpAttemptsException;

class OtpService
{
    private const EXPIRY_MINUTES = 2;
    private const RESEND_COOLDOWN_SECONDS = 60;
    private const MAX_VERIFY_ATTEMPTS = 3;

    public function generate(User $user): string
    {
        $code = (string) random_int(100000, 999999);

        Otp::updateOrCreate(
            ['user_id' => $user->id],
            [
                'otp' => Hash::make($code),
                'expires_at' => now()->addMinutes(self::EXPIRY_MINUTES),
            ]
        );

        RateLimiter::clear('otp-verify:' . $user->id);

        RateLimiter::hit($this->throttleKey($user), self::RESEND_COOLDOWN_SECONDS);

        return $code;
    }

    public function canResend(User $user): bool
    {
        return !RateLimiter::tooManyAttempts($this->throttleKey($user), 1);
    }

    public function secondsUntilResend(User $user): int
    {
        return RateLimiter::availableIn($this->throttleKey($user));
    }


    public function verify(User $user, string $code): bool
    {
        $attemptKey = 'otp-verify:' . $user->id;

        if (RateLimiter::tooManyAttempts($attemptKey, self::MAX_VERIFY_ATTEMPTS)) {
            throw new TooManyOtpAttemptsException(RateLimiter::availableIn($attemptKey));
        }

        $otp = $user->otp;
        if (! $otp || $otp->expires_at->isPast() || ! Hash::check($code, $otp->otp)) {
            RateLimiter::hit($attemptKey, self::EXPIRY_MINUTES * 60);
            return false;
        }
        RateLimiter::clear($attemptKey);
        $otp->delete();
        return true;
    }

    // Key unique to the user for rate limiting ex: otp-resend:123
    private function throttleKey(User $user): string
    {
        return 'otp-resend:' . $user->id;
    }
}
