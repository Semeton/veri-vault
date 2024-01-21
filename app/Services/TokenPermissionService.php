<?php

namespace App\Services;

use Illuminate\Http\Request;

class TokenPermissionService{
    public function getTokenPermission(Request $request, string $permission)
    {
        return $request->user()->tokenCan($permission);
    }
}