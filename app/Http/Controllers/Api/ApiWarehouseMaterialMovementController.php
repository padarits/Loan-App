<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WarehouseMaterialMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class ApiWarehouseMaterialMovementController extends Controller
{
    /**
     * Atgriež visu WarehouseMaterialMovement sarakstu.
     */
    public function index()
    {
        $movements = WarehouseMaterialMovement::all();
        return response()->json($movements);
    }

    /**
     * Izveido jaunu WarehouseMaterialMovement ierakstu vai atjaunina esošo pēc GUID.
     */
    public function store(Request $request)
    {
        /*$validatedData = $request->validate([
            'guid' => 'nullable|uuid',
            'date' => 'required|date',
            'code' => 'required|string|max:255',
            'order_number' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'name_2' => 'nullable|string|max:255',
            'material_grade' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'quantity' => 'required|numeric|min:0',
            'price_per_unit' => 'nullable|numeric|min:0',
            'supplier' => 'required|string|max:255',
            'recipient' => 'nullable|string|max:255',
            'due_date' => 'nullable|date',
            'invoice_number' => 'nullable|string|max:255',
            'supplier_company' => 'nullable|string|max:255',
            'warehouse_date' => 'nullable|date',
            'issued' => 'nullable|boolean',
            'code_2' => 'nullable|string|max:255',
            'status' => 'required|in:R,Ri,N,M',
            // 'type' => 'required|in:010_none,020_application,030_received,040_dispensed,050_written_off,060_added_to_inventory,070_removed_from_inventory,080_in_transit,090_canceled',
        ]);*/

        // Validē pieprasījumu, lai pārliecinātos, ka `data` lauks ir JSON formātā
        $validator = Validator::make($request->all(), [
            'data' => 'required|json',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Nepareizs formāts.',
                'errors' => $validator->errors(),
            ], 422);
        }
        
        $validatedData = json_decode($request->data, true);

        // Izveido validācijas noteikumus
        $validator = Validator::make($validatedData, [
            'warehouse_code' => 'nullable|string|max:255',
            'article' => 'nullable|string|max:255', // Article validācija
            'article_id' => 'required|string|max:255', // Article ID validācija
            'date' => 'required|date',
            'code' => 'required|string|max:10',
            'order_number' => 'required|string|max:10',
            'name' => 'required|string|max:255',
            'name_2' => 'nullable|string|max:255',
            'material_grade' => 'required|string|max:50',
            'unit' => 'required|string|max:10',
            'quantity' => 'required|integer|min:1',
            'price_per_unit' => 'required|numeric|min:0',
            'supplier' => 'required|string|max:255',
            'recipient' => 'nullable|string|max:255',
            'recipient_guid' => 'nullable|uuid|exists:users,id',
            'due_date' => 'required|date|after_or_equal:date',
            'invoice_number' => 'nullable|string|max:20',
            'supplier_company' => 'nullable|string|max:255',
            'warehouse_date' => 'nullable|date',
            'warehouse_code' => 'nullable|string|max:255',
            'issued' => 'nullable|boolean',
            'code_2' => 'nullable|string|max:10',
            'status' => 'nullable|in:R,Ri,N,M,-', // assuming R, P, and C are valid status codes
            'external_int_id' => 'nullable|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Nepareizs formāts.',
                'errors' => $validator->errors(),
            ], 422);
        }
        
        // Ja tiek padots `guid`, pārbauda, vai tas eksistē, un atjaunina ierakstu
        if (!empty($validatedData['guid'])) {
            $movement = WarehouseMaterialMovement::where('guid', $validatedData['guid'])->first();

            if ($movement) {
                $movement->update($validatedData);
                return response()->json([
                    'success' => true,
                    'message' => 'Dati veiksmīgi saglabāti.',
                    'data' => $movement,
                ], 200);
            }
        } else {
            // Ja `guid` nav norādīts, automātiski ģenerē jaunu
            $validatedData['guid'] = Str::uuid();
        }

        // Iestata `loaded_at` laiku uz pašreizējo laiku
        $validatedData['warehouse_code'] = $validatedData['warehouse_code'] ? $validatedData['warehouse_code'] : WarehouseMaterialMovement::WarehouseTypeNone;
        $validatedData['loaded_at'] = Carbon::now();
        $validatedData['type'] = '020_application';
        $validatedData['parent_guid'] = null;

        // Ja ieraksts netika atrasts, izveidojam jaunu
        $movement = WarehouseMaterialMovement::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Dati veiksmīgi saglabāti.',
            'data' => $movement,
        ], 201);
    }

    /**
     * Atgriež konkrētu WarehouseMaterialMovement ierakstu.
     */
    public function show($guid)
    {
        $movement = WarehouseMaterialMovement::findOrFail($guid);
        return response()->json($movement);
    }

    /**
     * Atjaunina esošu WarehouseMaterialMovement ierakstu pēc ID.
     */
    public function update(Request $request, $guid)
    {
        $movement = WarehouseMaterialMovement::findOrFail($guid);

        $validatedData = $request->validate([
            'warehouse_code' => 'nullable|string|max:255',
            'article' => 'nullable|string|max:255', // Article validācija
            'article_id' => 'required|string|max:255', // Article ID validācija
            'date' => 'required|date',
            'code' => 'required|string|max:255',
            'order_number' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'name_2' => 'nullable|string|max:255',
            'material_grade' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'quantity' => 'required|numeric|min:0',
            'price_per_unit' => 'nullable|numeric|min:0',
            'supplier' => 'required|string|max:255',
            'recipient' => 'nullable|string|max:255',
            'recipient_guid' => 'nullable|uuid|exists:users,id',
            'due_date' => 'nullable|date',
            'invoice_number' => 'nullable|string|max:255',
            'supplier_company' => 'nullable|string|max:255',
            'warehouse_date' => 'nullable|date',
            'issued' => 'nullable|boolean',
            'code_2' => 'nullable|string|max:255',
            'status' => 'required|in:R,Ri,N,M,-',
            'external_int_id' => 'nullable|numeric',
            //'type' => 'required|in:010_none,020_application,030_received,040_dispensed,050_written_off,060_added_to_inventory,070_removed_from_inventory,080_in_transit,090_canceled',
        ]);

        $movement->update($validatedData);

        return response()->json($movement);
    }

    /**
     * Dzēš WarehouseMaterialMovement ierakstu pēc ID.
     */
    public function destroy($guid)
    {
        $movement = WarehouseMaterialMovement::findOrFail($guid);
        $movement->delete();

        return response()->json(null, 204);
    }
}
