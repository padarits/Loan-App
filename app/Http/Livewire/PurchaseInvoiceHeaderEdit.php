<?php

namespace App\Http\Livewire;

use App\Models\PurchaseInvoiceHeader;
use Livewire\Component;

class PurchaseInvoiceHeaderEdit extends Component
{
    public $isOpen = false; // Lai pārvaldītu modālā loga redzamību
    public $purchaseInvoiceHeader; // Pirkuma rēķina galvene, kuru rediģēsim
    public $invoice_number, $invoice_date, $buyer_name, $buyer_address, $seller_name, $seller_address, $waybill_number, $waybill_date, $additional_info;

    // Noteikumi datu validācijai
    protected $rules = [
        'invoice_number' => 'required',
        'invoice_date' => 'required|date',
        'buyer_name' => 'nullable|string',
        'buyer_address' => 'nullable|string',
        'seller_name' => 'nullable|string',
        'seller_address' => 'nullable|string',
        'waybill_number' => 'nullable|string',
        'waybill_date' => 'nullable|date',
        'additional_info' => 'nullable|string',
    ];

    // Metode, lai atvērtu modālo logu un ielādētu rēķinu
    public function openModal($id)
    {
        $this->isOpen = true;
        $this->purchaseInvoiceHeader = PurchaseInvoiceHeader::findOrFail($id);
        $this->fill($this->purchaseInvoiceHeader->toArray()); // Aizpilda formu ar datiem
    }

    // Metode, lai aizvērtu modālo logu
    public function closeModal()
    {
        $this->reset(); // Atiestatīt visus datus
        $this->isOpen = false;
    }

    // Metode datu saglabāšanai
    public function saveData()
    {
        $this->validate(); // Validē datus
        $this->purchaseInvoiceHeader->update([
            'invoice_number' => $this->invoice_number,
            'invoice_date' => $this->invoice_date,
            'buyer_name' => $this->buyer_name,
            'buyer_address' => $this->buyer_address,
            'seller_name' => $this->seller_name,
            'seller_address' => $this->seller_address,
            'waybill_number' => $this->waybill_number,
            'waybill_date' => $this->waybill_date,
            'additional_info' => $this->additional_info,
        ]);

        session()->flash('message', 'Pirkuma rēķins veiksmīgi atjaunināts!');
        $this->closeModal(); // Aizver modālo logu pēc saglabāšanas
    }

    public function render()
    {
        return view('livewire.purchase-invoice-header-edit');
    }
}

