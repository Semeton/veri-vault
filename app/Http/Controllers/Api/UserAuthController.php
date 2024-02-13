<?php

namespace App\Http\Controllers\Api;

use App\Enums\HTTPResponseEnum;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserAuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create(array_merge(
                    $validatedData,
                    ['password' => bcrypt($request->password)]
                ));

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $token = $request->user()->createToken('auth_token')->plainTextToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json([
                'error' => 'dataMismatch',
                'message' => 'Invalid credentials'
            ], 401);
        }

    }

    public function me()
    {
        return response()->json(Auth::user(), HTTPResponseEnum::OK);
    }

}