<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Identifier extends Model
{
    use HasFactory;
    public $incrementing = false;   // Jo uuid nav autoinkrementējošs
    protected $keyType = 'string';  // Jo uuid ir string, nevis int
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'country_code',
        'identifier_type',
        'full_wording',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }  
    
    protected static function getIdentifierTypeFor($country_code) {
        $Identifier =  Identifier::where('country_code', $country_code)->first();
        if($Identifier) {
            return $Identifier->identifier_type;
        }   else {
            return '';
        }
    }
}
