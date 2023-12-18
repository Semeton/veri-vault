<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Models\Document;
use Illuminate\Support\Str;

class EncryptRequestService {

    protected $cryptoService;

    public function __construct(CryptoService $cryptoService)
    {
        $this->cryptoService = $cryptoService;
    }
    
    public function encryptAndStoreDocument(User $user, $data): Document
    {
        $encryptedContent = $this->cryptoService->encrypt($data['body'], $data['secret']);

        $encryptedDocument = [
            'title' => $data['title'],
            'encrypted_content' => $encryptedContent,
            'uuid' => Str::uuid(),
        ];

        // dd($encryptedDocument);
        
        $document = $user->documents()->create($encryptedDocument);

        return $document;
    }
}