<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthenticateApiKey;
use App\Http\Controllers\Api\UserAuthController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::get('/', function(){
        return response()->json([
            'message' => 'Api server running'
        ]);
    });
    Route::prefix('users')->group(function () {
        Route::post('/register', [UserAuthController::class, 'register']);
        Route::post('/login', [UserAuthController::class, 'login']);
    });
    Route::middleware(['auth:sanctum'])->group(function(){
        Route::prefix('tokens')->group(function () {
            Route::get('/', [PersonalAccessTokenController::class, 'index']);
            Route::post('/create', [PersonalAccessTokenController::class, 'create']);
        });
        
        Route::prefix('messages')->group(function () {
            Route::get('/', [MessageEncryptorController::class, 'index']);
            Route::post('/', [MessageEncryptorController::class, 'store']);
            Route::put('/{uuid}', [MessageEncryptorController::class, 'update']);
            Route::delete('/{uuid}', [MessageEncryptorController::class, 'destroy']);
            Route::post('/decrypt', [MessageDecryptorController::class, 'decryptMessage']);
        });
    });
});