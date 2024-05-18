<?php
declare(strict_types=1);
namespace App\Http\Controllers\Api;

use App\Actions\Fortify\CreateNewUser;
use App\Enums\HTTPResponseEnum;
use App\Events\NewUserRegistration;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EmailVerificationToken;
use App\Services\EmailService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserAuthController extends Controller
{
    public User $user;
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

        event(new NewUserRegistration($user));

        return response()->json(
            ["message" => "Email verified successfully", "token" => $authToken],
            HTTPResponseEnum::OK
        );
    }

    public function update(Request $request)
    {
        try {
            $data = $request->validate([
                "username" => "required|string|unique:users",
                "name" => "required|string",
                "email" => "required|string|email",
            ]);

            $user = Auth::user();

            if (!$user instanceof User) {
                return response()->json(["error" => "UError"], 400);
                abort(
                    HTTPResponseEnum::UNAUTHENTICATED,
                    HTTPResponseEnum::getUnathorizedMessage()
                );
            }

            $user->update($data);

            return response()->json(
                ["message" => "User updated successfully"],
                200
            );
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()], 400);
        }
    }

    public function updatePassword(Request $request)
    {
        try {
            // Validate incoming request data
            $data = $request->validate([
                "old_password" => "required|string",
                "new_password" => "required|string|min:8",
                "new_password_confirmation" =>
                    "required|string|same:new_password",
            ]);

            $user = Auth::user();

            if (!$user instanceof User) {
                return response()->json(
                    ["error" => "User does not exist"],
                    400
                );
            }

            if (!Hash::check($data["old_password"], $user->password)) {
                return response()->json(
                    ["error" => "Old password entered is incorrect"],
                    400
                );
            }

            if (Hash::check($data["new_password"], $user->password)) {
                return response()->json(
                    [
                        "error" =>
                            "New password cannot be the same as the old password",
                    ],
                    400
                );
            }

            $user->password = Hash::make($data["new_password"]);
            $user->save();

            return response()->json(
                ["message" => "Password updated successfully"],
                200
            );
        } catch (ValidationException $e) {
            return response()->json(["error" => $e->errors()], 400);
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()], 400);
        }
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

    public function accountInfo()
    {
        $user = Auth::user();
    }

    public function purgeData()
    {
        try {
            $this->user = Auth::user();
            $this->user->sentChatRequests()->delete();
            $this->user->receivedChatRequests()->delete();
            $this->user->encryptedEmails()->delete();
            $this->user->documents()->delete();
            $this->user->chats()->each(function ($chat) {
                $chat->chatMessages()->delete();
            });
            return response()->json(
                [
                    "message" => "All data cleared successfully",
                ],
                200
            );
        } catch (Exception $e) {
            return response()->json(
                [
                    "message" => $e->getMessage(),
                ],
                400
            );
        }
    }
}
