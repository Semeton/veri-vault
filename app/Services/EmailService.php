<?php
namespace App\Services;

use App\Mail\ChatActivityMail;
use App\Mail\ChatRequestMail;
use App\Mail\EmailVerification;
use App\Mail\FeedBackMail;
use App\Mail\NonUsersChatRequestMail;
use App\Models\User;
use App\Mail\SendEncryptedMail;
use App\Mail\WelcomeMail;
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

    public function sendWelcomeEmail(array $mailData)
    {
        Mail::to($mailData["email"])->send(new WelcomeMail($mailData));
    }

    public function sendChatRequestEmail(array $mailData)
    {
        Mail::to($mailData["email"])->send(new ChatRequestMail($mailData));
    }

    public function sendNonUsersChatRequestEmail(string $email)
    {
        Mail::to($email)->send(new NonUsersChatRequestMail($email));
    }

    public static function sendChatChatActivityEmail(array $mailData)
    {
        Mail::to($mailData["to"])->send(new ChatActivityMail($mailData));
    }

    public function sendFeedBackEmail(array $mailData)
    {
        Mail::to("feedbacks@verivault.xyz")->send(new FeedBackMail($mailData));
    }
}
