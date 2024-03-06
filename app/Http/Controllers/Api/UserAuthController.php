<?php

namespace App\Http\Controllers\Api;

use App\Enums\HTTPResponseEnum;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EmailVerificationToken;
use Illuminate\Support\Facades\Auth;

class UserAuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|string|email|max:255|unique:users",
            "password" => "required|string|min:8",
        ]);

        $user = User::create(
            array_merge($validatedData, [
                "password" => bcrypt($request->password),
            ])
        );

        $token = $user->createToken("auth_token")->plainTextToken;

        return response()->json(["token" => $token], 201);
    }

    public function sendVerificationToken(User $user)
    {
        $token = mt_rand(100000, 999999);
        $user->email_verification_token = $token;
        $user->save();

        // Code to send the verification token via email or SMS can be added here

        return response()->json(
            ["message" => "Verification token sent successfully"],
            200
        );
    }

    public function verify($token)
    {
        $user = EmailVerificationToken::where("token", $token)->first();

        if (!$user) {
            return response()->json(["error" => "invalidToken"], 400);
        }

        $user->email_verified_at = now();
        $user->email_verification_token = null;
        $user->save();

        return response()->json(
            ["message" => "Email verified successfully"],
            200
        );
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            "email" => "required|string|email",
            "password" => "required|string",
        ]);

        if (Auth::attempt($credentials)) {
            $user = $request->user();
            $user->tokens()->where("name", "auth_token")->delete();
            $token = $user->createToken("auth_token")->plainTextToken;
            return response()->json(["token" => $token], 200);
        } else {
            return response()->json(
                [
                    "error" => "dataMismatch",
                    "message" => "Invalid credentials",
                ],
                401
            );
        }
    }

    public function me()
    {
        return response()->json(Auth::user(), HTTPResponseEnum::OK);
    }
}
