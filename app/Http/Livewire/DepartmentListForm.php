<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\Attributes\On; 
use Livewire\Attributes\Locked;
use App\Models\Department;
use Illuminate\Validation\ValidationException;

class DepartmentListForm extends Component
{
    public $isOpen = false;
    public $department, $departments;
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    protected function rules() {
        return [
            'department.id' => 'nullable|string|max:255',
            'department.code' => "required|string|max:255|unique:departments,code," . (isset($this->department['id']) ? $this->department['id'] : 'null'),
            'department.name' => 'required|string|max:255',
            'department.parent_code' => 'nullable|string|max:255|exists:departments,code',
            'department.contact_person' => 'nullable|string|max:255',
            'department.address' => 'nullable|string|max:255',
            'department.city' => 'nullable|string|max:255',
            'department.country' => 'nullable|string|max:255',
            'department.zip' => 'nullable|string|max:255',
            'department.phone' => 'nullable|string|max:255',
            'department.email' => 'nullable|string|email|max:255',
            'department.description' => 'nullable|string|max:255',
        ];
    }
    #[On('openModalNewDoc')]
    public function openModalNewDoc()
    {
        $this->isOpen = true;
        $this->resetExcept('isOpen'); // Reset all fields except modal state
        $department = new Department(['code'=>'', 'name'=>'', 'parent_code'=>'', 'contact_person'=>'', 'address'=>'', 'city'=>'', 'country'=>'', 'zip'=>'', 'phone'=>'', 'email'=>'', 'description'=>'']);
        $this->department = $department->toArray();
        $this->departments = Department::all()->toArray();
    }
    
    #[On('openModal')]
    public function openModal($id)
    {
        $this->isOpen = true;
        $this->resetExcept('isOpen'); // Reset all fields except modal state
        
        // Load the department if it exists
        $this->department = Department::find($id)->toArray();
        if ($this->department) {

        } else {
            $this->dispatch('showWarning', 'Department not found');
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
        $this->validate_parent_code();
        $validatedData = $validatedData['department'];
        $validatedData['parent_code'] = ($validatedData['parent_code'] == "" ? null : $validatedData['parent_code']);
        $departmentData = Department::updateOrCreate(['id' => (isset($validatedData['id']) ? $validatedData['id'] : null)], $validatedData);

        $this->closeModal();
    }

    #[On('closeModal')]
    public function closeModal()
    {
        // $this->reset(); // Atiestata visus formā esošos laukus
        $this->isOpen = false;
        $this->dispatch('dataTableAjaxReload-departments');
    }

    public function render()
    {
        return view('livewire.department-list-form');
    }

    private function validate_parent_code() {
        //$this->checkParentCodes($this->department['parent_code']);
    }
    
    private function checkParentCodes(string $code) {
        $parentCodes = [];
        $deps = Department::where('parent_code', $code);
        foreach ($deps as $dep) {
            $this->check_parent_code($dep->code, $parentCodes);
        }
        return $parentCodes;
    }

    private function check_parent_code(string $code, array &$parentCodes) {
        if (empty($code)) {
            return;
        }
        $dep = Department::where('code', $code)->first();
        if (array_key_exists($dep->code, $parentCodes)) {
            throw ValidationException::withMessages([
                'department.parent_code' => ['Nevar pievienot šo departamentu ka galvēno.'],
            ]);
        } else {
            array_push($parentCodes, $dep->code);
            $this->check_parent_code($dep->parent_code, $parentCodes);
        }
    }
}
