<?php

declare(strict_types=1);

namespace App\Livewire\Crypto;

use Livewire\Component;
use App\Services\CryptoService;

class EncryptedTextInput extends Component
{
    public bool $loading = false;
    public array $alert = ['d-success' => false, 'd-error' => false, 'd-message' => ''];

    public string $encryptedText;
 
    public string $encryptSecret = '';

    public function mount()
    {
        $this->alert = ['d-success' => false, 'd-error' => false, 'd-message' => ''];
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
                $this->alert['d-error'] = true ;
                $this->alert['d-success'] = false ;
                $this->alert['d-message'] = $response;
            } else {
                $this->dispatch('bodyUpdated', $response);
                $this->alert['d-success'] = true ;
                $this->alert['d-error'] = false ;
                $this->alert['d-message'] = 'Decrypted successfully';
            }
            $this->loading = false;
        }
    }
    
    public function render()
    {
        return view('livewire.crypto.encrypted-text-input');
    }
}