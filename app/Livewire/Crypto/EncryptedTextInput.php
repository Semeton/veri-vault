<?php

namespace App\Livewire\Crypto;

use Livewire\Component;

class EncryptedTextInput extends Component
{
    public $encryptedText;

    public function mount()
    {
        $this->encryptedText = '';
    }
    
    public function render()
    {
        return view('livewire.crypto.encrypted-text-input', ['encryptedText' => $this->encryptedText]);
    }
}