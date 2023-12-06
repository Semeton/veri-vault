<?php

namespace App\Livewire\Crypto;

use Livewire\Component;
use App\Services\CryptoService;
use Livewire\Attributes\Validate;

class PlainTextInput extends Component
{
    public $loading = false;
    public $alert = ['success' => false, 'error' => false, 'message' => ''];

    protected $listeners = [];
    
    #[Validate('required')]
    public $body = '';
 
    #[Validate('required')]
    public $secret = '';

    public function mount()
    {
        // $this->loading = false;
        $this->listeners = [];
        $this->alert = ['success' => false, 'error' => false, 'message' => ''];
    }

    public function encryptMessage($button, CryptoService $cryptoService)
    {
        // $cryptoService = new CryptoService;
        if($button == 'encrypt'){
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
    }

    public function render()
    {
        return view('livewire.crypto.plain-text-input');
    }
}