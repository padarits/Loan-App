<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\Attributes\On; 
use Livewire\Attributes\Locked;
use App\Models\EmployeePosition;
use App\Models\Department;
use Illuminate\Support\Str;
class PositionListForm extends Component
{
    public $isOpen = false;
    public $position, $positions, $departments;//, $position_for_department_id;
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    protected function rules() {
        return [
            'position.id' => 'nullable|string|max:255',
            'position.position_name' => 'required|string|max:255',
            'position.position_for_department_id' => 'nullable|string|max:255|exists:departments,id',
            'position.is_head' => "nullable|boolean",
            //'employee.name' => 'required|string|max:255',
        ];
    }
    #[On('openModalNewDocPosition')]
    public function openModalNewDoc()
    {
        $this->isOpen = true;
        $this->resetExcept('isOpen'); // Reset all fields except modal state
        $position = new EmployeePosition(['id' => '', 'position_name' => '', 'position_for_department_id' => null, 'department.name'=>'', 'is_head'=>false]);
        $this->position = $position->toArray();
        $this->position['is_head'] = false;
        $this->departments = Department::all()->toArray();
    }
    
    #[On('openModalPosition')]
    public function openModal($id)
    {
        $this->isOpen = true;
        $this->resetExcept('isOpen'); // Reset all fields except modal state
        
        // Load the position if it exists
        $this->position = EmployeePosition::find($id);
        if ($this->position) {
            $this->position = $this->position->toArray();
        } else {
            $this->dispatch('showWarning', 'Position not found! id=' . $id);
        } 
        
        $this->departments = Department::all()->toArray();
    }

    #[On('saveData')]
    public function saveData()
    {
        // Reset form fields and validation errors
        $this->resetErrorBag();
        $this->resetValidation(); // Clear validation errors
        // Validation
        $validatedData = $this->validate();
        $validatedData = $validatedData['position'];
        $id = isset($validatedData['id']) ? $validatedData['id'] : null;
        $employeePosition = EmployeePosition::updateOrCreate(['id' => $id], $validatedData);

        $this->closeModal();
    }

    #[On('closeModal')]
    public function closeModal()
    {
        // $this->reset(); // Atiestata visus formā esošos laukus
        $this->isOpen = false;
        $this->dispatch('dataTableAjaxReload-positions');
    }
    
    public function render()
    {
        return view('livewire.position-list-form');
    }
}
