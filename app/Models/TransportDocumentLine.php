<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Str;

class TransportDocumentLine extends Model
{
    use HasFactory;

    // Norāda, ka primārā atslēga ir UUID
    protected $keyType = 'string'; // UUID ir string
    public $incrementing = false; // Neinkrementē
    protected $primaryKey = 'id'; // Norāda, ka 'id' ir primārā atslēga

    protected $fillable = ['transport_document_id', 'product_code', 'product_name', 'quantity', 'price', 'total'];

    // Pievieno "updating" eventu
    protected static function boot()
    {
        parent::boot();
        // Pirms izveides notikuma pievieno automātisku UUID ģenerēšanu
        static::creating(function ($document) {
            if (empty($document->id)) {
                $document->id = (string) Str::uuid(); // Ģenerē UUID, ja tas nav norādīts
            }
            $document->total = self::getSumFor($document->quantity, $document->price);
        });
        
        static::updating(function ($document) {
            if ($document->isDirty('quantity') or $document->isDirty('price') or $document->isDirty('total')) { // pārbauda vai 'quantity, price, total' ir mainīts
                $document->total = self::getSumFor($document->quantity, $document->price);
            }
        });
    }
    
    // Atsauce uz transporta dokumenta galveni
    public function document()
    {
        return $this->belongsTo(TransportDocument::class, 'transport_document_id');
    }
    
    public static function getSumFor($quantity, $price){
        return round(round($quantity, 6) * round($price, 6), 2);
    }
}

