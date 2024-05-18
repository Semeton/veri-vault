<?php

namespace App\Http\Controllers\Api;

use App\Enums\HTTPResponseEnum;
use App\Http\Controllers\Controller;
use App\Lib\RequestHandler;
use App\Models\EncryptedEmail;
use App\Models\User;
use App\Services\CryptoService;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EncryptedEmailController extends Controller
{
    private User $user;

    public function __construct(
        public RequestHandler $requestHandler,
        private EmailService $emailService
    ) {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    public function index()
    {
        $encryptedEmails = $this->user->encryptedEmails()->get();
        foreach ($encryptedEmails as $item) {
            $item->created_at_hum = $item->created_at->format("Y-m-d H:i A");
            $item->updated_at_hum = $item->updated_at->format("Y-m-d H:i A");
        }
        return response()->json($encryptedEmails, HTTPResponseEnum::OK);
    }

    public function create(Request $request, CryptoService $cryptoService)
    {
        return $this->requestHandler->handleException(function () use (
            $request,
            $cryptoService
        ) {
            $data = $this->validateRequest($request);
            $encryptedBody = $cryptoService->encrypt(
                $data["body"],
                $data["secret"]
            );
            $data["encrypted_body"] = $encryptedBody;

            if ($savedData = $this->user->encryptedEmails()->create($data)) {
                $mailData = $savedData->toArray();
                $this->emailService->sendEncryptedMail(
                    $data["recipient"],
                    $this->user,
                    $mailData
                );

                return response()->json(
                    ["message" => "Encrypted email sent successfully"],
                    HTTPResponseEnum::OK
                );
            }
        });
    }

    public function delete(Request $request, $uuid)
    {
        return $this->requestHandler->handleException(function () use (
            $request,
            $uuid
        ) {
            $exist = EncryptedEmail::where("uuid", $uuid)->get();

            if (count($exist) == 0) {
                return response()->json(
                    [
                        "error" => "NotFound",
                        "message" => "EncryptedEmail does not exist",
                        "details" => [
                            "request" => "Delete encrypted email",
                            "uuid" => $uuid,
                        ],
                    ],
                    404
                );
            } else {
                $encryptedEmail = EncryptedEmail::where("uuid", $uuid)
                    ->where("user_id", Auth::id())
                    ->first();

                if ($encryptedEmail) {
                    $bearerToken = $request->bearerToken();
                    if ($bearerToken && $request->user()->tokenCan("delete")) {
                        $encryptedEmail->delete();
                        return response()->json([
                            "message" =>
                                "Encrypted email deleted successfully",
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
        });
    }

    private function validateRequest(Request $request)
    {
        $data = $this->requestHandler->validateRequest($request, [
            "subject" => "required|string",
            "body" => "required|string",
            "recipient" => "required|email",
            "secret" => "required|string",
        ]);

        return $data;
    }
}