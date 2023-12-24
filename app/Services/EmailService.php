<?php 
namespace App\Services;

use App\Models\User;
use App\Mail\SendEncryptedMail;
use Illuminate\Support\Facades\Mail;

class EmailService{

    public function __construct()
    {
    }

    public function sendEncryptedMail(string $recipientEmail, User $user, Array $mailData)
    {
        Mail::to($recipientEmail)->send(new SendEncryptedMail($user, $mailData));
    }
}