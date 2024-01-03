<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
// use App\Services\CryptoService;
use App\Http\Controllers\Controller;
use Semeton\CryptoService\CryptoService;

class MessageDecryptorController extends Controller
{
    protected $cryptoService;

    public function __construct(CryptoService $cryptoService)
    {
        $this->cryptoService = $cryptoService;
    }

    public function decryptMessage(Request $request)
    {
        $validatedData = $request->validate([
            'encrypted_content' => 'required',
            'secret' => 'required',
        ]);

        $bearerToken = $request->bearerToken();
        if ($bearerToken && $request->user()->tokenCan('read')) {
            $decryptedContent = $this->cryptoService->decrypt($validatedData['encrypted_content'], $validatedData['secret']);
            return response()->json([
                'document' => $decryptedContent,
            ]);
        } else {
            return response()->json([
                'message' => 'You are not allowed to perform this operation'
            ], 401);
        }
    }
}