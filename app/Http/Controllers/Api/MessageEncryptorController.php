<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\CryptoService;
use App\Http\Controllers\Controller;

class MessageEncryptorController extends Controller
{
    protected $cryptoService;

    public function __construct(CryptoService $cryptoService)
    {
        $this->cryptoService = $cryptoService;
    }

    public function encryptMessage(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
            'secret' => 'required',
        ]);

        $encryptedContent = $this->cryptoService->encrypt($validatedData['body'], $validatedData['secret']);

        return response()->json([
            'encypted' => $encryptedContent,
        ]);
    }
}