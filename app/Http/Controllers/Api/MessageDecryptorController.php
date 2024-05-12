<?php

namespace App\Http\Controllers\Api;

use App\Enums\HTTPResponseEnum;
use Exception;
use App\Services\CryptoService;
use App\Models\Document;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
// use Semeton\CryptoService\CryptoService;

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
            "encrypted_content" => "required",
            "secret" => "required",
        ]);

        $bearerToken = $request->bearerToken();
        if ($bearerToken && $request->user()->tokenCan("read")) {
            $decryptedContent = $this->cryptoService->decrypt(
                $validatedData["encrypted_content"],
                $validatedData["secret"]
            );
            return response()->json([
                "document" => $decryptedContent,
            ]);
        } else {
            return response()->json(
                [
                    "message" =>
                        "You are not allowed to perform this operation",
                ],
                401
            );
        }
    }

    public function decryptWithUuid(
        string $uuid,
        string $secret,
        Request $request
    ) {
        try {
            $encryptedDocument = Document::where("uuid", $uuid)
                ->where("user_id", Auth::id())
                ->value("encrypted_content");

            if (!$encryptedDocument) {
                abort(
                    HTTPResponseEnum::NOT_FOUND,
                    HTTPResponseEnum::getNotFoundMessage(
                        "Decrypt document",
                        $uuid
                    )
                );
                // return response()->json(
                //     [
                //         "error" => "notFound",
                //         "message" =>
                //             "No document found with the provided UUID for this user",
                //     ],
                //     404
                // );
            }
            $bearerToken = $request->bearerToken();
            if ($bearerToken && $request->user()->tokenCan("read")) {
                $decryptedContent = $this->cryptoService->decrypt(
                    $encryptedDocument,
                    $secret
                );

                return response()->json([
                    "title" => Document::where("uuid", $uuid)
                        ->where("user_id", Auth::id())
                        ->value("title"),
                    "document" => $decryptedContent,
                ]);
            } else {
                abort(
                    HTTPResponseEnum::FORBIDDEN,
                    "You are not allowed to perform this operation"
                );
                // return response()->json(
                //     [
                //         "message" =>
                //             "You are not allowed to perform this operation",
                //     ],
                //     403
                // );
            }
        } catch (Exception $e) {
            abort(
                HTTPResponseEnum::BAD_REQUEST,
                "Decryption failed: invalid secret code"
            );
        }
    }
}
