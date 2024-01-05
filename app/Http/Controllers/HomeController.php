<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request) {
        $data = $request->ipinfo->all;
        // return response()->json(['data' => $data]);
        return view('home', ['data' => $data]);
    }

    private function getUserDevice(Request $request)
    {
        $userAgent = $request->header('User-Agent');
        
        return $userAgent;
    }
}