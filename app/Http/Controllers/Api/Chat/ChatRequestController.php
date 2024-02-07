<?php
declare(strict_types=1);
namespace App\Http\Controllers\Api\Chat;

use Exception;
use App\Models\User;
use App\Models\ChatRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Enums\HTTPResponseEnum;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Services\ChatRequestService;
use Illuminate\Support\Facades\Auth;

/**
 * Handles chat request operations such as listing, creating, accepting, rejecting, blocking, and deleting chat requests.
 */
class ChatRequestController extends Controller
{
    /**
     * ChatRequestController constructor.
     * 
     * @param User $user Injected User model to interact with user data.
     * @param ChatRequestService $chatRequestService Injected service to handle business logic related to chat requests.
     */
    public function __construct(private User $user, private ChatRequestService $chatRequestService)
    {
    }

    /**
     * Lists all sent and received chat requests for the authenticated user.
     * 
     * @return JsonResponse List of chat requests.
     */
    public function index(): JsonResponse
    {
        return $this->handleException(function () {
            $this->user = Auth::user();
            $sentChatRequests = $this->user->sentChatRequests->each(function ($item) {
                $item->recipient_email = $item->recipient()->get();
            });
            $receivedChatRequests = $this->user->receivedChatRequests->each(function ($item) {
                $item->sender_email = $item->sender()->get();
            });

            return response()->json([
                'sent' => $sentChatRequests,
                'received' => $receivedChatRequests,
            ], HTTPResponseEnum::OK);
        });
    }

    /**
     * Creates a new chat request with the provided recipient email.
     * 
     * @param Request $request Incoming request containing 'recipient_email'.
     * @return JsonResponse Newly created chat request data.
     */
    public function create(Request $request): JsonResponse
    {
        return $this->handleException(function () use ($request) {
            $data = $this->validateRequest($request, [
                'recipient_email' => 'required|email|exists:users,email',
            ]);

            $this->user = Auth::user();
            $data['uuid'] = Str::uuid()->toString();

            $this->validateChatRequest($data);

            $createRequest = $this->user->sentChatRequests()->create($data);

            return response()->json($createRequest, HTTPResponseEnum::CREATED);
        });
    }

    /**
     * Accepts a chat request identified by UUID.
     * 
     * @param string $uuid UUID of the chat request to accept.
     * @return JsonResponse Confirmation message.
     */
    public function acceptRequest(string $uuid): JsonResponse
    {
        return $this->handleException(function () use ($uuid) {
            $chatRequest = ChatRequest::whereUuid($uuid)->firstOrFail();
            $this->validateAndProcessChatRequest($chatRequest);

            $chatRequest->update(['status' => 1]);
            return response()->json(['message' => 'Request accepted and chat created successfully'], HTTPResponseEnum::CREATED);
        });
    }

    /**
     * Rejects a chat request identified by UUID.
     * 
     * @param string $uuid UUID of the chat request to reject.
     * @return JsonResponse Confirmation message.
     */
    public function rejectRequest(string $uuid): JsonResponse
    {
        return $this->handleException(function () use ($uuid) {
            $chatRequest = ChatRequest::findOrFail($uuid);
            $chatRequest->update(['status' => 2]);
            return response()->json(['message' => 'Request rejected'], HTTPResponseEnum::OK);
        });
    }

    /**
     * Blocks the user who sent a chat request identified by UUID.
     * 
     * @param string $uuid UUID of the chat request for blocking the user.
     * @return JsonResponse Confirmation message.
     */
    public function blockUserRequest(string $uuid): JsonResponse
    {
        return $this->handleException(function () use ($uuid) {
            $chatRequest = ChatRequest::findOrFail($uuid);
            $chatRequest->update(['status' => 3]);
            return response()->json(['message' => 'User blocked successfully'], HTTPResponseEnum::OK);
        });
    }

