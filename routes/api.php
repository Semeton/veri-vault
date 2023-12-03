<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MessageDecryptorController;
use App\Http\Controllers\Api\MessageEncryptorController;

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

Route::post('/messages/encrypt', [MessageEncryptorController::class, 'encryptMessage']);
Route::post('/messages/decrypt', [MessageDecryptorController::class, 'decryptMessage']);
Route::get('/', function(){
    return response()->json([
        'status' => true,
        'message' => 'Api server running'
    ]);
});