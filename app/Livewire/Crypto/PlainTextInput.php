<?php

namespace App\Livewire\Crypto;

use Livewire\Component;
use App\Services\CryptoService;
use Livewire\Attributes\Validate;

class PlainTextInput extends Component
{
    public $encryptedText = '';
    
    #[Validate('required')]
    public $body = '';
 
    #[Validate('required')]
    public $secret = '';

    public function encryptMessage(CryptoService $cryptoService)
    {
        $encryptedText = $cryptoService->encrypt($this->body, $this->secret);

        $this->encryptedText = $encryptedText;
        $this->dispatch('encryptedTextUpdated', $encryptedText);
    }

    public function render()
    {
        return view('livewire.crypto.plain-text-input', ['body' => $this->body]);
    }
}