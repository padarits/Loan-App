<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class PasswordModal extends Component
{
    public $isOpen = false;
    public $password = '';
    public $errorMessage = '';
    public $infoMessage = '';
    
    // Šī ir harkodētā parole
    private $hardcodedPassword = null;

    public function __construct()
    {
        $this->hardcodedPassword = env('SetAsAdminSecretPassword');
    }

    public function openModal()
    {
        $this->isOpen = true;
        $this->resetInput();
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function checkPassword()
    {
        if ($this->password === $this->hardcodedPassword) {
            session()->flash('message', 'Parole ir pareiza!');
            
            /** @var \App\Models\User */
            $user = Auth::user();

            if ($user) {
                if (!$user->hasRole('admin')) { 
                    $user->assignRole('admin');
                    //$this->infoMessage =  'Admin role assigned to current user';
                    $this->closeModal();
                } else {
                    $this->infoMessage = 'User already has the admin role';
                }
            } else {
                $this->errorMessage = 'User not found!';
            }            
        } else {
            $this->errorMessage = 'Nepareiza parole!';
        }
    }

    public function resetInput()
    {
        $this->password = '';
        $this->errorMessage = '';
        $this->infoMessage = '';
    }

    public function render()
    {
        return view('livewire.password-modal');
    }

    public static function getShowSetAdmin(): bool
    {
        $result = false;
        $adminUsers = User::role('admin')->get();
        if (!$adminUsers->isNotEmpty()) {
            $result = true;
        }
        return $result;
    }
}
