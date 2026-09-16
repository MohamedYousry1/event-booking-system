<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Mail\Otp as OtpMail;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Exceptions\TooManyOtpAttemptsException;

class AuthController extends Controller
{
    private const OTP_TOKEN_NAME = 'otp-verification';

    private const API_TOKEN_NAME = 'api';

    public function __construct(private readonly OtpService $otpService) {}

    /**
     * Register a new user and issue a token scoped only to OTP verification.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = DB::transaction(function () use ($request): User {
            $user = User::create($request->validated());

            $code = $this->otpService->generate($user);

            Mail::to($user->email)->queue(new OtpMail($code));

            return $user;
        });

        return response()->json([
            'message' => 'User created successfully. Please verify your email.',
            'user' => $user,
            'token' => $this->issueOtpToken($user),
        ], 201);
    }

    /**
     * Resend the OTP for the authenticated (unverified) user.
     */
    public function resendOtp(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified'], 409);
        }

        if (! $this->otpService->canResend($user)) {
            return response()->json([
                'message' => 'Please wait before requesting another code.',
                'retry_after' => $this->otpService->secondsUntilResend($user),
            ], 429);
        }

        $code = $this->otpService->generate($user);

        Mail::to($user->email)->queue(new OtpMail($code));

        return response()->json(['message' => 'OTP sent successfully']);
    }

    /**
     * Verify the OTP, mark the email verified, and swap the scoped token for a full one.
     */
    public function verifyOtp(VerifyOtpRequest $request): JsonResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified'], 409);
        }

        try {
            if (! $this->otpService->verify($user, $request->validated('otp'))) {
                return response()->json([
                    'message' => 'Invalid or expired OTP',
                ], 422);
            }
        } catch (TooManyOtpAttemptsException $e) {
            return response()->json([
                'message' => 'Too many attempts. Please try again later.',
                'retry_after' => $e->retryAfter,
            ], 429);
        }

        $user->markEmailAsVerified();

        // Revoke the OTP-scoped token so it cannot be reused.
        $user->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Email verified successfully',
            'user' => $user,
            'token' => $this->issueApiToken($user),
        ]);
    }

    /**
     * Authenticate a user. Unverified users receive an OTP-scoped token instead of a full one.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        if (! Auth::validate($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = User::where('email', $request->validated('email'))->firstOrFail();

        if (! $user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email not verified',
                'token' => $this->issueOtpToken($user),
            ], 403);
        }


        // $token = $user->createToken(self::API_TOKEN_NAME, ['*'], now()->addHours(1))->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $this->issueApiToken($user),
        ]);
    }

    private function issueOtpToken(User $user): string
    {
        $user->tokens()->where('name', self::OTP_TOKEN_NAME)->delete();

        return $user->createToken(
            self::OTP_TOKEN_NAME,
            ['otp:verify'],
            now()->addMinutes(5),
        )->plainTextToken;
    }

    private function issueApiToken(User $user): string
    {
        $user->tokens()->where('name', self::API_TOKEN_NAME)->delete();
        return $user->createToken(
            self::API_TOKEN_NAME,
            ['*'],
            now()->addHours(1),
        )->plainTextToken;
    }
}
