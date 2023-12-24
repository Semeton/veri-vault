<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\EncryptedEmail;
use App\Services\CryptoService;
use Illuminate\Support\Facades\Auth;

class EncryptedEmailController extends Controller
{
    public User $user;

    public function __construct(public CryptoService $cryptoService)
    {
        
    }
    
    public function index()
    {
        $this->user = Auth::user();
        $encryptedEmails = $this->user->encryptedEmails()->get();
        return view('messages.emails.index', ['encryptedEmails' => $encryptedEmails]);
    }

    public function getSecret($uuid)
    {
        $exists = EncryptedEmail::where('uuid', $uuid)->exists();
        if($exists){
            return view('', ['uuid' => $uuid]);
        } else {
            return redirect('home')->with('error', 'Problem dey');
        }
    }

    public function decryptEmail($uuid, Request $request)
    {
        $this->validate($request, [
            'secret' => 'required|string'
        ]);

        $encryptedEmail = EncryptedEmail::where('uuid', $uuid)->first();
        $decryptedBody = $this->cryptoService->decrypt($encryptedEmail, $request->secret);

        $error = explode(':', $decryptedBody)[0];
            if ($error === 'Error') {
            return redirect('home')->with('error', 'Problem dey');
            } else {
               return view('', ['decryptedBody' => $decryptedBody]);
            }
        
        
    }
}