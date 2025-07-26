<?php

namespace App\Http\Livewire;

use App\Models\TransportDocumentLine;
use Livewire\Component;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\On; 

class TransportDocumentLines extends Component
{
    const TransportDocumentLinesValidation = 'TransportDocumentLinesValidation';
    public $document_id; // Aktīvā dokumenta ID
    public $lines = []; // Transporta dokumenta līnijas\
    
    //protected $listeners = ['saveLines']; // Klausās uz saveLines notikumu

    protected $rules = [
        'lines.*.product_code' => 'required|string|max:255',
        'lines.*.product_name' => 'required|string|max:255',
        'lines.*.quantity' => 'required|numeric|min:0',   // Daudzumam jābūt lielākam par 0
        'lines.*.price' => 'required|numeric|min:0',   // Cena jābūt lielāka par 0
        'lines.*.total' => 'nullable|numeric|min:0', 
    ];
    
    // Kad komponents tiek inicializēts
    public function mount($document_id)
    {
        // Ielādē līnijas no datubāzes par konkrēto dokumentu
        $this->document_id = $document_id;
        // $this->lines = TransportDocumentLine::where('transport_document_id', $this->document_id)->get()->toArray();
    }

    // Saglabā transporta dokumenta līnijas
    #[On('saveLines')]
    public function saveLines()
    {
        $result = ['status' => 'processing'];
        session()->put(self::TransportDocumentLinesValidation, $result);
        
        try {
            // Validācija
            $validated = $this->validate();
            $result = ['status' => 'success'];
        } catch (ValidationException $e) {
            // Ja ir validācijas kļūdas, atgriez tās
            $result = [
                'status' => 'error',
                'errors' => $e->errors(), // Atgriežam validācijas kļūdas
            ];
        } catch (\Exception $e) {
            // Ja notiek cita kļūda
            $result = [
                'status' => 'error',
                'message' => [$e->getMessage()],
            ];
        }

        session()->put(self::TransportDocumentLinesValidation, $result);

        if ($result['status'] !== 'success') {
            return;
        }

        foreach ($this->lines as $line) {
            TransportDocumentLine::updateOrCreate(
                ['id' => $line['id'] ?? null], // Pārbauda, vai līnija jau eksistē
                [
                    'transport_document_id' => $this->document_id,
                    'product_code' => $line['product_code'],
                    'product_name' => $line['product_name'],
                    'quantity' => $line['quantity'],
                    'price' => $line['price'],
                    'total' => $line['total'],
                ]
            );
        }

        session()->flash('message', 'Transporta dokumenta līnijas saglabātas!');
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

    // Dzēš rindu no tabulas
    public function removeLine($index)
    {
        unset($this->lines[$index]);
        $this->lines = array_values($this->lines); // Pārraksta masīvu, lai indeksēšana būtu secīga
    }

    public function render()
    {
        return view('livewire.transport-document-lines');
    }

    // Vispārējā Livewire metode, kas izsaucas, kad tiek mainīti modeļi
    public function updated($field)
    {
        // Ja tiek mainīts quantity vai price, pārrēķina total
        /*if (str_contains($field, 'quantity') || str_contains($field, 'price')) {
            foreach ($this->lines as $index => $line) {
                $this->lines[$index]['total'] = round(round($this->lines[$index]['quantity'], 6) * $this->lines[$index]['price'], 2);
            }
        }*/
    }

    // Validē tikai vienu lauku
    public function validateField($field)
    {
        $this->validateOnly($field);
    }

    // Pielāgotie kļūdu ziņojumi
    protected function messages()
    {
        return [
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
