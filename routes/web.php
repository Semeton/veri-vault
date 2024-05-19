<?php

use App\Events\ChatActivity;
use Illuminate\Http\Request;
use App\Models\EncryptedEmail;
use App\Services\EmailService;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EncryptedEmailController;
use App\Http\Controllers\EncryptedMessagesController;
use App\Models\Chat;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get("/chat-activity", function () {
//     $chat = Chat::find(17);
//     event(new ChatActivity($chat, "UNLOCKED"));
// });
Route::get("/", [HomeController::class, "index"])->name("home");
Route::get("/privacy-policy", function () {
    $policy = file_get_contents(resource_path("markdown/policy.md"));
    return view("policy", ["policy" => $policy]);
})->name("privacyPolicy");

Route::get("/terms-of-service", function () {
    $terms = file_get_contents(resource_path("markdown/terms.md"));
    return view("terms", ["terms" => $terms]);
})->name("termsOfService");

Route::get("/developer/docs", function () {
    return view("docs.index");
})->name("docs");

Route::get("/email/{uuid}", [
    EncryptedEmailController::class,
    "getSecret",
])->name("viewEncryptedEmail");
Route::post("/email/{uuid}", [
    EncryptedEmailController::class,
    "decryptEmail",
])->name("revealEncryptedMessage");

Route::middleware([
    "auth:sanctum",
    config("jetstream.auth_session"),
    "verified",
])->group(function () {
    Route::get("/dashboard", function () {
        return view("dashboard");
    })->name("dashboard");
    Route::prefix("/user/")->group(function () {
        Route::get("/encrypted-messages", [
            EncryptedMessagesController::class,
            "index",
        ])->name("encryptedMessages");
        Route::get("/decrypted-messages", [
            EncryptedMessagesController::class,
            "index",
        ])->name("decryptedMessages");
        Route::get("/send-email", [
            EncryptedEmailController::class,
            "index",
        ])->name("encryptAndSendMail");
        Route::get("/delete-email/{uuid}", [
            EncryptedEmailController::class,
            "delete",
        ])->name("deleteEncryptedMail");
    });
});
