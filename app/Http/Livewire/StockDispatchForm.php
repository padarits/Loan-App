<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\Attributes\On; 
use Livewire\Attributes\Locked;

class StockDispatchForm extends Component
{
    public $isOpen = false;
    public $id;
    
    /**
     * Atver modālo logu un ielādē dokumenta datus, ja tie ir pieejami.
     * @param uuid $id Dokumenta ID
     */
    #[On('openModal3')]
    public function openModal($id)
    {
        $this->isOpen = true;
        $this->id = $id;
        $this->dispatch('stock-dispatch-form-open', ['id' => $id]);
    }

    public function closeModal()
    {
        // $this->reset(); // Atiestata visus formā esošos laukus
        $this->isOpen = false;
        $this->dispatch('dataTableAjaxReload-stock-dispatch');
    }
    
    public function render()
    {
        return view('livewire.stock-dispatch-form');
    }
}
