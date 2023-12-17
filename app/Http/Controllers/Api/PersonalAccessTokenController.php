<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PersonalAccessTokenController extends Controller
{
     public function index()
     {
         $user = Auth::user();
         $tokens = $user->tokens;
         return response()->json(['tokens' => $tokens]);
     }
    
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'token_name' => 'required|string',
            'abilities' => 'required'
        ]);
        $abilities = explode(',', $validatedData['abilities']);
        $token = $request->user()->createToken($request->token_name, $abilities);
        return response()->json(['token' => $token->plainTextToken, 'abilities' => $token->abilities], 201);
    }
}