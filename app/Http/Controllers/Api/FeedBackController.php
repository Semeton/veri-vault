<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FeedBack;
use Illuminate\Http\Request;

class FeedBackController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->validate(["feedback" => "required|string"]);
        FeedBack::create($data);
        return response()->json(
            [
                "message" => "Thank you for your feedback",
            ],
            201
        );
    }
}
