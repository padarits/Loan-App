<?php
    namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\WarehouseMaterialMovement;
use App\Utils\MainUtils;
use Livewire\Attributes\On; 
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use Livewire\Attributes\Session;
class ActiveApplicationsForm extends Component
{
    public const TypeAccept = 'accept';
    public const TypeCancel = 'cancel';
    public const TypeAddedToInventory = 'added_to_inventory';

    public $isOpen = false;
    public $typeAddedToInventoryOnly = false;
    public $warehouseLine;
    public $actionForEntry = self::TypeAccept;
    public $tabName;

    #[Locked]
    public $guid;
    #[Locked]
    public $parent_guid;
    #[Locked]
    public $rec_type, $type;

    public $article, $article_id, $date, $code, $status, $order_number, $name, $name_2,
            $material_grade, $unit, $quantity, $price_per_unit, $total_price, $supplier, $recipient, $due_date,
            $invoice_number, $supplier_company, $warehouse_date, $issued, $code_2,
            $delta_quantity, $recipientEmail, $recipient_guid, $prev_warehouse_code;

    public $showDeleteModal = false;
    public $showPrintModal = false;

    public $warehouses = [];
    public $warehouse_code;
    // #[Session]
    public $invoice_date;
    public $prev_supplier_company;
    #[Locked] 
    public $external_int_id;

    #[Locked] 
    public $deleteRowsIds = [];    
        
