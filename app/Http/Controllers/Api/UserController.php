<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function getAllUsers(): JsonResponse
    {
        // Iegūst visus lietotājus un izvēlas tikai nepieciešamos laukus
        $users = User::select('id', 'name', 'email')->get();

        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }
}
