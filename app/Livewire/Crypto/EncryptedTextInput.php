<?php

namespace App\Livewire\Crypto;

use Livewire\Component;
use Illuminate\Http\Request;
use App\Services\CryptoService;
use Livewire\Attributes\Validate;

class EncryptedTextInput extends Component
{
    public $loading = false;
    public $alert = ['success' => false, 'error' => false, 'message' => ''];

    protected $listeners = [];

    #[Validate('required')]
    public $encryptedText;
 
    #[Validate('required')]
    public $secret = '';

    public function mount()
    {
        // $this->loading = false;
        $this->listeners = [];
        $this->alert = ['success' => false, 'error' => false, 'message' => ''];
    }

    public function decryptMessage($button, CryptoService $cryptoService)
    {
        // $cryptoService = new CryptoService;
        if($button == 'decrypt'){

            $this->loading = true;
            $this->dispatch('resetError');
    
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