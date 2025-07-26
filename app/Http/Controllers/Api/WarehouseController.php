<?php

namespace App\Http\Controllers\Api;

use App\Models\Warehouse;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class WarehouseController extends Controller
{
    public function getAllWarehouses(): JsonResponse
    {
        // Iegūst visus lietotājus un izvēlas tikai nepieciešamos laukus
        $warehouses = Warehouse::select('warehouse_code', 'name', 'location')->distinct()->get();

        return response()->json([
            'status' => 'success',
            'data' => $warehouses
        ]);
    }
}
