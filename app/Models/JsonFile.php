<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

class JsonFile extends Model
{
    use HasFactory, HasUuids;

    // Aizpildāmie lauki
    protected $fillable = ['data'];

    // Norāda, ka 'id' ir UUID
    public $incrementing = false;
    protected $keyType = 'string';

    // Automātiski konvertē 'data' lauku uz JSON
    protected $casts = [
        'data' => 'json',
    ];

    // Modela "creating" notikums, kas ģenerē UUID, ja tas nav norādīts
    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = Str::uuid()->toString();
            }
        });
    }
}
