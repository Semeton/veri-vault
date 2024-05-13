<?php
declare(strict_types=1);
namespace App\Http\Controllers\Api\Chat;

use Exception;
use App\Models\Chat;
use App\Models\User;
use App\Lib\RequestHandler;
use App\Models\ChatRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Enums\HTTPResponseEnum;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
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
    public function __construct(
        private User $user,
        private ChatRequestService $chatRequestService,
        public RequestHandler $requestHandler
    ) {
    }

    /**
     * Lists all sent and received chat requests for the authenticated user.
     *
     * @return JsonResponse List of chat requests.
     */
    public function index(): JsonResponse
    {
        return $this->requestHandler->handleException(function () {
            $this->user = Auth::user();
            $sentChatRequests = $this->user->sentChatRequests
                ->where("status", 0)
                ->each(function ($item) {
                    $item->recipient_email = $item->recipient()->get();
                })
                ->get();
            $receivedChatRequests = $this->user->receivedChatRequests
                ->where("status", 0)
                ->each(function ($item) {
                    $item->sender_email = $item->sender()->get();
                })
                ->get();

            return response()->json(
                [
                    "sent" => $sentChatRequests,
                    "received" => $receivedChatRequests,
                ],
                HTTPResponseEnum::OK
            );
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
        return $this->requestHandler->handleException(function () use (
            $request
        ) {
            $data = $this->requestHandler->validateRequest($request, [
                "recipient_email" => "required|email|exists:users,email",
            ]);

            $this->user = Auth::user();
            $data["uuid"] = Str::uuid()->toString();

            $this->chatRequestService->validateChatRequest($this->user, $data);

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
        return $this->requestHandler->handleException(function () use ($uuid) {
            $chatRequest = $this->chatRequestService->validateUuid($uuid);
            $this->chatRequestService->validateAndProcessChatRequest(
                $chatRequest
            );

            $chatRequest->update(["status" => 1]);
            return response()->json(
                ["message" => "Request accepted and chat created successfully"],
                HTTPResponseEnum::CREATED
            );
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
            $chatRequest = $this->chatRequestService->validateUuid($uuid);
            $chatRequest->update(["status" => 2]);
            return response()->json(
                ["message" => "Request rejected"],
                HTTPResponseEnum::OK
            );
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
            $chatRequest->update(["status" => 3]);
            return response()->json(
                ["message" => "User blocked successfully"],
                HTTPResponseEnum::OK
            );
        });
    }
}
