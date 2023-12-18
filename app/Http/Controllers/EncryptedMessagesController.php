<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EncryptedMessagesController extends Controller
{
    public User $user;
    
    public function index()
    {
        $this->user = Auth::user();
        $documents = $this->user->documents()->get();
        return view('messages.encrypted.index', ['documents' => $documents]);
    }
}