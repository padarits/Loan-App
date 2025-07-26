<?php

namespace App\Http\Controllers;

use App\Models\WarehouseMaterialMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class WarehouseMaterialMovementController extends Controller
{
    public function index()
    {
        $movements = WarehouseMaterialMovement::all();
        return response()->json($movements);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'guid' => 'nullable|uuid',
            'parent_guid' => 'nullable|uuid|exists:warehouse_material_movements,guid', // Pārbauda, vai parent_guid eksistē
            'article' => 'nullable|string|max:255', // Article validācija
            'date' => 'required|date',
            'code' => 'required|string|max:255',
            'status' => 'nullable|in:R,Ri,N,M', // Validācija status laukam
            'order_number' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'name_2' => 'nullable|string|max:255',
            'material_grade' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'quantity' => 'required|numeric|min:0',
            'price_per_unit' => 'nullable|numeric|min:0',
            'total_price' => 'nullable|numeric|min:0',
            'supplier' => 'required|string|max:255',
            'recipient' => 'nullable|string|max:255',
            'due_date' => 'nullable|date',
            'invoice_number' => 'nullable|string|max:255',
            'supplier_company' => 'nullable|string|max:255',
            'warehouse_date' => 'nullable|date',
            'issued' => 'nullable|boolean',
            'code_2' => 'nullable|string|max:255',
            'type' => 'required|in:010_none,020_application,030_received,040_dispensed,050_written_off,060_added_to_inventory,070_removed_from_inventory,080_in_transit,090_canceled,100_balance', // Type validācija
        ]);

        $movement = WarehouseMaterialMovement::create($validatedData);

        return response()->json($movement, 201);
    }

    public function show($id)
    {
        $movement = WarehouseMaterialMovement::findOrFail($id);
        return response()->json($movement);
    }

    public function update(Request $request, $id)
    {
        $movement = WarehouseMaterialMovement::findOrFail($id);

        $validatedData = $request->validate([
            'guid' => 'nullable|uuid',
            'parent_guid' => 'nullable|uuid|exists:warehouse_material_movements,guid', // Pārbauda, vai parent_guid eksistē
            'article' => 'nullable|string|max:255', // Article validācija
            'date' => 'required|date',
            'code' => 'required|string|max:255',
            'status' => 'nullable|in:R,Ri,N,M', // Validācija status laukam
            'order_number' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'name_2' => 'nullable|string|max:255',
            'material_grade' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'quantity' => 'required|numeric|min:0',
            'price_per_unit' => 'nullable|numeric|min:0',
            'total_price' => 'nullable|numeric|min:0',
            'supplier' => 'required|string|max:255',
            'recipient' => 'nullable|string|max:255',
            'due_date' => 'nullable|date',
            'invoice_number' => 'nullable|string|max:255',
            'supplier_company' => 'nullable|string|max:255',
            'warehouse_date' => 'nullable|date',
            'issued' => 'nullable|boolean',
            'code_2' => 'nullable|string|max:255',
            'type' => 'required|in:010_none,020_application,030_received,040_dispensed,050_written_off,060_added_to_inventory,070_removed_from_inventory,080_in_transit,090_canceled,100_balance', // Type validācija
        ]);

        $movement->update($validatedData);

        return response()->json($movement);
    }

    public function destroy($id)
    {
        $movement = WarehouseMaterialMovement::findOrFail($id);
        $movement->delete();

        return response()->json(null, 204);
    }
}

