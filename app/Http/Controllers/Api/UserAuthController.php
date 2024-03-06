<?php
declare(strict_types=1);
namespace App\Http\Controllers\Api;

use App\Enums\HTTPResponseEnum;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EmailVerificationToken;
use App\Services\EmailService;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Boolean;

class UserAuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|string|email|max:255|unique:users",
            "password" => "required|string|min:8",
        ]);

        $user = User::create(
            array_merge($validatedData, [
                "password" => bcrypt($request->password),
            ])
        );

        $this->sendVerificationToken($user["email"]);

        // $token = $user->createToken("auth_token")->plainTextToken;

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

        // Code to send the verification token via email or SMS can be added here
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
        $user = EmailVerificationToken::where("token", $token)->first();

        if (!$user) {
            return response()->json(["error" => "invalidToken"], 400);
        }

        $user->email_verified_at = now();
        $user->email_verification_token = null;
        $user->save();

        return response()->json(
            ["message" => "Email verified successfully"],
            200
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
