<?php
    namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\WarehouseMaterialMovement;
use App\Utils\MainUtils;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\On; 
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Warehouse;
use Illuminate\Support\Str;
use App\Models\User;
use App\Http\Controllers\TransportDocumentController;
class StockReceiptForm extends Component
{
    public const TypeDispatch = 'dispatch';
    public const TypeMoveFor = 'move';
    public const TypeWrittenOffFor = 'writtenoff';
    public const TypeTransitFor = 'transit';

    public $isOpen = false;
    public $warehouseLine;
    public $actionForEntry = self::TypeDispatch;
    public $isTransit = false;

    #[Locked]
    public $guid;
    #[Locked]
    public $parent_guid;

    public $article, $article_id, $date, $code, $status, $order_number, $name, $name_2,
            $material_grade, $unit, $quantity, $price_per_unit, $total_price, $supplier, $recipient, $due_date,
            $invoice_number, $supplier_company, $warehouse_date, $issued, $code_2, $type,
            $delta_quantity, $recipientEmail, $recipient_guid;

    public $showDeleteModal = false;
    public $showPrintModal = false;

    public $warehouses = [];
    public $warehouse;
    public $warehouse_code;

    public $pageIds = [];
    public $transitWarehouses = [];
    public $transitWarehouseCode;
    public $invoice_date;
    #[Locked] 
    public $external_int_id;
        
    #[Locked] 
    public $deleteRowsIds = [];    
        
    // Pievienojiet īpašību, lai saglabātu PDF URL
    public $pdfUrl = '';

    protected $rules = [
        // 'actionForEntry' => 'required|in:accept,cancel',
        'parent_guid'=> 'required|string|max:255',
        'warehouse_code'=>'required|exists:warehouses,warehouse_code',
        'article' => 'required|string|max:255',
        'article_id' => 'required|string|max:255',
        'date' => 'required|date',
        'code' => 'required|string|max:255',
        'status'=> 'nullable|in:R,Ri,N,M,-',
        'order_number' => 'required|string|max:255',
        'name' => 'required|string|max:255',
        'name_2' => 'nullable|string|max:255',
        'material_grade' => 'required|string|max:255',
        'unit' => 'required|string|max:255',
        'quantity' => 'required|numeric|min:0.000001',
        'price_per_unit' => 'required|numeric|min:0',
        'total_price' => 'required|numeric|min:0',
        'supplier' => 'required|string|max:255',
        'recipient' => 'nullable|string|max:255',
        'due_date' => 'nullable|date',
        'invoice_number' => 'nullable|string|max:255',
        'supplier_company' => 'nullable|string|max:255',
        'warehouse_date' => 'nullable|date',
        'issued' => 'nullable|boolean',
        'code_2' => 'nullable|string|max:255',
        'transitWarehouseCode' => 'nullable|exists:warehouses,warehouse_code',
        'invoice_date' => 'nullable|date',
        'external_int_id' => 'nullable|numeric',
        //'type' => 'required|in:R,Ri,N,M'
    ];
    
    // Kad komponents tiek inicializēts
    public function mount()
    {
        //$this->transitSelectId = 'transitSelectId' . Str::random(8); 
    }

    #[On('openModal2')]
    public function openModal($id)
    {
        $this->isOpen = true;
        // Reset form fields and validation errors
        $this->resetErrorBag();
        $this->resetValidation(); // Clear validation errors
        $this->resetExcept('isOpen', 'warehouse_code', 'invoice_date'); // Reset all fields except modal state       
        $this->guid = $id;
        // Load the transport document if it exists
        $this->warehouseLine = WarehouseMaterialMovement::find($id)->cloneWithThisGuidAsParent(WarehouseMaterialMovement::TypeDispensed);
        if ($this->warehouseLine) {
            $this->parent_guid = $this->warehouseLine->parent_guid;
            $this->warehouse_code = $this->warehouseLine->warehouse_code;
            $this->warehouse = WarehouseMaterial::getWarhouseByCode($this->warehouseLine->warehouse_code)->toArray();
            $this->article = $this->warehouseLine->article;
            $this->article_id = $this->warehouseLine->article_id;
            $this->date = \Carbon\Carbon::parse($this->warehouseLine->date)->format('d.m.Y'); // Display format
            $this->code = $this->warehouseLine->code;
            $this->status = $this->warehouseLine->status;
            $this->order_number = $this->warehouseLine->order_number;
            $this->name = $this->warehouseLine->name;
            $this->name_2 = $this->warehouseLine->name_2;
            $this->material_grade = $this->warehouseLine->material_grade;
            $this->unit = $this->warehouseLine->unit;
            $this->quantity = $this->warehouseLine->delta_quantity; //quantity;
            $this->price_per_unit = $this->warehouseLine->price_per_unit;
            $this->total_price = $this->warehouseLine->total_price;
            $this->supplier = $this->warehouseLine->supplier;
            $this->recipient = $this->warehouseLine->recipient;
            if ($this->warehouseLine->recipientUser) {
                $this->recipientEmail = $this->warehouseLine->recipientUser->email;
            }
            $this->recipient_guid = $this->warehouseLine->recipient_guid;
            $this->due_date = \Carbon\Carbon::parse($this->warehouseLine->due_date)->format('d.m.Y'); // Display format
            $this->invoice_number = $this->warehouseLine->invoice_number;
            $this->supplier_company = $this->warehouseLine->supplier_company;
            //$this->warehouse_date = \Carbon\Carbon::parse($this->warehouseLine->warehouse_date)->format('d.m.Y'); // Display format
            $this->warehouse_date = \Carbon\Carbon::today()->format('d.m.Y');
            $this->issued = $this->warehouseLine->issued;
            $this->code_2 = $this->warehouseLine->code_2;
            $this->delta_quantity = $this->warehouseLine->delta_quantity;
            $this->external_int_id = $this->warehouseLine->external_int_id;
            $this->transitWarehouses = WarehouseMaterial::getAllWarhousesExcept($this->warehouse_code); // Iegūst visas noliktavas, izņemot pašreizējo
            if($this->transitWarehouseCode){
                $this->transitWarehouseCode = WarehouseMaterial::getFirstWarhouseCodeExcept($this->warehouse_code);
            }
        } else {
            $this->dispatch('showWarning', 'Noliktavas ieraksts nav atrasts!');
        } 

    }

