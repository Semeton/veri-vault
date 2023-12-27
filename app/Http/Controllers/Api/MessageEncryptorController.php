<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\CryptoService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\EncryptRequestService;
use App\Livewire\Messages\EncryptedMessages;

class MessageEncryptorController extends Controller
{
    public User $user;

    public function __construct(protected CryptoService $cryptoService, protected EncryptRequestService $encryptRequestService)
    {
    }

    public function index(Request $request)
    {
        $this->user = Auth::user();
        $bearerToken = $request->bearerToken();
        if ($bearerToken && $request->user()->tokenCan('*')) {
            $documents = $this->user->documents()->select('id', 'title', 'uuid', 'created_at', 'updated_at')->get();
            return response()->json([
                'documents' => $documents
            ]);
        } else {
            return response()->json([
                'message' => 'You are not allowed to perform this operation'
            ], 403);
        }
        
    }

    public function store(Request $request)
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
                ], 403);
            }
        }else{
            $encryptedContent = $this->cryptoService->encrypt($validatedData['body'], $validatedData['secret']);
    
            return response()->json([
                'encypted' => $encryptedContent,
            ]);
        }
    }

    public function update(Request $request, string $uuid)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'secret' => 'required|string',
        ]);

        $bearerToken = $request->bearerToken();
        if ($bearerToken && $request->user()->tokenCan('update')) {
            $encryptedContent = $this->encryptRequestService->encryptAndUpdateDocument($request->user(), $data, $uuid);
            return response()->json([
                'document' => $encryptedContent,
            ]);
        } else {
            return response()->json([
                'message' => 'You are not allowed to perform this operation'
            ], 403);
        }
    }

    public function delete(string $uuid)
    {
        $encryptedEmail = EncryptedMessages::where('uuid', $uuid)
            ->where('user_id', Auth::id())
            ->first();

        if($encryptedEmail){
            $encryptedEmail->delete();
            return response()->json([
                'message' => 'Encrypted email deleted successfully'
            ], 200);
        } else {
            return response()->json([
                'message' => 'You are not allowed to perform this operation'
            ], 403);
        }
    }
}