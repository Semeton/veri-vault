<?php

namespace App\Http\Controllers\Api\Chat;

use Exception;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use App\Enums\HTTPResponseEnum;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public User $user;
    
    public function index()
    {
        try{
            $this->user = Auth::user();
            $chats = $this->user->chats;
            return response()->json($chats, HTTPResponseEnum::OK);
        } catch (Exception $e){
            return response()->json([
                'error' => 'Exception',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function show(string $uuid)
    {
        $this->user = Auth::user();
        $chat = $this->user->chats->where('uuid', $uuid)->firstOrFail();
        return response()->json($chat, HTTPResponseEnum::OK);
    }
}