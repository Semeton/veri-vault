<?php

namespace App\Http\Controllers\Api\Chat;

use Throwable;
use App\Models\User;
use App\Lib\RequestHandler;
use App\Enums\HTTPResponseEnum;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Handles chat-related operations for the API.
 */
class ChatController extends Controller
{
    /**
     * @var User Holds the authenticated user instance.
     */
    private User $authenticatedUser;

    /**
     * ChatController constructor.
     * Sets up middleware to authenticate user before any chat action.
     */
    public function __construct(public RequestHandler $requestHandler)
    {
        $this->middleware(function ($request, $next) {
            $this->authenticatedUser = Auth::user();
            return $next($request);
        });
    }
    
    /**
     * Retrieves a list of chats for the authenticated user.
     *
     * @return JsonResponse List of chats or an error message.
     */
    public function index(): JsonResponse
    {
        return $this->requestHandler->handleException(function () {
            $chats = $this->authenticatedUser->chats()->get();
            return response()->json($chats, JsonResponse::HTTP_OK);
        });
    }

    /**
     * Displays a specific chat identified by UUID.
     *
     * @param string $uuid The UUID of the chat to retrieve.
     * @return JsonResponse The requested chat or an error message.
     */
    public function show(string $uuid): JsonResponse
    {
        return $this->requestHandler->handleException(function () use ($uuid) {
            $chat = $this->validateUuid($uuid);
            $chat->role = $chat->sender_id === $this->authenticatedUser->id ? 'sender' : 'recipient';
            return response()->json($chat, JsonResponse::HTTP_OK);
        });
    }

    /**
     * Handles exceptions thrown during chat operations.
     *
     * @param Throwable $th The thrown exception.
     * @param string|null $uuid Optional UUID of the chat for not found exceptions.
     * @return Chat $chat with the requested uuid
     */
    private function validateUuid(string $uuid): Chat
    {
        $chat = $this->authenticatedUser->chats()->where('uuid', $uuid)->first();
        if (!$chat) {
            abort(HTTPResponseEnum::NOT_FOUND, 'Chat does not exist');
        }
        return $chat;
    }
}