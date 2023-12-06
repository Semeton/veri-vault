<?php

namespace App\Livewire\Crypto;

use Livewire\Component;
use App\Services\CryptoService;
use Livewire\Attributes\Validate;

class EncryptedTextInput extends Component
{
    public $loading = false;

    #[Validate('required')]
    public $encryptedText;
 
    public $secret = '';

    public function decryptMessage(CryptoService $cryptoService)
    {
        $this->loading = true;

        $encryptedText = $cryptoService->decrypt($this->encryptedText, $this->secret);
        
        $this->dispatch('bodyUpdated', $encryptedText);

        $this->loading = false;
    }
    
    public function render()
    {
        return view('livewire.crypto.encrypted-text-input');
    }
}