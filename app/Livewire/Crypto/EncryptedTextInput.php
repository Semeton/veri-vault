<?php

declare(strict_types=1);

namespace App\Livewire\Crypto;

use Livewire\Component;
use App\Services\CryptoService;

class EncryptedTextInput extends Component
{
    public bool $loading = false;
    public array $alert = ['success' => false, 'error' => false, 'message' => ''];

    protected $listeners = [];

    public string $encryptedText;
 
    public string $encryptSecret = '';

    public function mount()
    {
        $this->listeners = [];
        $this->alert = ['success' => false, 'error' => false, 'message' => ''];
    }

    public function decryptMessage(string $button, CryptoService $cryptoService): void
    {
        $this->validate([
            'encryptedText' => 'required',
            'encryptSecret' => 'required',
        ]);

        if ($button === 'decrypt' && $this->encryptedText !== '' && $this->encryptSecret !== '') {

            $this->loading = true;
            $this->dispatch('resetError');
    
            $response = $cryptoService->decrypt($this->encryptedText, $this->encryptSecret);
            
            $error = explode(':', $response)[0];
            if ($error === 'Error') {
                $this->alert['error'] = true ;
                $this->alert['success'] = false ;
                $this->alert['message'] = $response;
            } else {
                $this->dispatch('bodyUpdated', $response);
                $this->alert['success'] = true ;
                $this->alert['error'] = false ;
                $this->alert['message'] = 'Decrypted successfully';
            }
            $this->loading = false;
        }
    }
    
    public function render()
    {
        return view('livewire.crypto.encrypted-text-input');
    }
}