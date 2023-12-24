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
    public array $alert = ['success' => false, 'error' => false, 'message' => ''];
    
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
        $this->alert = ['success' => false, 'error' => false, 'message' => ''];
    }

    public function encryptEmail(string $button, CryptoService $cryptoService, EmailService $emailService): void
    {
        if ($button === 'email') {
            $this->loading = true;
            $encryptedBody = $cryptoService->encrypt($this->body, $this->secret);

            $data = [
                'subject' => $this->subject,
                'body' => $encryptedBody,
            ];
            
            try {
                $this->user = Auth::user();
                $savedData = $this->user->encryptedEmails()->create($data);
                $mailData = $savedData->get();
                $emailService->sendEncryptedMail($this->recipient, $this->user, $mailData);
                $this->alert = ['success' => true, 'error' => false, 'message' => 'Encrypted email sent successfully'];
            } catch (Exception $e) {
                $this->alert = ['success' => false, 'error' => true, 'message' => $e->getMessage()];
            }
            $this->loading = false;
        }
    }

    public function render()
    {
        return view('livewire.messages.encrypted-emails');
    }
}