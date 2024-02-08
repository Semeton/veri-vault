<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserAuthController;
use App\Http\Controllers\Api\Chat\ChatController;
use App\Http\Controllers\Api\Chat\ChatMessageController;
use App\Http\Controllers\Api\Chat\ChatRequestController;
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
            Route::post('/', [PersonalAccessTokenController::class, 'create']);
        });
        
        Route::prefix('documents')->group(function () {
            Route::get('/', [MessageEncryptorController::class, 'index']);
            Route::get('/{uuid}', [MessageEncryptorController::class, 'show']);
            Route::post('/', [MessageEncryptorController::class, 'store']);
            Route::put('/{uuid}', [MessageEncryptorController::class, 'update']);
            Route::delete('/{uuid}', [MessageEncryptorController::class, 'destroy']);
            Route::prefix('decrypt')->group( function () {
                Route::post('/{uuid}', [MessageDecryptorController::class, 'decryptWithUuid']);
                Route::post('/', [MessageDecryptorController::class, 'decryptMessage']);
            });
        });

        Route::prefix('chats')->group(function () {
            Route::get('/', [ChatController::class, 'index']);
            Route::get('/show/{uuid}', [ChatController::class, 'show']);
            Route::put('/secret/{uuid}', [ChatController::class, 'setChatSecret']);
            Route::prefix('requests')->group(function () {
                Route::get('/', [ChatRequestController::class, 'index']);
                Route::post('/', [ChatRequestController::class, 'create']);
                Route::post('/accept/{uuid}', [ChatRequestController::class, 'acceptRequest']);
                Route::post('/reject/{uuid}', [ChatRequestController::class, 'rejectRequest']);
                Route::post('/block/{uuid}', [ChatRequestController::class, 'blockUserRequest']);
                Route::delete('/{uuid}', [ChatRequestController::class, 'delete']);
            });
            Route::prefix('/messages')->group(function () {
                Route::get('/{uuid}', [ChatMessageController::class, 'index']);
                Route::post('/{uuid}', [ChatMessageController::class, 'create']);
            });
        });
    });
});