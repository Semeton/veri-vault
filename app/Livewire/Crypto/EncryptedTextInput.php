<?php

namespace App\Livewire\Crypto;

use Livewire\Component;
use App\Services\CryptoService;

class EncryptedTextInput extends Component
{
    protected $cryptoService;

    public $encryptedText;
 
    public $secret = '';

    public function decryptMessage()
    {
        $encryptedContent = $this->cryptoService->encrypt($this->body, $this->secret);

        return response()->json([
            'status' => true,
            'encypted' => $encryptedContent,
        ]);
    }
    
    public function render()
    {
        return view('livewire.crypto.encrypted-text-input', ['encryptedText' => $this->encryptedText]);
    }
}