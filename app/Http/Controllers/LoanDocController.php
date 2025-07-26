<?php  
  
namespace App\Http\Controllers;  
  
use App\Models\Loan;  
use Illuminate\Http\Request;  
use App\Utils\MainUtils;  
use Mpdf\Mpdf;  
  
class LoanDocController extends Controller  
{  
    /**  
     * Generate a PDF document for a given loan ID.  
     *  
     * @param int $loanId  
     * @return \Illuminate\Http\Response  
     */  
    public function generatePdf($loanId)  
    {  
        // Create a new Mpdf instance, specifying a custom temp directory for storing generated files  
        $mpdf = new Mpdf(['tempDir' => dirname(dirname(dirname(__DIR__))) . '/tmp/mpdf']);  
  
        // Define CSS styles to be applied to the PDF document  
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
  
        // Apply the CSS styles to the PDF  
        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);  
  
        // Retrieve all necessary data for the specified loan ID  
        $loanData = $this->getLoanData($loanId);  
  
        // Render a Blade view into HTML, passing in the loan data  
        $html = view('pdf.loan', $loanData)->render();  
  
        // Write the rendered HTML content into the PDF  
        $mpdf->WriteHTML($html);  
  
        // Return the generated PDF as a browser response  
        return response($mpdf->Output('', 'S'), 200)  
            ->header('Content-Type', 'application/pdf')  
            ->header('Content-Disposition', 'inline; filename="invoice_' . $loanId . '.pdf"');  
    }  
  
    /**  
     * Retrieve the data required to build the PDF from the database.  
     *  
     * @param  int  $loanId  
     * @return array  
     */  
    private function getLoanData($loanId)  
    {  
        // Fetch the Loan model by ID, including related 'payments'  
        $loan = Loan::with('payments')->findOrFail($loanId);  
  
        // Prepare and return an array of data for the Blade view  
        return [  
            'contract_number'     => $loan->num,  
            'contract_date'       => optional($loan->contract_date)->format('Y-m-d'),  
            'lender_name'         => $loan->lender_name,  
            'lender_reg_number'   => $loan->lender_reg_number,  
            'lender_address'      => $loan->lender_address,  
            'borrower_name'       => $loan->borrower_name,  
            'borrower_reg_number' => $loan->borrower_reg_number,  
            'borrower_address'    => $loan->borrower_address,  
            'principal'           => $loan->amount,  
            'annual_rate'         => $loan->interest_rate,  
            'term_months'         => $loan->term,  
            'start_date'          => optional($loan->start_date)->format('Y-m-d'),  
  
            // Convert each related payment record into a structured array  
            'payments' => $loan->payments->map(function($item) {  
                return [  
                    'payment_number'     => $item->num,  
                    'payment_date'       => $item->payment_date,  
                    'monthly_payment'    => MainUtils::formatNumber($item->amount),  
                    'interest_portion'   => MainUtils::formatNumber($item->interest_portion),  
                    'principal_portion'  => MainUtils::formatNumber($item->principal_portion),  
                    'remaining_balance'  => MainUtils::formatNumber($item->remaining_balance),  
                ];  
            })->toArray(),  
        ];  
    }  
}  