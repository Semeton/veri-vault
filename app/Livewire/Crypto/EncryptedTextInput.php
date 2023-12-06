<?php

namespace App\Livewire\Crypto;

use Livewire\Component;
use App\Services\CryptoService;
use Livewire\Attributes\Validate;

class EncryptedTextInput extends Component
{
    public $loading = false;
    public $alert = ['success' => false, 'error' => false, 'message' => ''];

    #[Validate('required')]
    public $encryptedText;
 
    public $secret = '';

    public function decryptMessage(CryptoService $cryptoService)
    {
        $this->loading = true;

        $response = $cryptoService->decrypt($this->encryptedText, $this->secret);
        
        $error = explode(':', $response)[0];
        if ($error == 'Error') {
            $this->alert['error'] = true ;
            $this->alert['success'] = false ;
            $this->alert['message'] = $response;
        }else{
            $this->dispatch('bodyUpdated', $response);
            $this->alert['success'] = true ;
            $this->alert['error'] = false ;
            $this->alert['message'] = 'Encrypted successfully';
        }
        $this->loading = false;
    }
    
    public function render()
    {
        return view('livewire.crypto.encrypted-text-input');
    }
}