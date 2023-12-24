<?php

namespace App\Livewire\Messages;

use Exception;
use App\Models\User;
use Livewire\Component;
use App\Services\EmailService;
use App\Services\CryptoService;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;

class EncryptedEmails extends Component
{
    public User $user;
    
    public bool $loading = false;
    
    #[Validate('required')] 
    public string $recipient = '';

    #[Validate('required')] 
    public string $subject = '';

    #[Validate('required')] 
    public string $body = '';

    #[Validate('required')] 
    public string $secret = '';
    

    public function mount()
    {
        $this->loading = false;
    }

    public function encryptEmail(string $button, CryptoService $cryptoService, EmailService $emailService)
    {
        if ($button === 'email') {
            $this->loading = true;

            try {
                $encryptedBody = $cryptoService->encrypt($this->body, $this->secret);

                $data = [
                    'subject' => $this->subject,
                    'encrypted_body' => $encryptedBody,
                ];

                $this->user = Auth::user();
                $savedData = $this->user->encryptedEmails()->create($data);
                $mailData = $savedData->toArray();

                $emailService->sendEncryptedMail($this->recipient, $this->user, $mailData);
                $this->loading = false;
                return redirect()->route('encryptAndSendMail')->with('success', 'Encrypted email sent successfully');
            } catch (Exception $e) {
                $this->loading = false;
                return redirect()->route('encryptAndSendMail')->with('error', $e->getMessage());
            }
        }
    }

    public function render()
    {
        return view('livewire.messages.encrypted-emails');
    }
}