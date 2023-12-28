<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PersonalAccessTokenController extends Controller
{
     public function index(Request $request)
     {
        try{
            $user = Auth::user();
            $bearerToken = $request->bearerToken();
            if ($bearerToken && $request->user()->tokenCan('*')) {
                $tokens = $user->tokens;
                return response()->json(['tokens' => $tokens]);
            } else {
                return response()->json([
                    'error' => 'Unauthorized',
                    'message' => 'You are not allowed to perform this operation'
                ], 403);
            }
        } catch (Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
     }
    
    public function create(Request $request)
    {
        try{
            $validator = validator($request->all(), [
                'token_name' => 'required|string',
                'abilities' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Validation Error',
                    'message' => $validator->errors()
                ], 400);
            }

            $abilities = explode(',', $request->abilities);
            $bearerToken = $request->bearerToken();
            if ($bearerToken && $request->user()->tokenCan('*')) {
                $token = $request->user()->createToken($request->token_name, $abilities);
                return response()->json(['token' => $token->plainTextToken], 201);
            } else {
                return response()->json([
                    'error' => 'Unauthorized',
                    'message' => 'You are not allowed to perform this operation'
                ], 403);
            }
        } catch (Exception $e){
            dd($e);
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}