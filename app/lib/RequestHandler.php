<?php
declare(strict_types=1);

namespace App\Lib;

use Exception;
use App\Enums\HTTPResponseEnum;
use Illuminate\Http\JsonResponse;

class RequestHandler{
    
    /**
     * Handles exceptions for the chat request operations.
     * 
     * @param callable $callback Function to execute that may throw an exception.
     * @return JsonResponse Either the successful response from the callback or an error message.
     */
    public function handleException(callable $callback): JsonResponse
    {
        try {
            return $callback();
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Exception',
                'message' => $e->getMessage(),
            ], HTTPResponseEnum::BAD_REQUEST);
        }
    }
}