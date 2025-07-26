<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Livewire\Attributes\On; 
use Livewire\Attributes\Locked;

class UserModal extends Component
{
    #[Locked]
    public $userId;
    public $user;

    protected $listeners = ['openModal'];
    public $isOpen = false;

    public function openModal($id)
    {
        $this->userId = $id;
        $this->user = User::find($id);  // Fetch user data based on ID
        $this->isOpen = true;
    }
    
    public function closeModal()
    {
        $this->isOpen = false;
        $this->dispatch('dataTableAjaxReload');    // Emit an event to reload the DataTable
    }

    public function saveData()
    {
        $this->dispatch('showWarning', 'Hello from Livewire!');    // Emit an event to show a warning message
        $this->isOpen = false;
    }
    
    public function render()
    {
        return view('livewire.user-modal');
    }
}
