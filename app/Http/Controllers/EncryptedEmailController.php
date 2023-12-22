<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EncryptedEmailController extends Controller
{
    public User $user;
    
    public function index()
    {
        $this->user = Auth::user();
        $encryptedEmails = $this->user->encryptedEmails()->get();
        return view('messages.emails.index', ['encryptedEmails' => $encryptedEmails]);
    }
}