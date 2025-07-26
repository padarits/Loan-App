<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Application;

class ActiveApplications extends Component
{
    public $applications = [];

    public function mount()
    {
        // Iegūstam visus aktīvos pieteikumus no datubāzes
        // $this->applications = Application::where('status', 'active')->get();
    }

    public function render()
    {
        return view('livewire.active-applications');
    }
}
