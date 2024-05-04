<?php

namespace App\Http\Controllers\Api;

use App\Enums\HTTPResponseEnum;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\CryptoService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\EncryptRequestService;
use App\Models\Document;
use App\Services\TokenPermissionService;
use Exception;

class MessageEncryptorController extends Controller
{
    public User $user;

    public function __construct(
        protected CryptoService $cryptoService,
        protected EncryptRequestService $encryptRequestService,
        protected TokenPermissionService $tokenPermissionService
    ) {
    }

    public function index(Request $request)
    {
        $this->user = Auth::user();
        if (!$this->tokenPermissionService->getTokenPermission($request, "*")) {
            return response()->json(
                HTTPResponseEnum::getUnathorizedMessage(),
                HTTPResponseEnum::FORBIDDEN
            );
        }
        $documents = $this->user
            ->documents()
            ->select("title", "uuid", "created_at", "updated_at")
            ->get();
        return response()->json($documents);
    }

    public function show($uuid, Request $request)
    {
        $this->user = Auth::user();
        if (
            !$this->tokenPermissionService->getTokenPermission($request, "read")
        ) {
            return response()->json(
                HTTPResponseEnum::getUnathorizedMessage(),
                HTTPResponseEnum::FORBIDDEN
            );
        }
        $document = $this->user->documents()->where("uuid", $uuid)->get();
        if (count($document) === 0) {
            return response()->json(
                HTTPResponseEnum::getNotFoundMessage(
                    "Get an encypted document",
                    $uuid
                ),
                HTTPResponseEnum::NOT_FOUND
            );
        }
        return response()->json($document);
    }

    public function store(Request $request)
    {
        // validation
        $validatedData = $request->validate([
            "body" => "required|string",
            "secret" => "required|string",
        ]);

        // persistence
        if ($request->has("persist")) {
            if (!$request["title"]) {
                return response()->json(
                    [
                        "message" => "The title field is required",
                        "errors" => ["title" => "The title field is required"],
                    ],
                    400
                );
            }

            if ($request["persist"] !== "true") {
                return response()->json(
                    [
                        "error" => "typeError",
                        "message" =>
                            "Invalid persist value. It should be set to `true`.",
                    ],
                    400
                );
            }

            if (!$request["persist"]) {
                return response()->json(
                    [
                        "error" => "typeError",
                        "message" =>
                            "The `persist` field must be set to `true`",
                    ],
                    400
                );
            }

            // validation
            $data = [
                "title" => $request["title"],
                "body" => $request["body"],
                "secret" => $request["secret"],
            ];

            if (
                !$this->tokenPermissionService->getTokenPermission(
                    $request,
                    "read"
                )
            ) {
                return response()->json(
                    HTTPResponseEnum::getUnathorizedMessage(),
                    HTTPResponseEnum::FORBIDDEN
                );
            }
            $encryptedContent = $this->encryptRequestService->encryptAndStoreDocument(
                $request->user(),
                $data
            );
            return response()->json([
                "message" => "Document encrypted successfully",
            ]);
        } else {
            $encryptedContent = $this->cryptoService->encrypt(
                $validatedData["body"],
                $validatedData["secret"]
            );
            return response()->json([
                "encypted" => $encryptedContent,
            ]);
        }
    }

    public function update(string $uuid, Request $request)
    {
        try {
            $data = $request->validate([
                "title" => "required|string",
                "body" => "required|string",
                "secret" => "required|string",
            ]);

            $exist = Document::where("uuid", $uuid)->get();

            if (count($exist) == 0) {
                return response()->json(
                    [
                        "error" => "NotFound",
                        "message" => "Document does not exist",
                        "details" => [
                            "request" => "Update encrypted document",
                            "uuid" => $uuid,
                        ],
                    ],
                    404
                );
            } else {
                $bearerToken = $request->bearerToken();
                if ($bearerToken && $request->user()->tokenCan("update")) {
                    $encryptedContent = $this->encryptRequestService->encryptAndUpdateDocument(
                        $request->user(),
                        $data,
                        $uuid
                    );
                    return response()->json([
                        "message" => "Document updated successfully",
                        "document" => $encryptedContent,
                    ]);
                } else {
                    return response()->json(
                        [
                            "error" => "Unauthorized",
                            "message" =>
                                "You are not allowed to perform this operation",
                        ],
                        403
                    );
                }
            }
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
            ]);
        }
    }

    public function destroy(string $uuid, Request $request)
    {
        try {
            $exist = Document::where("uuid", $uuid)->get();

            if (count($exist) == 0) {
                return response()->json(
                    [
                        "error" => "NotFound",
                        "message" => "Document does not exist",
                        "details" => [
                            "request" => "Update encrypted document",
                            "uuid" => $uuid,
                        ],
                    ],
                    404
                );
            } else {
                $encryptedEmail = Document::where("uuid", $uuid)
                    ->where("user_id", Auth::id())
                    ->first();

                if ($encryptedEmail) {
                    $bearerToken = $request->bearerToken();
                    if ($bearerToken && $request->user()->tokenCan("delete")) {
                        $encryptedEmail->delete();
                        return response()->json([
                            "message" =>
                                "Encrypted document deleted successfully",
                        ]);
                    } else {
                        return response()->json(
                            [
                                "error" => "Unauthorized",
                                "message" =>
                                    "You are not allowed to perform this operation",
                            ],
                            403
                        );
                    }
                } else {
                    return response()->json(
                        [
                            "error" => "Unauthorized",
                            "message" =>
                                "You are not allowed to perform this operation",
                        ],
                        403
                    );
                }
            }
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
            ]);
        }
    }
}
