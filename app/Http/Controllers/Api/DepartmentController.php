<?php

namespace App\Http\Controllers\Api;

use App\Models\Department;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class DepartmentController extends Controller
{
    /**
     * Get all departments.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllDepartments(): JsonResponse
    {
        // Iegūst visus lietotājus un izvēlas tikai nepieciešamos laukus
        $departments = Department::all();

        return response()->json([
            'status' => 'success',
            'data' => $departments
        ]);
    }
}
