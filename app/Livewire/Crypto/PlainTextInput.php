<?php

namespace App\Livewire\Crypto;

use Livewire\Component;
use App\Services\CryptoService;
use Livewire\Attributes\Validate;

class PlainTextInput extends Component
{
    public $loading = false;
    public $alert = ['success' => false, 'error' => false, 'message' => ''];
    
    #[Validate('required')]
    public $body = '';
 
    #[Validate('required')]
    public $secret = '';

    public function encryptMessage(CryptoService $cryptoService)
    {
        $this->loading = true;
        $this->dispatch('resetError');

        $response = $cryptoService->encrypt($this->body, $this->secret);
        
        $error = explode(':', $response)[0];
        if ($error == 'Error') {
            $this->alert['error'] = true ;
            $this->alert['success'] = false ;
            $this->alert['message'] = $response;
        }else{
            $this->dispatch('encryptedTextUpdated', $response);
            $this->alert['success'] = true ;
            $this->alert['error'] = false ;
            $this->alert['message'] = 'Success';
        }
        $this->loading = false;
    }

    public function render()
    {
        return view('livewire.crypto.plain-text-input');
    }
}