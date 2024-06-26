<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserAuthController;
use App\Http\Controllers\Api\Chat\ChatController;
use App\Http\Controllers\Api\Chat\ChatMessageController;
use App\Http\Controllers\Api\Chat\ChatRequestController;
use App\Http\Controllers\Api\EncryptedEmailController;
use App\Http\Controllers\Api\FeedBackController;
use App\Http\Controllers\Api\MessageDecryptorController;
use App\Http\Controllers\Api\MessageEncryptorController;
use App\Http\Controllers\Api\PersonalAccessTokenController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware("auth:sanctum")->get("/user", function (Request $request) {
    return $request->user();
});

Route::prefix("v1")->group(function () {
    Route::get("/", function () {
        return response()->json([
            "message" => "Api server running",
        ]);
    });
    Route::post("/feedback", [FeedBackController::class, "create"]);
    Route::prefix("users")->group(function () {
        Route::post("/register", [UserAuthController::class, "register"]);
        Route::get("/verify/token/{email}", [
            UserAuthController::class,
            "sendVerificationToken",
        ]);
        Route::get("/verify/{token}", [UserAuthController::class, "verify"]);
        Route::post("/login", [UserAuthController::class, "login"]);
        Route::middleware("auth:sanctum")->group(function () {
            Route::get("/me", [UserAuthController::class, "me"]);
            Route::get("/account-info", [
                UserAuthController::class,
                "accountInfo",
            ]);
            Route::get("/purge-data", [UserAuthController::class, "purgeData"]);
            Route::post("/update", [UserAuthController::class, "update"]);
            Route::post("/update/password", [
                UserAuthController::class,
                "updatePassword",
            ]);
        });
    });
    Route::middleware(["auth:sanctum"])->group(function () {
        Route::prefix("tokens")->group(function () {
            Route::get("/", [PersonalAccessTokenController::class, "index"]);
            Route::post("/", [PersonalAccessTokenController::class, "create"]);
        });

        Route::prefix("documents")->group(function () {
            Route::get("/", [MessageEncryptorController::class, "index"]);
            Route::get("/{uuid}", [MessageEncryptorController::class, "show"]);
            Route::post("/", [MessageEncryptorController::class, "store"]);
            Route::post("/{uuid}", [
                MessageEncryptorController::class,
                "update",
            ]);
            Route::get("/delete/{uuid}", [
                MessageEncryptorController::class,
                "destroy",
            ]);
            Route::prefix("decrypt")->group(function () {
                Route::get("/{uuid}/{secret}", [
                    MessageDecryptorController::class,
                    "decryptWithUuid",
                ]);
                Route::post("/", [
                    MessageDecryptorController::class,
                    "decryptMessage",
                ]);
            });
        });

        Route::prefix("chats")->group(function () {
            Route::get("/", [ChatController::class, "index"]);
            Route::get("/show/{uuid}", [ChatController::class, "show"]);
            Route::get("/unlock/{uuid}", [ChatController::class, "unlock"]);
            Route::post("/secret/{uuid}", [
                ChatController::class,
                "setChatSecret",
            ]);
            Route::prefix("requests")->group(function () {
                Route::get("/", [ChatRequestController::class, "index"]);
                Route::post("/", [ChatRequestController::class, "create"]);
                Route::get("/accept/{uuid}", [
                    ChatRequestController::class,
                    "acceptRequest",
                ]);
                Route::get("/reject/{uuid}", [
                    ChatRequestController::class,
                    "rejectRequest",
                ]);
                Route::post("/block/{uuid}", [
                    ChatRequestController::class,
                    "blockUserRequest",
                ]);
                Route::delete("/{uuid}", [
                    ChatRequestController::class,
                    "delete",
                ]);
            });
            Route::prefix("/messages")->group(function () {
                Route::get("/{uuid}/{secret}", [
                    ChatMessageController::class,
                    "index",
                ]);
                Route::post("/{uuid}", [
                    ChatMessageController::class,
                    "create",
                ]);
            });
        });

        Route::prefix("emails")->group(function () {
            Route::get("/", [EncryptedEmailController::class, "index"]);
            Route::post("/", [EncryptedEmailController::class, "create"]);
            Route::get("/delete/{uuid}", [
                EncryptedEmailController::class,
                "delete",
            ]);
        });
    });
});
