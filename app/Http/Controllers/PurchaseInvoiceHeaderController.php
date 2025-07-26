<?php
namespace App\Http\Controllers;

use App\Models\PurchaseInvoiceHeader;
use Illuminate\Http\Request;

class PurchaseInvoiceHeaderController extends Controller
{
    /**
     * Rāda visu pirkuma rēķinu galveņu sarakstu.
     */
    public function index()
    {
        $invoices = PurchaseInvoiceHeader::all();
        return response()->json($invoices);
    }

    /**
     * Saglabā jaunu pirkuma rēķina galveni.
     */
    public function store(Request $request)
    {
        $request->validate([
            'invoice_number' => 'required|unique:purchase_invoice_headers',
            'invoice_date' => 'required|date',
            'supplier_name' => 'required|string',
            'total_amount' => 'required|numeric',
            'tax_amount' => 'required|numeric',
            'net_amount' => 'required|numeric',
            'buyer_name' => 'nullable|string',
            'buyer_address' => 'nullable|string',
            'buyer_registration_number' => 'nullable|string', // Jauns lauks
            'seller_name' => 'nullable|string',
            'seller_address' => 'nullable|string',
            'seller_registration_number' => 'nullable|string', // Jauns lauks
            'waybill_number' => 'nullable|string',
            'waybill_date' => 'nullable|date',
            'additional_info' => 'nullable|string',
        ]);

        $invoice = PurchaseInvoiceHeader::create($request->all());

        return response()->json($invoice, 201);
    }

    /**
     * Rāda konkrētu pirkuma rēķina galveni.
     */
    public function show($id)
    {
        $invoice = PurchaseInvoiceHeader::findOrFail($id);
        return response()->json($invoice);
    }

    /**
     * Atjaunina konkrētu pirkuma rēķina galveni.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'invoice_number' => 'required|unique:purchase_invoice_headers,invoice_number,'.$id,
            'invoice_date' => 'required|date',
            'supplier_name' => 'required|string',
            'total_amount' => 'required|numeric',
            'tax_amount' => 'required|numeric',
            'net_amount' => 'required|numeric',
            'buyer_name' => 'nullable|string',
            'buyer_address' => 'nullable|string',
            'buyer_registration_number' => 'nullable|string', // Jauns lauks
            'seller_name' => 'nullable|string',
            'seller_address' => 'nullable|string',
            'seller_registration_number' => 'nullable|string', // Jauns lauks
            'waybill_number' => 'nullable|string',
            'waybill_date' => 'nullable|date',
            'additional_info' => 'nullable|string',
        ]);

        $invoice = PurchaseInvoiceHeader::findOrFail($id);
        $invoice->update($request->all());

        return response()->json($invoice);
    }

    /**
     * Izdzēš konkrētu pirkuma rēķina galveni.
     */
    public function destroy($id)
    {
        $invoice = PurchaseInvoiceHeader::findOrFail($id);
        $invoice->delete();

        return response()->json(null, 204);
    }
}
