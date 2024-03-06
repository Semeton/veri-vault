<?php
namespace App\Services;

use App\Mail\EmailVerification;
use App\Models\User;
use App\Mail\SendEncryptedMail;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    public function __construct()
    {
    }

    public function sendEncryptedMail(
        string $recipientEmail,
        User $user,
        array $mailData
    ) {
        Mail::to($recipientEmail)->send(
            new SendEncryptedMail($user, $mailData)
        );
    }

    public function sendEmailVerificationMail(array $mailData)
    {
        Mail::to($mailData["email"])->send(new EmailVerification($mailData));
    }
}
