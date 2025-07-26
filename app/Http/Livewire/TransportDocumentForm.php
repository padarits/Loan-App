<?php
namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\TransportDocument;
use App\Http\Livewire\TransportDocumentLines;
use App\Models\TransportDocumentLine;
use App\Http\Controllers\TransportDocumentController;
use App\Utils\MainUtils;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\On; 
use Livewire\Attributes\Locked;
//use Livewire\Features\SupportAttributes\AttributeCollection;

class TransportDocumentForm extends Component
{
    public $isOpen = false;
    public $transportDocument;
    public $document_number;
    public $document_date;
    public $supplier_name;
    public $supplier_reg_number;
    public $supplier_address;
    public $receiver_name;
    public $receiver_reg_number;
    public $receiver_address;
    public $issuer_name;
    public $receiving_location;
    public $additional_info;
    #[Locked]
    public $id;
    public $status;
    public $vehicle_registration_number;

    public $document_id; // Aktīvā dokumenta ID
    public $lines = []; // Transporta dokumenta līnijas\
    public $lineToDelete = null;
    public $showDeleteModal = false;
    public $showPrintModal = false;

    //public AttributeCollection $attributes;
    //protected $listeners = ['itemSelected'];
    #[Locked] 
    public $deleteRowsIds = [];    
        
    // Pievienojiet īpašību, lai saglabātu PDF URL
    public $pdfUrl = '';

    protected $rules = [
        'document_number' => 'required|string|max:255',
        'document_date' => 'required|date',
        'supplier_name' => 'required|string|max:255',
        'supplier_reg_number' => 'required|string|max:255',
        'supplier_address' => 'required|string|max:255',
        'receiver_name' => 'required|string|max:255',
        'receiver_reg_number' => 'required|string|max:255',
        'receiver_address' => 'required|string|max:255',
        'issuer_name' => 'required|string|max:255',
        'receiving_location' => 'required|string|max:255',
        'additional_info' => 'nullable|string',
        'status' => 'required|in:010-new,020-prepared,030-in_transit,040-received,050-waiting,060-canceled', // Validācija statusam
        'vehicle_registration_number' => 'nullable|string|max:255',	
        'lines.*.product_code' => 'required|string|max:255',
        'lines.*.product_name' => 'required|string|max:255',
        'lines.*.quantity' => 'required|numeric|min:0',   // Daudzumam jābūt lielākam par 0
        'lines.*.price' => 'required|numeric|min:0',   // Cena jābūt lielāka par 0
    ];
    
    // Kad komponents tiek inicializēts
    public function mount()
    {

    }

    #[On('openModal')]
    public function openModal($id)
    {
        $this->isOpen = true;
        $this->resetExcept('isOpen'); // Reset all fields except modal state
        $this->deleteRowsIds = []; 
        $this->lineToDelete = -1;
        
        $this->id = $id;
        // Load the transport document if it exists
        $this->transportDocument = TransportDocument::find($id);
        if ($this->transportDocument) {
            $this->document_number = $this->transportDocument->document_number;
            $this->document_date = \Carbon\Carbon::parse($this->transportDocument->document_date)->format('d.m.Y'); // Display format
            $this->supplier_name = $this->transportDocument->supplier_name;
            $this->supplier_reg_number = $this->transportDocument->supplier_reg_number;
            $this->supplier_address = $this->transportDocument->supplier_address;
            $this->receiver_name = $this->transportDocument->receiver_name;
            $this->receiver_reg_number = $this->transportDocument->receiver_reg_number;
            $this->receiver_address = $this->transportDocument->receiver_address;
            $this->issuer_name = $this->transportDocument->issuer_name;
            $this->receiving_location = $this->transportDocument->receiving_location;
            $this->additional_info = $this->transportDocument->additional_info;
            $this->status = $this->transportDocument->status;
            $this->vehicle_registration_number = $this->transportDocument->vehicle_registration_number;

            // Ielādē līnijas no datubāzes par konkrēto dokumentu
            $this->document_id = $id;
            $this->lines = TransportDocumentLine::where('transport_document_id', $this->document_id)
                                                ->orderBy('created_at', 'asc')
                                                ->get()
                                                ->toArray();
            foreach ($this->lines as $index => $line) {
                $this->lines[$index]['quantity'] = MainUtils::formatNumber($line['quantity'], 0);
                $this->lines[$index]['price'] = MainUtils::formatNumber($line['price']);
                $this->lines[$index]['total'] = MainUtils::formatNumber($line['total']);
            }
        } else {
            $this->dispatch('showWarning', 'Transport Document not found');
        } 

    }
    
    #[On('openModalNewDoc')]
    public function openModalNewDoc()
    {
        $this->isOpen = true;
        $this->resetExcept('isOpen'); // Reset all fields except modal state
        $this->deleteRowsIds = []; 
        $this->lineToDelete = -1;
        $this->document_date = date('d.m.Y');
        $this->status = '010-new';
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
        $result = TransportDocumentController::getLastReceiverAddressFor($code, $name); // Iegūst pēdējo adresi
        $this->receiver_reg_number = $code;
        $this->receiver_name = $name;
        $this->receiver_address = isset($result['receiver_address']) ? $result['receiver_address'] : '';
        $this->receiving_location = isset($result['receiving_location']) ? $result['receiving_location'] : '';
    }

