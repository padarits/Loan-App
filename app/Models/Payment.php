<?php  
  
namespace App\Models;  
  
use Illuminate\Database\Eloquent\Factories\HasFactory;  
use Illuminate\Database\Eloquent\Model;  
  
class Payment extends Model  
{  
    use HasFactory;  
  
    /**  
     * The attributes that can be mass assigned.  
     * This allows Laravel's mass assignment to populate these fields.  
     *  
     * @var array  
     */  
    protected $fillable = [  
        'num',  
        'loan_id',  
        'payment_date',  
        'amount',  
        'status',  
        'interest_portion',  
        'principal_portion',  
        'remaining_balance'  
    ];  
  
    /**  
     * Relationship: Each Payment belongs to a single Loan.  
     *  
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo  
     */  
    public function loan()  
    {  
        return $this->belongsTo(Loan::class);  
    }  
}  