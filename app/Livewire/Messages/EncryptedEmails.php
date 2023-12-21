<?php

namespace App\Livewire\Messages;

use Livewire\Component;
use App\Services\CryptoService;

class EncryptedEmails extends Component
{
    public bool $loading = false;
    public array $alert = ['success' => false, 'error' => false, 'message' => ''];
    
    public string $subject = '';
    public string $body = '';
    public string $secret = '';

    public function mount()
    {
        $this->alert = ['success' => false, 'error' => false, 'message' => ''];
    }

    public function encryptEmail(string $button, CryptoService $cryptoService): void
    {
        $data = $this->validate([
            'subject' => 'required',
            'body' => 'required',
            'secret' => 'required',
        ]);
        
        dd($data);
        
        if($button === 'email' && $this->body !== '' && $this->secret !== ''){
            $this->loading = true;
            $this->dispatch('resetError');
    
            $response = $cryptoService->encrypt($this->body, $this->secret);
            
            $error = explode(':', $response)[0];
            if ($error === 'Error') {
                $this->alert['error'] = true ;
                $this->alert['success'] = false ;
                $this->alert['message'] = $response;
            } else {
                // $this->dispatch('encryptedTextUpdated', $response);
                $this->alert['success'] = true ;
                $this->alert['error'] = false ;
                $this->alert['message'] = 'Encrypted successfully';
            }
            $this->loading = false;
        }
    }

    public function render()
    {
        return view('livewire.messages.encrypted-emails');
    }
}