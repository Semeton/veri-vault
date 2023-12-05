<?php

namespace App\Livewire\Crypto;

use Livewire\Component;

class PlainTextInput extends Component
{
    public $plainText;
    
    public function mount()
    {
        $this->plainText = '';
    }

    public function render()
    {
        return view('livewire.crypto.plain-text-input', ['plainText' => $this->plainText]);
    }
}