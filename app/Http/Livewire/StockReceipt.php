<?php

namespace App\Http\Livewire;

use Livewire\Component;

class StockReceipt extends Component
{
    public $dataTableConfig;

    public function mount()
    {
        $this->dataTableConfig = [
            'processing' => true,
            'serverSide' => true,
            'stateSave' => true,
            'scrollX' => true,
        ];
    }
    
    public function render()
    {
        return view('livewire.stock-receipt');
    }
}
