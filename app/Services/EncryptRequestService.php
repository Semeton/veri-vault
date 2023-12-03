<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Document;
use Illuminate\Http\Request;

class EncryptRequestService {

    protected $cryptoService;

    public function __construct(CryptoService $cryptoService)
    {
        $this->cryptoService = $cryptoService;
    }
    
    public function storeDocument(Request $request): void
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
            'secret' => 'required',
            'access_permission_id' => 'required|exists:access_permissions,id',
        ]);

        $encryptedContent = $this->cryptoService->encrypt($validatedData['body'], $validatedData['secret']);

        Document::create([
            'title' => $validatedData['title'],
            'owner_id' => $request->user()->id,
            'encrypted_content' => $encryptedContent,
            'access_permission_id' => $validatedData['access_permission_id'],
        ]);
    }
}