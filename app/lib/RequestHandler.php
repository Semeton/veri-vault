<?php
declare(strict_types=1);

namespace App\Lib;

use Exception;
use Illuminate\Http\Request;
use App\Enums\HTTPResponseEnum;
use Illuminate\Http\JsonResponse;

class RequestHandler
{
    /**
     * Validates the incoming request against the provided rules.
     *
     * @param Request $request Incoming request to validate.
     * @param array $rules Validation rules.
     * @return array Validated data.
     */
    public function validateRequest(Request $request, array $rules): array
    {
        return $request->validate($rules);
    }

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
            return response()->json(
                [
                    "error" => "Exception",
                    "message" => $e->getMessage(),
                ],
                HTTPResponseEnum::BAD_REQUEST
            );
        }
    }
}
