<?php
declare(strict_types=1);
namespace App\Http\Controllers\Api;

use App\Actions\Fortify\CreateNewUser;
use App\Enums\HTTPResponseEnum;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EmailVerificationToken;
use App\Services\EmailService;
use Illuminate\Support\Facades\Auth;

class UserAuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|string|email|max:255|unique:users",
            "password" => "required|string|min:8",
            "password_confirmation" => "required|string|min:8",
            "terms" => "required|string",
        ]);
        $cUser = new CreateNewUser();
        $user = $cUser->create($validatedData);

        $this->sendVerificationToken($user["email"]);

        return response()->json(
            [
                "message" =>
                    "Account created successfull. Check your email for the verification code",
            ],
            HTTPResponseEnum::CREATED
        );
    }

    public function sendVerificationToken(string $email)
    {
        $token = mt_rand(100000, 999999);
        $user = User::where("email", $email)->first();

        if (!$user) {
            return response()->json(
                [
                    "error" => "notFound",
                    "message" => $email . " is not registered",
                ],
                HTTPResponseEnum::NOT_FOUND
            );
        }

        if (!is_null($user->email_verified_at)) {
            return response()->json(
                [
                    "message" => "Email already verified",
                ],
                HTTPResponseEnum::OK
            );
        }
        $data = [
            "token" => $token,
        ];
        if ($token = $this->checkIfVerificationTokenExists($user->id)) {
            $token->update($data);
        } else {
            $data["user_id"] = $user->id;
            EmailVerificationToken::create($data);
        }

        $mailData = [
            "email" => $user->email,
            "name" => $user->name,
            "token" => $data["token"],
        ];

        $emailService = new EmailService();
        $emailService->sendEmailVerificationMail($mailData);

        return response()->json(
            [
                "message" => "Verification token sent successfully",
            ],
            HTTPResponseEnum::OK
        );
    }

    private function checkIfVerificationTokenExists(
        int $userId
    ): ?EmailVerificationToken {
        return EmailVerificationToken::where("user_id", $userId)->first();
    }

    public function verify($token)
    {
        $token = EmailVerificationToken::where("token", $token)->first();

        if (!$token) {
            return response()->json(
                [
                    "error" => "invalidToken",
                    "message" => "Token is invalid or has expired",
                ],
                HTTPResponseEnum::BAD_REQUEST
            );
        }

        $user = User::find($token->user_id);
        $user->email_verified_at = now();
        $user->save();

        $token->delete();

        $authToken = $user->createToken("auth_token")->plainTextToken;

        return response()->json(
            ["message" => "Email verified successfully", "token" => $authToken],
            HTTPResponseEnum::OK
        );
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            "email" => "required|string|email",
            "password" => "required|string",
        ]);

        if (Auth::attempt($credentials)) {
            $user = $request->user();
            $user->tokens()->where("name", "auth_token")->delete();
            $token = $user->createToken("auth_token")->plainTextToken;
            return response()->json(["token" => $token], 200);
        } else {
            return response()->json(
                [
                    "error" => "dataMismatch",
                    "message" => "Invalid credentials",
                ],
                401
            );
        }
    }

    public function me()
    {
        return response()->json(Auth::user(), HTTPResponseEnum::OK);
    }
}
