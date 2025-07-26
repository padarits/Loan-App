<?php

namespace App\Http\Controllers;

use App\Models\TransportDocument;
use Illuminate\Http\Request;
use App\Utils\MainUtils;
use Mpdf\Mpdf;

class InvoiceController extends Controller
{
    public function generatePdf($invoiceId)
    {
        $mpdf = new Mpdf(['tempDir' => dirname(dirname(dirname(__DIR__))) . '/tmp/mpdf']);

        // Definējiet CSS stilus
        $stylesheet = '
            body { font-family: sans-serif; font-size: 12pt; }
            .header { text-align: center; margin-bottom: 20px; }
            .header img { max-width: 150px; }
            .invoice-details { width: 100%; margin-bottom: 20px; }
            .invoice-details td { padding: 5px; vertical-align: top; }
            .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
            .items-table th, .items-table td { border: 1px solid #000; padding: 8px; text-align: left; }
            .items-table th { background-color: #f2f2f2; }
            .total { text-align: right; font-weight: bold; }
            .footer { text-align: center; font-size: 10pt; color: #777; position: fixed; bottom: 0; width: 100%; }
        ';
        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);

        // Iegūstiet rēķina datus, izmantojot $invoiceId
        $invoiceData = $this->getInvoiceData($invoiceId);

        // Iegūstiet HTML saturu
        $html = view('pdf.invoice', $invoiceData)->render();

        $mpdf->WriteHTML($html);

        // Izvadiet PDF saturu
        return response($mpdf->Output('', 'S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="invoice_' . $invoiceId . '.pdf"');
    }

    private function getInvoiceData($invoiceId)
    {
        // Iegūstiet dokumentu no datubāzes ar saistībām
        $document = TransportDocument::with('lines')->findOrFail($invoiceId);

        // Piemēram, izmantojam statiskus datus
        $result = [
            'document_number' => $document->document_number,
            'document_date' => \Carbon\Carbon::parse($document->document_date)->format('d.m.Y'),
            'supplier_name' => $document->supplier_name,
            'supplier_reg_number' => $document->supplier_reg_number,
            'supplier_address' => $document->supplier_address,
            'receiver_name' => $document->receiver_name,
            'receiver_reg_number' => $document->receiver_reg_number,
            'receiver_address' => $document->receiver_address,
            'issuer_name' => $document->issuer_name,
            'receiving_location' => $document->receiving_location,
            'additional_info' => $document->additional_info,
            'status' => $document->status,
            'lines' => $document->lines->map(function($item) {
                return [
                    'product_name' => $item->product_name,
                    'quantity' => MainUtils::formatNumber($item->quantity, 0),
                    'price' => MainUtils::formatNumber($item->price),
                    'total' => MainUtils::formatNumber($item->total),
                ];
            })->toArray(),
            'totalAmount' => MainUtils::formatNumber($document->fn_total_sum),
        ];
        return $result;
    }
}