    // Pievienojiet ăipašību, lai saglabātu PDF URL
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
        'recipient_guid' => 'nullable|uuid|exists:users,id',
        'due_date' => 'nullable|date',
        'invoice_number' => 'required|string|max:255',
        'supplier_company' => 'nullable|string|max:255',
        'warehouse_date' => 'nullable|date',
        'issued' => 'nullable|boolean',
        'code_2' => 'nullable|string|max:255',
        'prev_warehouse_code' => 'nullable|string|max:255',
        'rec_type' => 'required|string|max:255',
        'type' => 'required|string|max:255',
        'invoice_date' => 'required|date',
        'external_int_id' => 'nullable|numeric',
    ];
    
    // Kad komponents tiek inicializēts
    public function mount($tabName = null)
    {
        $this->tabName = $tabName;
    }
       
    /**
     * Atver modālo logu un ielādē dokumenta datus, ja tie ir pieejami.
     * @param uuid $id Dokumenta ID
     */
    #[On('openModal1')]
    public function openModal($id)
    {
        $this->isOpen = true;
        // Reset form fields and validation errors
        $this->resetErrorBag();
        $this->resetValidation(); // Clear validation errors
        $this->resetExcept(['isOpen', 'warehouse_code', 'invoice_date', 'invoice_number', 'prev_supplier_company']); // Reset all fields except modal state
        $this->typeAddedToInventoryOnly = false;
        $this->warehouses = WarehouseMaterial::getAllWarhousesForUser()->toArray();

        //$this->guid = $id;
        // Load the document if it exists
        $this->warehouseLine = WarehouseMaterialMovement::find($id)->cloneWithThisGuidAsParent(WarehouseMaterialMovement::TypeReceived);
        if ($this->warehouseLine) {
            $this->parent_guid = $this->warehouseLine->parent_guid;
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
            $this->total_price = MainUtils::formatNumber(WarehouseMaterialMovement::getTotalFor($this->quantity, $this->price_per_unit)); //$this->warehouseLine->total_price;
            $this->supplier = $this->warehouseLine->supplier;
            $this->recipient = $this->warehouseLine->recipient;
            if ($this->warehouseLine->recipientUser) {
                $this->recipientEmail = $this->warehouseLine->recipientUser->email;
            }
            $this->recipient_guid = $this->warehouseLine->recipient_guid;
            $this->due_date = \Carbon\Carbon::parse($this->warehouseLine->due_date)->format('d.m.Y'); // Display format
            //$this->invoice_number = $this->warehouseLine->invoice_number ? $this->warehouseLine->invoice_number : $this->invoice_number;
            $this->supplier_company = $this->warehouseLine->supplier_company;
            //$this->warehouse_date = \Carbon\Carbon::parse($this->warehouseLine->warehouse_date)->format('d.m.Y'); // Display format
            //if(!$this->warehouse_date){
            $this->warehouse_date = \Carbon\Carbon::today()->format('d.m.Y');
            //}
            $this->issued = $this->warehouseLine->issued;
            $this->code_2 = $this->warehouseLine->code_2;
            $this->delta_quantity = $this->warehouseLine->delta_quantity;
            $this->warehouse_code = $this->warehouseLine->warehouse_code;
            $this->prev_warehouse_code = $this->warehouseLine->prev_warehouse_code;
            $this->type = $this->warehouseLine->type;
            $this->rec_type = $this->warehouseLine->rec_type;
            $this->external_int_id = $this->warehouseLine->external_int_id;
            //$this->invoice_date = $this->warehouseLine->invoice_date ? \Carbon\Carbon::parse($this->warehouseLine->invoice_date)->format('d.m.Y') : $this->invoice_date;
            
            if ($this->prev_supplier_company != $this->supplier_company) {
                $this->prev_supplier_company = $this->supplier_company;
                $this->invoice_date = null;
                $this->invoice_number = null;
            }
        } else {
            $this->dispatch('showWarning', 'Noliktavas ieraksts nav atrasts!');
        } 

    }
        
    #[On('openModalNewDocActiveApplicationsForm')]
    public function openModalNewDoc()
    {
        $this->isOpen = true;
        $this->resetErrorBag();
        $this->resetValidation(); // Clear validation errors
        $this->resetExcept(['isOpen', 'warehouse_code', 'invoice_date', 'invoice_number', 'prev_supplier_company']); // Reset all fields except modal state
        $this->typeAddedToInventoryOnly = true;
        $this->warehouses = WarehouseMaterial::getAllWarhousesForUser()->toArray();
        $this->warehouse_date = \Carbon\Carbon::today()->format('d.m.Y');
        $this->material_grade = '-';
    }
    
    #[On('openPdfPhp')]
    public function openPdfPhp()
    {
        // Ģenerēt PDF maršruta URL
        $this->pdfUrl = route('invoice.pdf', ['invoiceId' => $this->document_id]);

        $this->saveData();

        $this->showPrintModal = true;
    
        // Emitējiet notikumu, lai atvērtu jaunu logu ar PDF
        // $this->dispatch('printPdf');
    }
    
    #[On('itemSelected')]
    public function itemSelected($index, $code, $name)
    {
        // $this->dispatch('showWarning', 'itemSelected:' . $code);
        $this->lines[$index]['product_code'] = $code;
        $this->lines[$index]['product_name'] = $name;
    }

    #[On('receiverSelected')]
    public function receiverSelected($code, $name)
    {
        /*$result = TransportDocumentController::getLastReceiverAddressFor($code, $name); // Iegūst pēdējo adresi
        $this->receiver_reg_number = $code;
        $this->receiver_name = $name;
        $this->receiver_address = isset($result['receiver_address']) ? $result['receiver_address'] : '';
        $this->receiving_location = isset($result['receiving_location']) ? $result['receiving_location'] : '';*/
    }

    #[On('articleSelected')]
    public function articleSelected($article, $name, $name_2, $article_id, $material_grade)
    {
        $this->article = $article;
        $this->name = $name;
        $this->name_2 = $name_2;
        $this->article_id = $article_id;
        $this->material_grade = $material_grade;
    }

    #[On('name1Selected')]
    public function name1Selected($name1)
    {
        $this->name = $name1;
    }
    
    #[On('recipientSelected')]
    public function recipientSelected($recipient, $id)
    {
        $this->recipient = $recipient;
        $this->recipient_guid = $id;
        $user = user::class::find($id);
        $this->recipientEmail = $user->email;
    }

    #[On('supplierForItemSelected')]
    public function supplierForItemSelected($supplier, $supplier_company)
    {
        $this->supplier = $supplier;
        $this->supplier_company = $supplier_company;
    }

    #[On('itemCodeSelected')]
    public function itemCodeSelected($itemCodeSelected)
    {
        $this->code = $itemCodeSelected;
    }

    #[On('saveData')]
    public function saveData()
    {
        // Reset form fields and validation errors
        $this->resetErrorBag();
        $this->resetValidation(); // Clear validation errors
        // Validation
        $validatedData = $this->validate();
        $this->invoice_date = $validatedData['invoice_date'];
        $this->invoice_number = $validatedData['invoice_number'];
        //$this->dispatch('showInfo', $this->invoice_date);

        switch ($this->actionForEntry) {
            case self::TypeAccept:
                $validatedData['type'] = WarehouseMaterialMovement::TypeReceived;
                $validatedData['prev_warehouse_code'] = $validatedData['warehouse_code'];
                $this->saveClone($validatedData);
                break;
            case self::TypeCancel:
                switch ($validatedData['rec_type']){
                    case WarehouseMaterialMovement::TypeApplication:
                        $model = $this;
                        $validatedData['type'] = WarehouseMaterialMovement::TypeCanceled;
                        DB::transaction(function () use ($model, $validatedData) {
                            $model->saveClone($validatedData);
                        });
                        break;
                    case WarehouseMaterialMovement::TypeInTransit:
                        $model = $this;
                        $validatedData['type'] = WarehouseMaterialMovement::TypeReceived;
                        DB::transaction(function () use ($model, $validatedData) {
                            $validatedData['warehouse_code'] = $validatedData['prev_warehouse_code'];
                            $model->saveClone($validatedData);
                        });
                        break;
                    default:
                        $this->dispatch('showWarning', 'Darbība nav paveikta! Nezināms tips:' . $validatedData['rec_type']);
                        break;
                }
                break;
            case self::TypeAddedToInventory:
                $validatedData['type'] = WarehouseMaterialMovement::TypeAddedToInventory;
                $validatedData['prev_warehouse_code'] = $validatedData['warehouse_code'];
                $this->saveClone($validatedData);
                break;    
            default:
                $this->dispatch('showWarning', 'Darbība nav paveikta! Nezināms tips:' . $this->actionForEntry);
                break;
            
        }

        $this->closeModal();
    }

    private function saveClone($validatedData){
        if(!WarehouseMaterialMovement::where('article_id', $validatedData['article_id'])
                        ->where('article', $validatedData['article'])
                        ->where('name', $validatedData['name'])
                        ->where('name_2', $validatedData['name_2'])
                        ->where('material_grade', $validatedData['material_grade'])
                        ->limit(1)
                        ->exists()){
                            $validatedData['article_id'] = Str::uuid();
        }

        $warehouse = WarehouseMaterialMovement::create($validatedData);
        if ($warehouse) {
            session()->flash('message', 'Updated successfully!');
        } else {
            // Optionally, you can emit an event to trigger JavaScript
            $this->dispatch('showWarning', 'Darbība nav saglabāta!');
            session()->flash('error', 'Transport Document not found.'); 
        }
    }

    // Pievieno jaunu rindu tabulā
    public function addLine()
    {

    }
    
    public function closeModal()
    {
        // $this->reset(); // Atiestata visus formā esošos laukus
        $this->isOpen = false;
        $this->dispatch('dataTableAjaxReload-active-applications');
    }

    // Vispārējā Livewire metode, kas izsaucas, kad tiek mainīti modeļi
    public function updated($field, $value)
    {
        // Validē tikai konkrēto lauku
        $this->validateOnly($field);
        // Ja tiek mainīts quantity vai price, pārrēķina total
        if (str_contains($field, 'quantity') || str_contains($field, 'price_per_unit')) {
            //foreach ($this->lines as $index => $line) {
            $this->total_price = MainUtils::formatNumber(WarehouseMaterialMovement::getTotalFor($this->quantity, $this->price_per_unit)); 
            //}
        }
        // $this->dispatch('showWarning', 'updated:' . $value);
    }  

    // Dzēš rindu no tabulas
    public function removeLine()
    {
        /*$this->showDeleteModal = false;
        
        if ( isset($this->lines[$this->lineToDelete]['id']) and $this->lines[$this->lineToDelete]['id'] > 0) {
            array_push($this->deleteRowsIds, $this->lines[$this->lineToDelete]['id']);
        }
        unset($this->lines[$this->lineToDelete]);
        $this->lines = array_values($this->lines); // Pārraksta masīvu, lai indeksēšana būtu secīga
        */
    }
    
    // Validē tikai vienu lauku
    public function validateField($field)
    {
        $this->validateOnly($field);
    }

    // Function to delete the line
    public function confirmDelete($index)
    {
        //$this->showDeleteModal = true;
        //$this->lineToDelete = $index;
    }
    
    // Function to delete the line
    public function cancelDelete()
    {
        //$this->showDeleteModal = false;
    }

    public function render()
    {
        return view('livewire.active-applications-form');
    }

    // Pielāgotie kļūdu ziņojumi
    protected function messages()
    {
        return [
            'required' => 'Lauks :attribute ir obligāts.',
            'string' => 'Lauks :attribute ir jābūt virknei.',
            'max' => [
                'string' => 'Lauks :attribute nedrīkst būt garāks par :max rakstzīmēm.',
            ],
            'date' => 'Lauks :attribute ir jābūt derīgam datumam.',
            'in' => 'Izvēlētais lauks :attribute ir nederīgs.',
            
            'attributes' => [
                'document_number' => 'dokumenta numurs',
                'document_date' => 'dokumenta datums',
                'supplier_name' => 'piegādātāja nosaukums',
                'supplier_reg_number' => 'piegādātāja reģistrācijas numurs',
                'supplier_address' => 'piegādātāja adrese',
                'receiver_name' => 'saņēmēja nosaukums',
                'receiver_reg_number' => 'saņēmēja reģistrācijas numurs',
                'receiver_address' => 'saņēmēja adrese',
                'issuer_name' => 'izsniedzēja vārds',
                'receiving_location' => 'saņemšanas vieta',
                'additional_info' => 'papildinformācija',
                'status' => 'statuss',
                'vehicle_registration_number' => 'transportlīdzekļa reģistrācijas numurs',
            ],

            'lines.*.product_code.required' => 'Produkta kods ir obligāts.',
            'lines.*.product_code.string' => 'Produkta kodam jābūt virknei.',
            'lines.*.product_code.max' => 'Produkta kodam nevar būt vairāk par 255 rakstzīmēm.',
            
            'lines.*.product_name.required' => 'Produkta nosaukums ir obligāts.',
            'lines.*.product_name.string' => 'Produkta nosaukumam jābūt virknei.',
            'lines.*.product_name.max' => 'Produkta nosaukumam nevar būt vairāk par 255 rakstzīmēm.',

            'lines.*.quantity.required' => 'Daudzums ir obligāts.',
            'lines.*.quantity.numeric' => 'Daudzumam jābūt skaitliskai vērtībai.',
            'lines.*.quantity.min' => 'Daudzumam jābūt lielākam par 0.',

            'lines.*.price.required' => 'Cena ir obligāta.',
            'lines.*.price.numeric' => 'Cenai jābūt skaitliskai vērtībai.',
            'lines.*.price.min' => 'Cenai jābūt lielākai par 0.',
        ];
    }
}
