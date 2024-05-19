<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FeedBack;
use App\Services\EmailService;
use Illuminate\Http\Request;

class FeedBackController extends Controller
{
    public function create(Request $request, EmailService $emailService)
    {
        $data = $request->validate(["feedback" => "required|string"]);
        $emailService->sendFeedBackEmail($data);
        FeedBack::create($data);
        return response()->json(
            [
                "message" => "Thank you for your feedback",
            ],
            201
        );
    }
}
