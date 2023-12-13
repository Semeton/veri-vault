<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\CryptoService;
use App\Http\Controllers\Controller;

class MessageDecryptorController extends Controller
{
    protected $cryptoService;

    public function __construct(CryptoService $cryptoService)
    {
        $this->middleware('auth:api');
        $this->cryptoService = $cryptoService;
    }

    public function decryptMessage(Request $request)
    {
        $validatedData = $request->validate([
            'encrypted_content' => 'required',
            'secret' => 'required',
            // 'access_permission_id' => 'required|exists:access_permissions,id',
        ]);

        $decryptedContent = $this->cryptoService->decrypt($validatedData['encrypted_content'], $validatedData['secret']);

        return response()->json([
            'status' => true,
            'decypted' => $decryptedContent,
        ]);
    }
}