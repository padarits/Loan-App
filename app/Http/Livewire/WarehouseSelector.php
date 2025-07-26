<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Warehouse;
use Illuminate\Support\Str;
use Livewire\Attributes\Session;
use Livewire\Attributes\On; 

class WarehouseSelector extends Component
{
    public $warehouses = [];
    //#[Session] 
    public $warehouseCode;
    public $selectId;
    public $name;

    protected $rules = [
        'warehouseCode' => 'required|string|exists:warehouses,warehouse_code',
    ];

    public function mount($name = null)
    {
        $this->name = $name;
        // Ģenerē unikālu ID izvēlnei
        $this->selectId = 'warehouseCode_' . Str::random(8);
        // Ielādē visas noliktavas
        $this->warehouses = WarehouseMaterial::getAllWarhousesForUser();
    }
    
    // Vispārējā Livewire metode, kas izsaucas, kad tiek mainīti modeļi
    public function updated($field, $value)
    {
        // Validē tikai konkrēto lauku
        $this->validateOnly($field);
        $this->dispatch('update-warehouse-code', warehouseCode: $this->warehouseCode, selectId: $this->selectId)->to(WarehouseSelector::class);
    }
    
    #[On('update-warehouse-code')] 
    public function updateWarehouseCode($warehouseCode, $selectId)
    {
        if ($this->selectId != $selectId) {
            $this->warehouseCode = $warehouseCode;
        } else {
            $this->js("$('#jj-warehouse-code-filter').val('$warehouseCode')");
            $this->dispatch('dataTableAjaxReload-' . $this->name);
        } 
    }

    public function render()
    {
        return view('livewire.warehouse-selector');
    }
}
