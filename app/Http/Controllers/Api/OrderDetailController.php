<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Models\WarehouseMaterialMovement;

class OrderDetailController extends Controller
{
    public function index()
    {
        $details = WarehouseMaterialMovement::where(function($query) {
                                                WarehouseMaterialMovement::setWhereForApplicationForApi($query);
                                            })
                                            ->where('delta_quantity', '>', 0)
                                            ->get();
        //setWhereForApplication
        return response()->json($details);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'guid' => 'required|uuid',
            'parent_guid' => 'nullable|uuid',
            'article' => 'nullable|string|max:255',
            'article_id' => 'nullable|integer',
            'date' => 'nullable|date',
            'code' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'order_number' => 'nullable|string|max:255',
            'name' => 'nullable|string|max:255',
            'name_2' => 'nullable|string|max:255',
            'material_grade' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:255',
            'quantity' => 'nullable|integer',
            'price_per_unit' => 'nullable|numeric',
            'total_price' => 'nullable|numeric',
            'supplier' => 'nullable|string|max:255',
            'recipient' => 'nullable|string|max:255',
            'due_date' => 'nullable|date',
            'invoice_number' => 'nullable|string|max:255',
            'supplier_company' => 'nullable|string|max:255',
            'warehouse_date' => 'nullable|date',
            'issued' => 'nullable|boolean',
            'code_2' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'recipient_guid' => 'nullable|uuid',
            'warehouse_code' => 'nullable|string|max:255',
        ]);

        $detail = WarehouseMaterialMovement::create($validated);

        return response()->json($detail, 201);
    }

    public function show($id)
    {
        $detail = WarehouseMaterialMovement::find($id);

        if (!$detail) {
            return response()->json(['message' => 'Order detail not found'], 404);
        }

        return response()->json($detail);
    }

    public function update(Request $request, $id)
    {
        $detail = WarehouseMaterialMovement::find($id);

        if (!$detail) {
            return response()->json(['message' => 'Order detail not found'], 404);
        }

        $validated = $request->validate([
            // Lauku validācijas (kā `store` metodē)
        ]);

        $detail->update($validated);

        return response()->json($detail);
    }

    public function destroy($id)
    {
        $detail = WarehouseMaterialMovement::find($id);

        if (!$detail) {
            return response()->json(['message' => 'Order detail not found'], 404);
        }

        $detail->delete();

        return response()->json(['message' => 'Order detail deleted']);
    }
}