    #[On('supplierSelected')]
    public function supplierSelected($code, $name)
    {
        $result = TransportDocumentController::getLastSupplierAddressFor($code, $name); // Iegūst pēdējo adresi
        $this->supplier_reg_number = $code;
        $this->supplier_name = $name;
        $this->supplier_address = isset($result['supplier_address']) ? $result['supplier_address'] : '';
    }

    #[On('receiverAddressSelected')]
    public function receiverAddressSelected($address, $location)
    {
        $this->receiver_address = $address ? $address : '';
        $this->receiving_location = $location ? $location : '';
    }
    
    #[On('supplierAddressSelected')]
    public function supplierAddressSelected($address)
    {
        $this->supplier_address = $address ? $address : '';
    }

    #[On('saveData')]
    public function saveData()
    {
        // Reset form fields and validation errors
        $this->resetErrorBag();
        $this->resetValidation(); // Clear validation errors
      
        // Validation
        $validatedData = $this->validate();

        // Check if editing an existing record or creating a new one
        if ($this->id) {
            // Update existing TransportDocument
            $transportDocument = TransportDocument::find($this->id);
            if ($transportDocument) {
                $transportDocument->update($validatedData);
                session()->flash('message', 'Transport Document Header updated successfully!');
            } else {
                // Optionally, you can emit an event to trigger JavaScript
                $this->dispatch('showWarning', 'Transport Document not found.');
                session()->flash('error', 'Transport Document not found.'); 
            }
        } else {
            // Validation
            $validatedData = $this->validate();
            
            // Create new TransportDocument
            $transportDocument = TransportDocument::create($validatedData);
            // $this->dispatch('showSuccess', 'Transport Document created successfully!');
            session()->flash('message', 'Transport Document created successfully!');
        }   
        
        foreach ($this->lines as $line) {
            TransportDocumentLine::updateOrCreate(
                ['id' => $line['id'] ?? null], // Pārbauda, vai līnija jau eksistē
                [
                    'transport_document_id' => $this->document_id ? $this->document_id : $transportDocument->id,
                    'product_code' => $line['product_code'],
                    'product_name' => $line['product_name'],
                    'quantity' => $line['quantity'],
                    'price' => $line['price'],
                    //'total' => $this->getTotalSum($line['quantity'], $line['price']),
                ]
            );
        }
        session()->flash('message', 'Transporta dokumenta līnijas saglabātas!');

        //$this->dispatch('showSuccess', "deleteRowsIds: " . json_encode($this->deleteRowsIds));
        foreach ($this->deleteRowsIds as $id) {
            TransportDocumentLine::destroy($id);
            session()->flash('message', 'Transport Document Line delete successfully! id:' . $id);
        }   
        
        // $this->dispatch('showSuccess', 'Transporta Documēnts saglabāts.');
        
        $this->closeModal();
    }

    // Pievieno jaunu rindu tabulā
    public function addLine()
    {
        $this->lines[] = [
            'product_code' => '',
            'product_name' => '',
            'quantity' => 1,
            'price' => 0,
            'total' => 0,
        ];
    }
    
    public function closeModal()
    {
        // $this->reset(); // Atiestata visus formā esošos laukus
        $this->isOpen = false;
        $this->dispatch('dataTableAjaxReload');
    }

    // Vispārējā Livewire metode, kas izsaucas, kad tiek mainīti modeļi
    public function updated($field, $value)
    {
        // Validē tikai konkrēto lauku
        $this->validateOnly($field);
        // Ja tiek mainīts quantity vai price, pārrēķina total
        if (str_contains($field, 'quantity') || str_contains($field, 'price')) {
            foreach ($this->lines as $index => $line) {
                $this->lines[$index]['total'] = MainUtils::formatNumber(TransportDocumentLine::getSumFor($this->lines[$index]['quantity'], $this->lines[$index]['price'])); 
            }
        }
        // $this->dispatch('showWarning', 'updated:' . $value);
    }  

    // Dzēš rindu no tabulas
    public function removeLine()
    {
        $this->showDeleteModal = false;
        
        if ( isset($this->lines[$this->lineToDelete]['id']) and $this->lines[$this->lineToDelete]['id'] > 0) {
            array_push($this->deleteRowsIds, $this->lines[$this->lineToDelete]['id']);
        }
        unset($this->lines[$this->lineToDelete]);
        $this->lines = array_values($this->lines); // Pārraksta masīvu, lai indeksēšana būtu secīga
    }
    
    // Validē tikai vienu lauku
    public function validateField($field)
    {
        $this->validateOnly($field);
    }

    // Function to delete the line
    public function confirmDelete($index)
    {
        $this->showDeleteModal = true;
        $this->lineToDelete = $index;
    }
    
    // Function to delete the line
    public function cancelDelete()
    {
        $this->showDeleteModal = false;
    }

    public function render()
    {
        return view('livewire.transport-document-form');
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
