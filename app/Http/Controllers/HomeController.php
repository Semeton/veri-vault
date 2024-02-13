<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\SiteVisits;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request) {
        $data = $request->ipinfo->all;
        $ipExist = SiteVisits::where('ip_address', $data['ip'])->first();
        $new = false;
        if(!$ipExist){
            $new = true;
            SiteVisits::create([
                'ip_address' => $data['ip'],
                'country' => $data['country_name'] ?? "Localhost",
                'time_of_visit' => Carbon::now(),
            ]);
        }
        // return response()->json(['data' => $data]);
        return view('home', ['data' => $data, 'new' => $new]);
    }

    private function getUserDevice(Request $request)
    {
        $userAgent = $request->header('User-Agent');
        
        return $userAgent;
    }
}