    public function closeModal()
    {
        // $this->reset(); // Atiestata visus formā esošos laukus
        $this->isOpen = false;
        $this->dispatch('dataTableAjaxReload-stock-receipt');
    }
    
    // Vispārējā Livewire metode, kas izsaucas, kad tiek mainīti modeļi
    public function updated($field, $value)
    {
        // Validē tikai konkrēto lauku
        $this->validateOnly($field);
        // Ja tiek mainīts quantity vai price, pārrēķina total
        if (str_contains($field, 'quantity') || str_contains($field, 'price_per_unit')) {
            $this->total_price = MainUtils::formatNumber(WarehouseMaterialMovement::getTotalFor($this->quantity, $this->price_per_unit)); 
        }
        if ($field === 'actionForEntry') {
            $this->isTransit = ($value === self::TypeTransitFor);
        }
        if($this->isTransit){
            if(!$this->transitWarehouseCode){
                $this->transitWarehouseCode = WarehouseMaterial::getFirstWarhouseCodeExcept($this->warehouse_code);
            }
        }
    }  

    #[On('saveData2')]
    public function saveData2()
    {
        // Reset form fields and validation errors
        $this->resetErrorBag();
        $this->resetValidation(); // Clear validation errors
        // Validation
        $validatedData = $this->validate();
        if(!$validatedData['invoice_date']){
            $this->invoice_date = \Carbon\Carbon::today()->format('d.m.Y');
        } else {
            $this->invoice_date = $validatedData['invoice_date'];
        }
        // Save the document
        switch ($this->actionForEntry) {
            case self::TypeDispatch:
                $validatedData['type'] = WarehouseMaterialMovement::TypeDispensed;
                $this->saveClone2($validatedData);
                break;
            case self::TypeTransitFor:
                $model = $this;
                DB::transaction(function () use ($model, $validatedData) {
                    $validatedData['type'] = WarehouseMaterialMovement::TypeSent;
                    $warehouse = $model->saveClone2($validatedData);
                    TransportDocumentController::addWarhouseEntry($warehouse->toArray(), $validatedData['warehouse_code'], $validatedData['transitWarehouseCode']);

                    $validatedData2 = $warehouse->cloneWithThisGuidAsParent(WarehouseMaterialMovement::TypeInTransit)->toArray();
                    $validatedData2['warehouse_code'] = $validatedData['transitWarehouseCode'];                  
                    $model->saveClone2($validatedData2);
                });
                break;
            case self::TypeWrittenOffFor:
                $model = $this;
                $validatedData['type'] = WarehouseMaterialMovement::TypeWrittenOff;
                DB::transaction(function () use ($model, $validatedData) {
                    $model->saveClone2($validatedData);
                });
                break;
            default:
                $this->dispatch('showWarning', 'Darbība nav saglabāta! Nezināms tips.');
                break;
            
        }

        $this->closeModal();
    }

    private function saveClone2($validatedData){
        $warehouse = WarehouseMaterialMovement::create($validatedData);
        if ($warehouse) {
            session()->flash('message', 'Updated successfully!');
        } else {
            // Optionally, you can emit an event to trigger JavaScript
            $this->dispatch('showWarning', 'Darbība nav saglabāta!');
            session()->flash('error', 'Transport Document not found.'); 
        }
        return $warehouse;
    }

    public function render()
    {
        return view('livewire.stock-receipt-form');
    }
}
