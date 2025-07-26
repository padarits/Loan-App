<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as SpatiePermission;
use Illuminate\Support\Str;

class Permission extends SpatiePermission
{
    use HasFactory;
    use HasUuids;
    protected $primaryKey = 'uuid';
    public $incrementing = false;   // Jo uuid nav autoinkrementējošs
    protected $keyType = 'string';  // Jo uuid ir string, nevis int
    
    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Pievieno UUID automātisku ģenerēšanu, ja modelis tiek izveidots
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid()->toString();
            }
        });
    }
}
