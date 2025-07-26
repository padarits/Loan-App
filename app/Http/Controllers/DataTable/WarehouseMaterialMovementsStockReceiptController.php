<?php

namespace App\Http\Controllers\DataTable;

use App\Http\Controllers\Controller;
use App\Models\WarehouseMaterialMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class WarehouseMaterialMovementsStockReceiptController extends Controller
{
    // Atgriež visus WarehouseMaterialMovement ierakstus datu tabulas formātā
    public function index(Request $request)
    {
        $columns = [
            0 => 'created_at',
            1 => 'type',
            2 => 'delta_quantity',
            3 => 'article',
            4 => 'date',
            5 => 'code',
            6 => 'status',
            7 => 'order_number',
            8 => 'name',
            9 => 'name_2',
            10 => 'material_grade',
            11 => 'unit',
            12 => 'quantity',
            13 => 'price_per_unit',
            14 => 'total_price',
            15 => 'supplier',
            16 => 'recipient',
            17 => 'due_date',
            18 => 'invoice_number',
            19 => 'supplier_company',
            20 => 'warehouse_date',
            21 => 'issued',
            22 => 'code_2',
        ];
        
        WarehouseMaterialMovement::setWarehouseFilter($request->input('jjWarehouseCodeFilter'));

        $totalData = WarehouseMaterialMovement::where(function($query) {
                            WarehouseMaterialMovement::setWhereForWarehouse($query);
                        })
                        ->where(function($query) {
                            WarehouseMaterialMovement::setWhereForStockReceipt($query);
                        })
                        ->where(function($query) {
                            self::setWhereForVisible($query);
                        })
                        ->count();
        $totalFiltered = $totalData;
    
        $limit = intval($request->input('length'));
        $start = intval($request->input('start'));
        $order = $columns[intval($request->input('order.0.column'))];
        $dir = ($request->input('order.0.dir') === 'desc') ? 'desc' : 'asc';
    
        if(empty($request->input('search.value')))
        {
            $movements = WarehouseMaterialMovement::where(function($query) {
                            WarehouseMaterialMovement::setWhereForWarehouse($query);
                        })
                        ->where(function($query) {
                            WarehouseMaterialMovement::setWhereForStockReceipt($query);
                        })
                        ->where(function($query) {
                            self::setWhereForVisible($query);
                        })
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order, $dir)
                        ->get();
        }
        else {
            $search = $request->input('search.value');
    
            $movements = WarehouseMaterialMovement::where(function($query) {
                            WarehouseMaterialMovement::setWhereForWarehouse($query);
                        })
                        ->where(function($query) {
                            WarehouseMaterialMovement::setWhereForStockReceipt($query);
                        })
                        ->where(function($query) {
                            self::setWhereForVisible($query);
                        })
                        ->where(function($query) use ($search) {
                            self::setWhereFor($query, $search);
                        })
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order, $dir)
                        ->get();
    
            $totalFiltered = WarehouseMaterialMovement::where(function($query) {
                                        WarehouseMaterialMovement::setWhereForWarehouse($query);
                                    })
                                    ->where(function($query) {
                                        WarehouseMaterialMovement::setWhereForStockReceipt($query);
                                    })
                                    ->where(function($query) {
                                        self::setWhereForVisible($query);
                                    })
                                    ->where(function($query) use ($search) {
                                        self::setWhereFor($query, $search);
                                    })
                                    ->count();
        }
    
        $data = [];
        if(!empty($movements))
        {
            foreach ($movements as $movement)
            {
                //$nestedData['id'] = $movement->id;
                $nestedData['guid'] = $movement->guid;
                $nestedData['parent_guid'] = $movement->parent_guid;
                $nestedData['type'] = __(substr($movement->type, 4));
                $nestedData['article'] = $movement->article;
                $nestedData['article_id'] = $movement->article_id;
                $nestedData['date'] = $movement->date->format('d.m.Y');
                $nestedData['code'] = $movement->code;
                $nestedData['status'] = $movement->status;
                $nestedData['order_number'] = $movement->order_number;
                $nestedData['name'] = $movement->name;
                $nestedData['name_2'] = $movement->name_2;
                $nestedData['material_grade'] = $movement->material_grade;
                $nestedData['unit'] = $movement->unit;
                $nestedData['quantity'] = $movement->quantity;
                $nestedData['price_per_unit'] = $movement->price_per_unit;
                $nestedData['total_price'] = $movement->total_price;
                $nestedData['supplier'] = $movement->supplier;
                $nestedData['recipient'] = $movement->recipient;
                $nestedData['due_date'] = $movement->due_date ? $movement->due_date->format('d.m.Y') : null;
                $nestedData['invoice_number'] = $movement->invoice_number;
                $nestedData['supplier_company'] = $movement->supplier_company;
                $nestedData['warehouse_date'] = $movement->warehouse_date ? $movement->warehouse_date->format('d.m.Y') : null;
                $nestedData['issued'] = $movement->issued;
                $nestedData['code_2'] = $movement->code_2;
                $nestedData['delta_quantity'] = $movement->delta_quantity;
                $nestedData['created_at'] = $movement->created_at->format('d.m.Y H:i:s');

                $data[] = $nestedData;
            }
        }
    
        $json_data = [
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        ];
    
        return response()->json($json_data);
    }

    public static function setWhereFor(&$query, $search){
        $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('name_2', 'LIKE', "%{$search}%")
                ->orWhere('code', 'LIKE', "%{$search}%")
                ->orWhere('article', 'LIKE', "%{$search}%")
                ->orWhere('order_number', 'LIKE', "%{$search}%")
                ->orWhere('material_grade', 'LIKE', "%{$search}%")
                ->orWhere('supplier', 'LIKE', "%{$search}%")
                ->orWhere('recipient', 'LIKE', "%{$search}%")
                ->orWhere('invoice_number', 'LIKE', "%{$search}%")
                ->orWhere('supplier_company', 'LIKE', "%{$search}%")
                ->orWhere('code_2', 'LIKE', "%{$search}%");
    }

    public static function setWhereForVisible(&$query){
        $query->where('delta_quantity', '>', "0")
                ->orWhere('updated_at', '>', Carbon::now()->toDateString());
    }

    // Izveido jaunu WarehouseMaterialMovement ierakstu
    public function store(Request $request)
    {
        $validated = $request->validate([
            'guid' => 'required|uuid',
            'parent_guid' => 'nullable|uuid|exists:warehouse_material_movements,guid',
            'article' => 'nullable|string|max:255',
            'article_id' => 'required|string|max:255', // Article ID validācija
            'date' => 'required|date',
            'code' => 'required|string|max:255',
            'status' => 'required|in:R,Ri,N,M',
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
            'type' => 'required|in:010_none,020_application,030_received,040_dispensed,050_written_off,060_added_to_inventory,070_removed_from_inventory,080_in_transit,090_canceled,100_balance',
        ]);

        $movement = WarehouseMaterialMovement::create($validated);

        return response()->json($movement, 201);
    }

    // Atgriež konkrētu WarehouseMaterialMovement ierakstu
    public function show($id)
    {
        $movement = WarehouseMaterialMovement::find($id);

        if ($movement) {
            return response()->json($movement, 200);
        }

        return response()->json(['message' => 'Movement not found'], 404);
    }

    // Atjaunina konkrētu WarehouseMaterialMovement ierakstu
    public function update(Request $request, $id)
    {
        $movement = WarehouseMaterialMovement::find($id);

        if (!$movement) {
            return response()->json(['message' => 'Movement not found'], 404);
        }

        $validated = $request->validate([
            'parent_guid' => 'nullable|uuid|exists:warehouse_material_movements,guid',
            'article' => 'nullable|string|max:255',
            'article_id' => 'required|string|max:255', // Article ID validācija
            'date' => 'sometimes|required|date',
            'code' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|required|in:R,Ri,N,M',
            'order_number' => 'sometimes|required|string|max:255',
            'name' => 'sometimes|required|string|max:255',
            'name_2' => 'nullable|string|max:255',
            'material_grade' => 'sometimes|required|string|max:255',
            'unit' => 'sometimes|required|string|max:50',
            'quantity' => 'sometimes|required|numeric|min:0',
            'price_per_unit' => 'nullable|numeric|min:0',
            'total_price' => 'nullable|numeric|min:0',
            'supplier' => 'sometimes|required|string|max:255',
            'recipient' => 'nullable|string|max:255',
            'due_date' => 'nullable|date',
            'invoice_number' => 'nullable|string|max:255',
            'supplier_company' => 'nullable|string|max:255',
            'warehouse_date' => 'nullable|date',
            'issued' => 'nullable|boolean',
            'code_2' => 'nullable|string|max:255',
            'type' => 'sometimes|required|in:010_none,020_application,030_received,040_dispensed,050_written_off,060_added_to_inventory,070_removed_from_inventory,080_in_transit,090_canceled,100_balance',
        ]);

        $movement->update($validated);

        return response()->json($movement, 200);
    }

    // Dzēš konkrētu WarehouseMaterialMovement ierakstu
    public function destroy($id)
    {
        $movement = WarehouseMaterialMovement::find($id);

        if (!$movement) {
            return response()->json(['message' => 'Movement not found'], 404);
        }

        $movement->delete();

        return response()->json(['message' => 'Movement deleted successfully'], 200);
    }
}
