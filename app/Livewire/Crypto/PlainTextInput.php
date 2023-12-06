<?php

declare(strict_types=1);

namespace App\Livewire\Crypto;

use Livewire\Component;
use App\Services\CryptoService;

class PlainTextInput extends Component
{
    public bool $loading = false;
    public array $alert = ['success' => false, 'error' => false, 'message' => ''];

    protected array $listeners = [];
    
    public string $body = '';
 
    public string $secret = '';

    public function mount()
    {
        $this->listeners = [];
        $this->alert = ['success' => false, 'error' => false, 'message' => ''];
    }

    public function encryptMessage(string $button, CryptoService $cryptoService): void
    {
        $this->validate([
            'body' => 'required',
            'secret' => 'required',
        ]);
        
        if($button === 'encrypt' && $this->body !== '' && $this->secret !== ''){
            $this->loading = true;
            $this->dispatch('resetError');
    
            $response = $cryptoService->encrypt($this->body, $this->secret);
            
            $error = explode(':', $response)[0];
            if ($error === 'Error') {
                $this->alert['error'] = true ;
                $this->alert['success'] = false ;
                $this->alert['message'] = $response;
            } else {
                $this->dispatch('encryptedTextUpdated', $response);
                $this->alert['success'] = true ;
                $this->alert['error'] = false ;
                $this->alert['message'] = 'Encrypted successfully';
            }
            $this->loading = false;
        }
    }

    public function render()
    {
        return view('livewire.crypto.plain-text-input');
    }
}