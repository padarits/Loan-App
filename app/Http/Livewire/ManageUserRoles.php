<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Livewire\Attributes\On; 
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\Hash;
use App\Helpers\EmailHelper;
use Illuminate\Support\Str;

class ManageUserRoles extends Component
{
    #[Locked]   
    public $userId;
    #[Locked]
    public $user;
    public $roles = [];
    public $selectedRoles = [];
    public $isOpen = false;
    public $newRoleName = '';  // Jaunās lomas nosaukums
    public $newPassword;
    #[Locked] 
    public $email;

    public function mount(User $user)
    {
        $this->user = $user;
        // Iegūst visas pieejamās lomas
        $this->roles = Role::all();
        // Lietotāja pašreizējās lomas
        $this->selectedRoles = $this->user->roles->pluck('name')->toArray();
    }

    #[On('openModal')]
    public function openModal($id)
    {
        $this->reset();
        $this->resetErrorBag();
        $this->resetValidation(); // Clear validation errors
        $this->userId = $id;
        $this->user = User::find($id);  // Fetch user data based on ID
        if ($this->user) {
            // Iegūst visas pieejamās lomas
            $this->roles = Role::all();
            // Lietotāja pašreizējās lomas
            $this->selectedRoles = $this->user->roles->pluck('name')->toArray();
            $this->email = $this->user->email;
        }
        $this->isOpen = true;
    }
    
    public function closeModal()
    {
        $this->isOpen = false;
        $this->dispatch('dataTableAjaxReload');    // Emit an event to reload the DataTable
    }

    public function saveRoles()
    {
        // Saglabā iepriekšējās lomas, lai varētu salīdzināt
        $previousRoles = $this->user->roles->pluck('name')->toArray();
        // Izmanto syncRoles metodi, lai sinhronizētu lomas
        $this->user->syncRoles($this->selectedRoles);
                
        // Salīdzina, lai noskaidrotu, vai loma "admin" tika pievienota vai atņemta
        if (!in_array('admin', $previousRoles) && in_array('admin', $this->selectedRoles)) {
            // Ja "admin" loma tika pievienota
            EmailHelper::sendEmailAdminAdded($this->email);
        } elseif (in_array('admin', $previousRoles) && !in_array('admin', $this->selectedRoles)) {
            // Ja "admin" loma tika atņemta
            EmailHelper::sendEmailAdminRemoved($this->email);
        }

        // Aizver modālo logu un paziņo lietotājam
        $this->closeModal();
        session()->flash('message', 'Lietotāja lomas veiksmīgi atjauninātas!');
        $this->dispatch('showSuccess', 'Lietotāja lomas veiksmīgi atjauninātas!');
    }

    public function loadRoles()
    {
        $this->roles = Role::all();
    }
    
    public function createRole()
    {
        // Pārbauda, vai lomas nosaukums nav tukšs
        $this->validate([
            'newRoleName' => 'required|string|max:255|unique:roles,name',
        ]);

        // Izveido jaunu lomu
        Role::create(['uuid' => Str::uuid()->toString(), 'name' => $this->newRoleName, 'guard_name' => 'web']);

        // Ielādē no jauna visas lomas
        $this->loadRoles();

        // Atiestata lomas ievades lauku
        $this->newRoleName = '';

        // Paziņojums par izveidi
        session()->flash('message', 'Jaunā loma veiksmīgi izveidota!');
        $this->dispatch('showSuccess', 'Jaunā loma veiksmīgi izveidota!');
    }

    public function setPassword()
    {
        // Validējam ievadītos datus
        $this->validate([
            'newPassword' => 'required|min:8',
        ]);
        
        // Atrodam lietotāju pēc ID
        $user = User::findOrFail($this->userId);
        
        // Nomainām paroli
        $user->password = Hash::make($this->newPassword);
        $user->save();
        $this->dispatch('showSuccess', 'Jaunā parole veiksmīgi iestatīta!');
    }

    public function render()
    {
        return view('livewire.manage-user-roles');
    }

    // Pielāgotie kļūdu ziņojumi
    protected function messages()
    {
        return [
            'newPassword.required' => 'Lauks Jaunā parole ir obligāts.',
            'newPassword.min' => 'Lauks Jaunā parolei ir jābūt vismaz :min rakstzīmēm.',
            'newPassword.confirmed' => 'Jaunās paroles apstiprinājumam jāsakrīt.',
            'password_confirmation.required' => 'Lauks Paroles apstiprinājums ir obligāts.',
        ];
    }
}

