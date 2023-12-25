<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\CryptoService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\EncryptRequestService;

class MessageEncryptorController extends Controller
{
    protected $cryptoService;
    protected $encryptRequestService;

    public function __construct(CryptoService $cryptoService, EncryptRequestService $encryptRequestService)
    {
        $this->cryptoService = $cryptoService;
        $this->encryptRequestService = $encryptRequestService;
    }

    public function encryptMessage(Request $request)
    {
        $validatedData = $request->validate([
            'body' => 'required|string',
            'secret' => 'required|string',
        ]);

        if ($request->has('persist')) {
            
            if(!$request['title']){
                return response()->json([
                    'message' => 'The title field is required',
                    'errors' => ['title' => 'The title field is required'],
                ], 422);
            }
            
            $data = [
                'title' => $request['title'],
                'body' => $request['body'],
                'secret' => $request['secret'],
            ];

            $bearerToken = $request->bearerToken();
            if ($bearerToken && $request->user()->tokenCan('create')) {
                $encryptedContent = $this->encryptRequestService->encryptAndStoreDocument($request->user(), $data);
                return response()->json([
                    'document' => $encryptedContent,
                ]);
            } else {
                return response()->json([
                    'message' => 'You are not allowed to perform this operation'
                ], 401);
            }
        }else{
            $encryptedContent = $this->cryptoService->encrypt($validatedData['body'], $validatedData['secret']);
    
            return response()->json([
                'encypted' => $encryptedContent,
            ]);
        }
    }
}