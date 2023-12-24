<?php

use App\Http\Controllers\EncryptedMessagesController;
use App\Http\Controllers\EncryptedEmailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function (Request $request) {
    $info = [
        'ip' => $request->ip(),
        'userAgent' => $request->header('User-Agent'),
        'os' => $request->server('HTTP_SEC_CH_UA_PLATFORM')
    ];
    // return response()->json(['data' => $info]);
    return view('home', ['data' => $info]);
})->name('home');

Route::get('/email/{uuid}', [EncryptedEmailController::class, 'getSecret']);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::prefix('/user/')->group(function () {
        Route::get('/encrypted-messages', [EncryptedMessagesController::class, 'index'])->name('encryptedMessages');
        Route::get('/decrypted-messages', [EncryptedMessagesController::class, 'index'])->name('decryptedMessages');
        Route::get('/send-email', [EncryptedEmailController::class, 'index'])->name('encryptAndSendMail');
    });
});
// return redirect('encryptAndSendMail')