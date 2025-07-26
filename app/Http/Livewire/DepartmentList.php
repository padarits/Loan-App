<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\Attributes\On; 
use Livewire\Attributes\Locked;

class DepartmentList extends Component
{
    public function render()
    {
        return view('livewire.department-list');
    }
}
