<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Warehouse extends Model
{
    use HasFactory;
    use HasUuids;
    protected $primaryKey = 'guid';
    public $incrementing = false;   // Jo uuid nav autoinkrementējošs
    protected $keyType = 'string';  // Jo uuid ir string, nevis int

    protected $fillable = [
        'name',
        'location',
        'warehouse_code',
        'user_id',
        'guid',
    ];

    protected $casts = [
    ];

    /**
     * Modela notikumi, kas automātiski ģenerē GUID un aprēķina total_price.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Automātiski ģenerē GUID, ja tas nav norādīts
            if (empty($model->guid)) {
                $model->guid = Str::uuid();
            }
        });
    }

}
