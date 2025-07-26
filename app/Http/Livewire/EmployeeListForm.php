<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\Attributes\On; 
use Livewire\Attributes\Locked;
use App\Models\User;
use App\Models\EmployeePosition;
use App\Models\EmployeeForPosition;

class EmployeeListForm extends Component
{
    public $isOpen = false;
    #[Locked]
    public $employee; 
    public $employees, $newPosition, $positionForDepartmentId, $positionId, $employeeForDepartments, $positionIsHead;
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    protected function rules() {
        return [
            'employee.id' => 'nullable|string|max:255',
            'employee.email' => "required|string|max:255|unique:users,email," . $this->employee['id'],
            'employee.name' => 'required|string|max:255',
            'newPosition' => 'nullable|string|max:255',
            'positionIsHead' => 'nullable|boolean'
        ];
    }
    #[On('openModalNewDoc')]
    public function openModalNewDoc()
    {
        $this->isOpen = true;
        $this->resetExcept('isOpen'); // Reset all fields except modal state
        /*$department = new Department(['code'=>'', 'name'=>'', 'parent_code'=>'', 'contact_person'=>'', 'address'=>'', 'city'=>'', 'country'=>'', 'zip'=>'', 'phone'=>'', 'email'=>'', 'description'=>'']);
        $this->department = $department->toArray();
        $this->departments = Department::all()->toArray();*/
    }
    
    #[On('openModalEmployee')]
    public function openModal($id)
    {
        $this->isOpen = true;
        $this->resetExcept('isOpen'); // Reset all fields except modal state
        
        // Load the department if it exists
        $this->employee = User::find($id);
        if ($this->employee) {
            $this->employee = $this->employee->toArray();
        } else {
            $this->dispatch('showWarning', 'Employee not found! id=' . $id);
        } 
        //$this->employees = Employee::all()->toArray();
        $this->setEmployeeForDepartments($this->employee['id']);
    }
    
    private function setEmployeeForDepartments($id) {
        $this->employeeForDepartments = EmployeeForPosition::where('employee_id', $id)
                                                            ->with('employee', 'department', 'position')
                                                            ->get();
    }

    #[On('saveData')]
    public function saveData()
    {
        // Reset form fields and validation errors
        $this->resetErrorBag();
        $this->resetValidation(); // Clear validation errors
        // Validation
        $validatedData = $this->validate();
        /*$this->validate_parent_code();
        $validatedData = $validatedData['department'];
        $departmentData = Department::updateOrCreate(['id' => $validatedData['id']], $validatedData);*/

        $this->closeModal();
    }

    #[On('closeModal')]
    public function closeModal()
    {
        // $this->reset(); // Atiestata visus formā esošos laukus
        $this->isOpen = false;
        $this->dispatch('dataTableAjaxReload-employee-list');
    }

    #[On('positionSelected')]
    public function positionSelected($id)
    {
        // Load the position if it exists
        $position = EmployeePosition::find($id);
        if ($position) {
            $this->positionId = $position->id;
            $this->newPosition = $position->position_name;
            $this->positionForDepartmentId = $position->position_for_department_id;
            $this->positionIsHead = $position->is_head;
        } else {
            $this->dispatch('showWarning', 'Position not found! id=' . $id);
        } 
    }

    #[On('addPosition')]
    public function addPosition()
    {
        if (!$this->newPosition) {
            $this->dispatch('showWarning', 'Jaunā amata nosaukums ir obligāts!');	
            return;
        }
        if (!$this->positionId) {
            $position = EmployeePosition::create([
                'position_name' => $this->newPosition,
                'position_for_department_id' => null,
                'is_head' => false
            ]);
            EmployeeForPosition::create([
                'employee_id' => $this->employee['id'],
                'department_id' => null,
                'position_id' => $position->id,
                'is_head' => false
            ]);
            $this->dispatch('showInfo', 'Amats ir izveidots un pievienots!');
        } else {
            EmployeeForPosition::create([
                'employee_id' => $this->employee['id'],
                'department_id' => $this->positionForDepartmentId,
                'position_id' => $this->positionId,
                'is_head' => $this->positionIsHead
            ]);
            $this->dispatch('showSuccess', 'Amats ir pievienots!');
        }
        $this->setEmployeeForDepartments($this->employee['id']);
        $this->positionId = null;
        $this->newPosition = null;
        $this->positionForDepartmentId = null;
        $this->positionIsHead = false;
    }

    #[On('deletePosition')]
    public function deletePosition($id)
    {
        EmployeeForPosition::find($id)->delete();
        $this->dispatch('showSuccess', 'Amats ir atvienots!');
        $this->setEmployeeForDepartments($this->employee['id']);
    }
    
    #[On('changePositionIsHead')]
    public function changePositionIsHead($id)
    {
        $this->dispatch('showSuccess', 'Vadītāja statuss ir mainīts!');
        $employeeForPosition = EmployeeForPosition::find($id);
        $employeeForPosition->is_head = !$employeeForPosition->is_head;
        $employeeForPosition->save();
        $this->setEmployeeForDepartments($this->employee['id']);
    }
    
    // Vispārējā Livewire metode, kas izsaucas, kad tiek mainīti modeļi
    /*public function updated($field, $value)
    {
        // Validē tikai konkrēto lauku
        // $this->validateOnly($field);
        if (str_contains($field, 'positionIsHead')) {
            $this->dispatch('showSuccess', 'Updated!');
            $validatedData = $this->validateOnly($field);
            print_r($validatedData);
            $this->setEmployeeForDepartments($this->employee['id']);
        }    
    }*/

    public function render()
    {
        return view('livewire.employee-list-form');
    }
}
