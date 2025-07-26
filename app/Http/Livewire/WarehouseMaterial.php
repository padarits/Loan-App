<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\Attributes\On; 
use App\Models\Warehouse;
use Illuminate\Support\Facades\Auth;
use App\Models\WarehouseMaterialMovement;
class WarehouseMaterial extends Component
{
    public $jjWarehouseCodeFilter;

    public function mount(){
        $this->jjWarehouseCodeFilter = WarehouseMaterialMovement::WarehouseTypeNone;
    }

    public function render()
    {
        return view('livewire.warehouse-material');
    }

    public static function getWarhouseByCode($warehouse_code){
        return Warehouse::where('warehouse_code', '=', $warehouse_code)->limit(1)->get();
    }

    public static function getAllWarhousesForUser(){
        $user = Auth::user();
        $warehouses = Warehouse::all();
        return $warehouses;
    }

    public static function getAllWarhousesExcept($warehouse_code){
        $transitWarehouses = Warehouse::where('warehouse_code', '<>', $warehouse_code)->get()->toArray();
        return $transitWarehouses;
    }

    public static function getFirstWarhouseCodeExcept($warehouse_code){
        return self::getAllWarhousesExcept($warehouse_code)[0]['warehouse_code'];
    }
}
