<?php  
  
namespace App\Http\Livewire;  
  
use Livewire\Component;  
use App\Models\Loan;  
use Illuminate\Support\Facades\Auth;  
use Livewire\Attributes\On;  
  
class LoanManager extends Component  
{  
    // Public properties to bind to form fields or store data  
    public $loanId;  
    public $amount;  
    public $interest_rate;  
    public $term;  
    public $start_date;  
    public $updateMode = false;  
    public $pdfUrl = '';  
    public $showPrintModal = false;  
  
    /**  
     * This Livewire attribute listens for the 'openPdfPhp' event.  
     * It then sets the PDF URL and displays the modal.  
     */  
    #[On('openPdfPhp')]  
    public function openPdfPhp($loanId)  
    {  
        // Generate the PDF route URL for the specified loan  
        $this->pdfUrl = route('Loan.pdf', ['loanId' => $loanId]);  
        $this->showPrintModal = true;  
    }  
  
    /**  
     * Hide the PDF modal.  
     *  
     * @return void  
     */  
    public function cancelModal()  
    {  
        $this->showPrintModal = false;  
    }  
  
    /**  
     * Validation rules for creating or updating a loan.  
     *  
     * @var array  
     */  
    protected $rules = [  
        'amount'         => 'required|numeric|min:0',  
        'interest_rate'  => 'required|numeric|min:0',  
        'term'           => 'required|integer|min:1',  
        'start_date'     => 'required|date|after_or_equal:today',  
    ];  
  
    /**  
     * Renders the Livewire component.  
     *  
     * Queries the database for all loans belonging to the current user,  
     * orders them by ID in descending order, and passes them to the  
     * 'livewire.loan-manager' view.  
     *  
     * @return \Illuminate\Contracts\View\View  
     */  
    public function render()  
    {  
        $loans = Loan::where('user_id', Auth::id())  
                     ->with('user')  
                     ->orderBy('id', 'desc')  
                     ->get();  
  
        return view('livewire.loan-manager', compact('loans'));  
    }  
  
    /**  
     * Reset the form fields to their default (empty) values.  
     *  
     * @return void  
     */  
    private function resetInputFields()  
    {  
        $this->loanId        = null;  
        $this->amount        = null;  
        $this->interest_rate = null;  
        $this->term          = null;  
        $this->start_date    = null;  
    }  
  
    /**  
     * Create a new loan record in the database.  
     *  
     * @return void  
     */  
    public function store()  
    {  
        $this->validate();  
  
        Loan::create([  
            'user_id'       => Auth::id(), // Retrieve the ID from currently logged in user  
            'amount'        => $this->amount,  
            'interest_rate' => $this->interest_rate,  
            'term'          => $this->term,  
            'start_date'    => $this->start_date,  
        ]);  
  
        session()->flash('message', 'Loan successfully created!');  
        $this->resetInputFields();  
    }  
  
    /**  
     * Edit an existing loan record.  
     * Loads loan data into form fields for further editing.  
     *  
     * @param int $id  
     * @return void  
     */  
    public function edit($id)  
    {  
        // Ensure that the loan belongs to the current user. If not, throw a 404.  
        $loan = Loan::where('id', $id)  
                    ->where('user_id', Auth::id())  
                    ->firstOrFail();  
  
        // Populate form fields with the existing loan data  
        $this->loanId        = $loan->id;  
        $this->amount        = $loan->amount;  
        $this->interest_rate = $loan->interest_rate;  
        $this->term          = $loan->term;  
        $this->start_date    = optional($loan->start_date)->format('Y-m-d');  
  
        $this->updateMode = true;  
    }  
  
    /**  
     * Update the loan data with new values entered by the user.  
     *  
     * @return void  
     */  
    public function update()  
    {  
        $this->validate();  
  
        // Verify that this loan ID belongs to the current user  
        $loan = Loan::where('id', $this->loanId)  
                    ->where('user_id', Auth::id())  
                    ->firstOrFail();  
  
        // Update the loan using the form fields  
        $loan->update([  
            'amount'        => $this->amount,  
            'interest_rate' => $this->interest_rate,  
            'term'          => $this->term,  
            'start_date'    => $this->start_date,  
        ]);  
  
        session()->flash('message', 'Loan successfully updated!');  
        $this->resetInputFields();  
        $this->updateMode = false;  
    }  
  
    /**  
     * Delete the specified loan record from the database.  
     *  
     * @param int $id  
     * @return void  
     */  
    public function delete($id)  
    {  
        $loan = Loan::where('id', $id)  
                    ->where('user_id', Auth::id())  
                    ->firstOrFail();  
  
        $loan->delete();  
  
        session()->flash('message', 'Loan deleted.');  
        $this->resetInputFields();  
        $this->updateMode = false;  
    }  
  
    /**  
     * Cancel editing and reset the form fields/UI state.  
     *  
     * @return void  
     */  
    public function cancel()  
    {  
        $this->resetInputFields();  
        $this->updateMode = false;  
    }  
}  