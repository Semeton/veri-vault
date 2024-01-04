<?php

namespace App\Http\Controllers\Api;

use Exception;
// use App\Services\CryptoService;
use App\Models\Document;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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

    public function decryptWithUuid(string $uuid, Request $request)
    {
        try{
            $validator = validator($request->all(), [
                'secret' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => 'validationError',
                    'message' => $validator->errors()
                ], 400);
            }
            $encryptedDocument = Document::where('uuid', $uuid)
                                ->where('user_id', Auth::id())
                                ->value('encrypted_content');

            if(!$encryptedDocument){
                return response()->json([
                    'error' => 'notFound',
                    'message' => "No document found with the provided UUID for this user"
                ], 404);
            }
            $bearerToken = $request->bearerToken();
            if ($bearerToken && $request->user()->tokenCan('read')) {
                $decryptedContent = $this->cryptoService->decrypt($encryptedDocument, $request['secret']);
                
                return response()->json([
                    'document' => $decryptedContent,
                ]);
            } else {
                return response()->json([
                    'message' => 'You are not allowed to perform this operation'
                ], 403);
            }
            
            
        } catch (Exception $e){
            return response()->json([
                'error' => 'Exception',
                'message' => $e->getMessage()
            ]);
        }
    }
}