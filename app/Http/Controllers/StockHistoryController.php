<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WarehouseMaterialMovement;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
class StockHistoryController extends Controller
{
    // Atgriež visus WarehouseMaterialMovement ierakstus datu tabulas formātā
    public function index(Request $request)
    {       
        $data = self::getDataForDataTable($request->input('jjId'));

        $totalData = count($data);
        $totalFiltered = $totalData;
    
        $json_data = [
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        ];
    
        return response()->json($json_data);
    }
    
    public function forApi(Request $request)
    {               
        $validator = Validator::make(['uuid' => $request->input('guid')], [
            'uuid' => ['required', 'uuid'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid UUID'], 400);
        }
    
        return response()->json(self::getDataForDataTable($request->input('guid')), 201);
    }

    private static function getDataForDataTable($id){
        $data = [];
        $movement = self::findFirstParentFor($id, 0);
        if($movement) {
            $subdata = self::getArray($movement->toArray());
            $data = array_merge($data, [$subdata]);
            self::getChildren($data, $movement->guid, 0);
        } else {
            $data = ['error'=>'No data found'];
        }
        return $data;
    }

    /**
     * Find the first parent movement for a given id, 
     * following the parent_guid relationship recursively.
     * 
     * @param int $id The id of the movement to start from
     * @param int $depth Current recursion depth
     * @return \App\Models\WarehouseMaterialMovement|null The first parent movement, or null if no parent found
     */
    private static function findFirstParentFor($id, $depth)
    {
        $depth++;
        if($depth > 1000) {
            return null;
        }
        $movement = WarehouseMaterialMovement::where('guid', $id)->with('warehouse')->first();
        if (!$movement) {
            return null;
        }
        if($movement->parent_guid) {
            return self::findFirstParentFor($movement->parent_guid, $depth);
        }
        else {
            return $movement;    
        }
    }
    //get all chidren for a movement
    private static function getChildren(&$data, $id, $depth) {
        $depth++;
        if($depth > 1000) {
            return null;
        }
        $movements = WarehouseMaterialMovement::where('parent_guid', $id)->with('warehouse')->get();
        //for each child, get its children
        foreach($movements as $movement) {
            $subdata = self::getArray($movement->toArray());
            $data = array_merge($data, [$subdata]);
            self::getChildren($data, $movement->guid, $depth);
        }
    }

    private static function getArray($data) {
        $data['type'] = __(substr($data['type'], 4));
        $data['created_at'] = Carbon::createFromFormat('Y-m-d\TH:i:s.u\Z', $data['created_at'])->format('d.m.Y H:i:s'); 
        $data['updated_at'] = Carbon::createFromFormat('Y-m-d\TH:i:s.u\Z', $data['updated_at'])->format('d.m.Y H:i:s');
        if($data['warehouse_date']){
            $data['warehouse_date'] = Carbon::createFromFormat('Y-m-d\TH:i:s.u\Z', $data['warehouse_date']) ? Carbon::createFromFormat('Y-m-d\TH:i:s.u\Z', $data['warehouse_date'])->format('d.m.Y') : null;
        }
        if($data['due_date']){
            $data['due_date'] = Carbon::createFromFormat('Y-m-d\TH:i:s.u\Z', $data['due_date']) ? Carbon::createFromFormat('Y-m-d\TH:i:s.u\Z', $data['due_date'])->format('d.m.Y') : null;
        }
        if($data['date']){
            $data['date'] = Carbon::createFromFormat('Y-m-d\TH:i:s.u\Z', $data['date'])->format('d.m.Y') ? Carbon::createFromFormat('Y-m-d\TH:i:s.u\Z', $data['date'])->format('d.m.Y') : null;
        }
        $data['warehouse_name'] = $data['warehouse']['name'] ?? null;
        return $data;
    }
}