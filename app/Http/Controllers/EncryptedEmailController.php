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
            return view('messages.emails.view', ['uuid' => $uuid]);
        } else {
            return redirect('home')->with('error', 'A problem was encountered');
        }
    }

    public function decryptEmail($uuid, Request $request)
    {
        $this->validate($request, [
            'secret' => 'required|string'
        ]);

        $encryptedEmail = EncryptedEmail::where('uuid', $uuid)->first();
        $decryptedBody = $this->cryptoService->decrypt($encryptedEmail['encrypted_body'], $request->secret);

        $error = explode(':', $decryptedBody)[0];
        if ($error === 'Error') {
            return redirect()->back()->with('error', $decryptedBody);
            // dd($decryptedBody);
        } else {
            return view('messages.emails.reveal', ['decryptedBody' => $decryptedBody]);
        }
    }

    public function delete($uuid)
    {
        $encryptedEmail = EncryptedEmail::where('uuid', $uuid)
            ->where('user_id', Auth::id())
            ->first();

        if($encryptedEmail){
            $encryptedEmail->delete();
            return redirect()->back()->with('success', 'Encrypted email deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Unauthorized to delete this encrypted email');
        }
    }

}