    /**
     * Deletes a chat request identified by UUID.
     * 
     * @param string $uuid UUID of the chat request to delete.
     * @return JsonResponse Confirmation message.
     */
    public function delete(string $uuid): JsonResponse
    {
        return $this->handleException(function () use ($uuid) {
            $chatRequest = ChatRequest::where('uuid', $uuid)->firstOrFail();
            $chatRequest->delete();
            return response()->json(['message' => "Chat request deleted"]);
        });
    }

    /**
     * Handles exceptions for the chat request operations.
     * 
     * @param callable $callback Function to execute that may throw an exception.
     * @return JsonResponse Either the successful response from the callback or an error message.
     */
    private function handleException(callable $callback): JsonResponse
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

    /**
     * Validates the incoming request against the provided rules.
     * 
     * @param Request $request Incoming request to validate.
     * @param array $rules Validation rules.
     * @return array Validated data.
     */
    private function validateRequest(Request $request, array $rules): array
    {
        return $request->validate($rules);
    }

    /**
     * Validates the chat request data for business logic.
     * 
     * @param array $data Data to validate.
     * @return void
     */
    private function validateChatRequest(array $data): void
    {
        if ($this->user->email === $data['recipient_email']) {
            abort(HTTPResponseEnum::FORBIDDEN, 'You cannot send a chat request to yourself');
        }

        if (Chat::where('sender_id', $this->user->id)->where('recipient_id', User::where('email', $data['recipient_email'])->first()->id)->exists()) {
            abort(HTTPResponseEnum::BAD_REQUEST, 'An active chat is already established between you and this user');
        }

        $requestExists = $this->user->sentChatRequests()->where('recipient_email', $data['recipient_email'])->first();
        if ($requestExists && in_array($requestExists->status, [1, 3])) {
            abort(HTTPResponseEnum::BAD_REQUEST, 'A request in this state already exists');
        }
    }

    /**
     * Validates if the chat request can be accepted and initiates chat creation.
     * 
     * @param ChatRequest $chatRequest Chat request to validate and process.
     * @throws \Illuminate\Http\Exceptions\HttpResponseException If validation fails or chat creation fails.
     */
    private function validateAndProcessChatRequest(ChatRequest $chatRequest): void
    {
        $this->ensureNotSelfSent($chatRequest);
        $this->ensureNoExistingChat($chatRequest);
        $this->initiateChatCreation($chatRequest);
    }

    /**
     * Ensures the chat request was not sent by the current user.
     * 
     * @param ChatRequest $chatRequest
     * @throws \Illuminate\Http\Exceptions\HttpResponseException If the sender is the current user.
     */
    private function ensureNotSelfSent(ChatRequest $chatRequest): void
    {
        if ($chatRequest->sender_email === Auth::user()->email) {
            abort(HTTPResponseEnum::FORBIDDEN, 'You cannot accept a chat request sent out by you.');
        }
    }

    /**
     * Checks for an existing chat between the sender and recipient to prevent duplicates.
     * 
     * @param ChatRequest $chatRequest
     * @throws \Illuminate\Http\Exceptions\HttpResponseException If an existing chat is found.
     */
    private function ensureNoExistingChat(ChatRequest $chatRequest): void
    {
        $existingChat = Chat::where('sender_id', $chatRequest->sender()->value('id'))
                            ->where('recipient_id', $chatRequest->recipient()->value('id'))
                            ->first();
        if ($existingChat) {
            abort(HTTPResponseEnum::BAD_REQUEST, 'A chat between these users already exists.');
        }
    }

    /**
     * Initiates the creation of a chat based on the chat request.
     * 
     * @param ChatRequest $chatRequest
     * @throws \Illuminate\Http\Exceptions\HttpResponseException If chat creation fails.
     */
    private function initiateChatCreation(ChatRequest $chatRequest): void
    {
        if (!$this->chatRequestService->createChat($chatRequest)) {
            abort(HTTPResponseEnum::BAD_REQUEST, 'Failed to create chat.');
        }
    }
}