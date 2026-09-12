<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Otp;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\Otp as OtpMail;
class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create($request->validated());
        $otp = rand(100000, 999999);
        Otp::create([
            'user_id' => $user->id,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(10),
        ]);
        #TODO: send otp
        Mail::to($user->email)->send(new OtpMail($otp));
        return response()->json([
            'message' => 'User created successfully',
            'user' => $user,
            'token' => $user->createToken('api')->plainTextToken,
        ], 201);
    }


    
    public function resendOtp(User $user){
        if($user->email_verified_at){
            return response()->json([
                'message' => 'Email already verified'
            ], 400);
        }
        $otp = $user->otp;
        $allowedResendTime = $otp?->updated_at?->addMinutes(1) ?? now()->subMinutes();
        if(!$otp || $otp->expires_at < now() || $allowedResendTime < now()){
            $newOtp = rand(100000, 999999);
                Otp::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'otp' => $newOtp,
                        'expires_at' => now()->addMinutes(10),
                    ]
                );
            Mail::to($user->email)->send(new OtpMail($newOtp));
            return response()->json([
                'message' => 'OTP sent successfully',
            ], 200);
        }else{
            return response()->json([
                'message' => 'you can resend otp after ' . $allowedResendTime->diffForHumans(),
            ], 400);
        }
    }
    public function verifyOtp(Request $request,User $user){
        if($user->email_verified_at){
            return response()->json([
                'message' => 'Email already verified'
            ], 400);
        }
        $otp = $user->otp;
        $validate = $request->validate([
            'otp' => 'required|string',
        ]);
        if(!$otp || $otp->otp !== $validate['otp'] || $otp->expires_at < now()){
            return response()->json([
                'message' => 'Invalid OTP'
            ], 401);
        }
        $user->update([
            'email_verified_at' => now(),
        ]);
        $otp->delete();

        return response()->json([
            'message' => 'Email verified successfully',
        ], 200);
    }



    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        } elseif(!$user->email_verified_at){
            return response()->json([
                'message' => 'Email not verified'
            ], 401);
        }


        // $user = Auth::user();

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $user->createToken('api', ['*'], now()->addHours(1))->plainTextToken
        ], 200);
    }
}
