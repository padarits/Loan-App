<?php

namespace App\Http\Controllers\DataTable;

use App\Http\Controllers\Controller;
use App\Models\User;
//use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EmployeeListController extends Controller
{
    // Atgriež visus WarehouseMaterialMovement ierakstus datu tabulas formātā
    public function index(Request $request)
    {
        $columns = [
            0 => 'email',
            1 => 'name',
            /*2 => 'name',
            3 => 'parent_name',
            4 => 'contact_person',
            5 => 'email',
            6 => 'phone',
            7 => 'address',
            8 => 'city',
            9 => 'description',
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
            22 => 'code_2',*/
        ];
        
        $totalData = User::where(function($query) {
                        })
                        ->count();
        $totalFiltered = $totalData;
    
        $limit = intval($request->input('length'));
        $start = intval($request->input('start'));
        $order = $columns[intval($request->input('order.0.column'))];
        $dir = ($request->input('order.0.dir') === 'desc') ? 'desc' : 'asc';
        
        $search = $request->input('search.value');
        if(empty($search))
        {
            $employees = User::where(function($query) {
                        })
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order, $dir)
                        ->get();
        }
        else {    
            $employees = User::where(function($query) use ($search) {
                            self::setWhereFor($query, $search);
                        })
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order, $dir)
                        ->get();
    
            $totalFiltered = User::where(function($query) {
                                    })
                                    ->where(function($query) use ($search) {
                                        self::setWhereFor($query, $search);
                                    })
                                    ->with('parent')
                                    ->count();
        }
    
        $data = [];
        if(!empty($employees))
        {
            foreach ($employees as $employee)   
            {
                $nestedData['guid'] = $employee->id;
                $nestedData['name'] = $employee->name;
                $nestedData['email'] = $employee->email;
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

        // $movement = WarehouseMaterialMovement::create($validated);

        // return response()->json($movement, 201);
    }
    
    public static function setWhereFor(&$query, $search){
        $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                /*->orWhere('contact_person', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->orWhere('phone', 'LIKE', "%{$search}%")
                ->orWhere('address', 'LIKE', "%{$search}%")
                ->orWhere('city', 'LIKE', "%{$search}%")
                ->orWhere('description', 'LIKE', "%{$search}%")
                ->orWhere('invoice_number', 'LIKE', "%{$search}%")
                ->orWhere('supplier_company', 'LIKE', "%{$search}%")
                ->orWhere('code_2', 'LIKE', "%{$search}%");*/
                ;
    }
  
    /*
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
    }*/

    public function search_article(Request $request)
    {
        // Atgriež līdz 10 rezultātiem ar article 
        /*return WarehouseMaterialMovement::where('article', 'like', '%' . $request->query('query') . '%')
                        ->orWhere('name', 'like', '%' . $request->query('query') . '%')
                        ->orWhere('name_2', 'like', '%' . $request->query('query') . '%')
                        ->distinct()
                        ->limit(10)
                        ->get(['article_id', 'article', 'name', 'name_2', 'material_grade'])->toArray();
                        */
    }


}
