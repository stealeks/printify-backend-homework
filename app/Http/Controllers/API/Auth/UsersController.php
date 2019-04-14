<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\API\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function get() : JsonResponse
    {
        $user = Auth::user();

        return response()->json($user);
    }
}
