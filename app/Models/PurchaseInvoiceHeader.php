<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseInvoiceHeader extends Model
{
    use HasFactory;

    // Norādām, kuras kolonnas ir pieejamas masveida aizpildīšanai
    protected $fillable = [
        'invoice_number',
        'invoice_date',
        'supplier_name',
        'total_amount',
        'tax_amount',
        'net_amount',
        'buyer_name',
        'buyer_address',
        'buyer_registration_number', // Pircēja reģistrācijas numurs
        'seller_name',
        'seller_address',
        'seller_registration_number', // Pārdevēja reģistrācijas numurs
        'waybill_number',
        'waybill_date',
        'additional_info',
    ];
}
