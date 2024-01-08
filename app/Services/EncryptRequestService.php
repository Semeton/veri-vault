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
    
    /**
     * Encrypt and store document
     *
     * @param User $user
     * @param Array $data
     * @return Document
     */
    public function encryptAndStoreDocument(User $user, Array $data): Document
    {
        $encryptedContent = $this->cryptoService->encrypt($data['body'], $data['secret']);

        $encryptedDocument = [
            'title' => $data['title'],
            'encrypted_content' => $encryptedContent,
            'uuid' => Str::uuid(),
        ];
        
        $document = $user->documents()->create($encryptedDocument);

        return $document;
    }

    /**
     * Update encrypted document
     *
     * @param User $user
     * @param Array $data
     * @param string $uuid
     * @return Document
     */
    public function encryptAndUpdateDocument(User $user, Array $data, string $uuid): Document
    {
        $encryptedContent = $this->cryptoService->encrypt($data['body'], $data['secret']);

        $user->documents()->where('uuid', $uuid)->update([
            'title' => $data['title'],
            'encrypted_content' => $encryptedContent,
        ]);

        $document = Document::where('uuid', $uuid)->first();

        return $document;
    }
}