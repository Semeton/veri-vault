<?php

namespace App\Http\Controllers\Api;

use App\Enums\HTTPResponseEnum;
use App\Http\Controllers\Controller;
use App\Lib\RequestHandler;
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
            $savedData = $this->user->encryptedEmails()->create($data);
            $mailData = $savedData->toArray();

            if (
                $this->emailService->sendEncryptedMail(
                    $data["recipient"],
                    $this->user,
                    $mailData
                )
            ) {
                return response()->json(
                    ["message" => "success"],
                    HTTPResponseEnum::OK
                );
            } else {
                return response()->json(
                    HTTPResponseEnum::getBadRequestMessage(),
                    HTTPResponseEnum::BAD_REQUEST
                );
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
