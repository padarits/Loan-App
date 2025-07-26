<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Str;

class TransportDocument extends Model
{
    use HasFactory;
    
    public const STATUS_NEW = '010-new';
    public const STATUS_PREPARED = '020-prepared';
    public const STATUS_IN_TRANSIT = '030-in_transit';
    public const STATUS_RECEIVED = '040-received';
    public const STATUS_WAITING = '050-waiting';
    public const STATUS_CANCELED = '060-canceled';
    
    // Norāda, ka primārā atslēga ir UUID
    protected $keyType = 'string'; // UUID ir string
    public $incrementing = false; // Neinkrementē
    protected $primaryKey = 'id'; // Norāda, ka 'id' ir primārā atslēga

    protected $casts = [
        'document_date' => 'date:d.m.Y'
    ];
    
    protected $guarded = ['id'];

    protected $fillable = [
        'document_number',
        'document_date',
        'supplier_name',
        'supplier_reg_number',
        'supplier_address',
        'receiver_name',
        'receiver_reg_number',
        'receiver_address',
        'issuer_name',
        'receiver_person_name',
        'receiving_location',
        'additional_info',
        'status', // transporta dokumenta statuss
        'vehicle_registration_number', // transportlīdzekļa reģistrācijas numurs
        'total_sum', // kopējā summa (varchar[30]) tiek aprēķināta: app\Observers\TransportDocumentLineObserver.php
    ];

    // Pievieno "updating" eventu
    protected static function boot()
    {
        parent::boot();
        
        // Pirms izveides notikuma pievieno automātisku UUID ģenerēšanu
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });

        static::updating(function ($document) {
            if ($document->isDirty('document_date') ) { // pārbauda vai 'document_date' ir mainīts
                $document->document_date_str = Carbon::parse($document->document_date)->format('d.m.Y');
            }
        });
    }

    // Attiecības ar transporta dokumentu līnijām
    public function lines()
    {
        return $this->hasMany(TransportDocumentLine::class, 'transport_document_id', 'id')
                    ->orderBy('created_at', 'asc');
    }

        /**
     * Mutators: pārvērst datumu no formāta d.m.Y uz MySQL Y-m-d formātu
     * @param string $value
     */
    public function setDocumentDateAttribute($value)
    {
        // Ja ievadītais datums nav tukšs, pārvērst to pareizajā formātā
        $this->attributes['document_date'] = Carbon::createFromFormat('d.m.Y', $value)->format('Y-m-d');
    }

    /**
     * Accessors: pārvērst datumu no MySQL formāta uz d.m.Y formātu, kad to izgūst no DB
     * @param string $value
     * @return string
     */
    public function getDocumentDateAttribute($value)
    {
        return Carbon::createFromFormat('Y-m-d', $value)->format('d.m.Y');
    }

    // Metode, lai iegūtu kopējo summu
    public function getFnTotalSumAttribute()
    {
        return $this->lines()->sum('total');
    }

    public static function generateDocumentNumber(){
        return 'TD-' . Carbon::now()->format('Y') . '-' . str_pad(TransportDocument::count() + 1, 4, '0', STR_PAD_LEFT);
    }

}

