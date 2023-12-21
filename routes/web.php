<?php

use App\Http\Controllers\EncryptedMessagesController;
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

Route::get('/', function () {
    return view('home');
})->name('home');

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
        Route::get('/send-email', function () {
            return view('messages.emails.index');
        })->name('encryptAndSendMail');
    });
});
// 