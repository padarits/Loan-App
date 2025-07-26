<?php  
  
namespace App\Models;  
  
use Illuminate\Database\Eloquent\Factories\HasFactory;  
use Illuminate\Database\Eloquent\Model;  
use LoanHelper;  
  
class Loan extends Model  
{  
    use HasFactory;  
  
    /**  
     * The attributes that can be mass assigned.  
     *  
     * @var array  
     */  
    protected $fillable = [  
        'user_id', 
        'num', 
        'amount',  
        'interest_rate',  
        'term',  
        'start_date',  
    ];  
  
    /**  
     * Attribute casting definitions.  
     * This tells Eloquent that 'start_date' should be treated as a date object.  
     *  
     * @var array  
     */  
    protected $casts = [  
        'start_date' => 'date', // Alternatively 'datetime'  
    ];  
  
    /**  
     * The "booted" method of the model.  
     * It is called once the model has been fully initialized by Laravel.  
     * This is where we can register model event listeners (e.g. created, updating).  
     *  
     * @return void  
     */  
    protected static function booted()  
    {  
        /**  
         * When a new Loan record is created in the database,  
         * automatically generate and store its payment schedule.  
         */  
        static::created(function ($loan) {  
            $loan->storeSchedule();  
        });  
  
        /**  
         * When an existing Loan record is updated (e.g., if the amount  
         * or interest rate is changed), re-generate the schedule to  
         * ensure it reflects the new data.  
         */  
        static::updating(function ($loan) {  
            $loan->storeSchedule();  
        });  
    }  
  
    /**  
     * Define the relationship indicating that each Loan belongs to a User.  
     *  
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo  
     */  
    public function user()  
    {  
        return $this->belongsTo(User::class);  
    }  
  
    /**  
     * Define the relationship indicating that each Loan has many associated Payment records.  
     * The payments are ordered by 'payment_date' in ascending order.  
     *  
     * @return \Illuminate\Database\Eloquent\Relations\HasMany  
     */  
    public function payments()  
    {  
        return $this->hasMany(Payment::class)->orderBy('payment_date', 'asc');  
    }  
  
    /**  
     * Recreate (refresh) the payment schedule in the payments table  
     * for the current Loan instance.  
     *  
     * This method is triggered whenever the Loan is created or updated,  
     * ensuring the Payment list stays in sync with the loan data.  
     *  
     * @return void  
     */  
    public function storeSchedule(): void  
    {  
        // First, remove all existing Payment records for this loan,  
        // so we can store a clean, updated schedule.  
        $this->payments()->delete();  
  
        // Next, generate a new amortization schedule using the LoanHelper class.  
        $schedule = LoanHelper::generateAmortizationSchedule(  
            $this->amount,         // Principal  
            $this->interest_rate,  // Annual interest rate  
            $this->term,           // Number of months  
            $this->start_date      // Starting date of the loan  
        );  
  
        // For each calculated payment in the schedule, create a new Payment record in the database.  
        foreach ($schedule as $paymentRow) {  
            $this->payments()->create([  
                'num'               => $paymentRow['payment_number'],  
                'payment_date'      => $paymentRow['payment_date'],  
                'amount'            => $paymentRow['monthly_payment'],  
                'interest_portion'  => $paymentRow['interest_portion'],  
                'principal_portion' => $paymentRow['principal_portion'],  
                'remaining_balance' => $paymentRow['remaining_balance']  
            ]);  
        }  
    }  
}  