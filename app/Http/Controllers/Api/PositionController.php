<?php

namespace App\Http\Controllers\Api;

use App\Models\EmployeePosition;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class PositionController extends Controller
{
    /**
     * Get all positions.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllPositions(): JsonResponse
    {
        // Iegūst visus lietotājus un izvēlas tikai nepieciešamos laukus
        $positions = EmployeePosition::all();

        return response()->json([
            'status' => 'success',
            'data' => $positions
        ]);
    }
}
