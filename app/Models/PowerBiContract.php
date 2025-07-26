<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class PowerBiContract extends Model
{
    use HasFactory;
    use HasUuids;
    protected $primaryKey = 'id';
    public $incrementing = false;   // Jo uuid nav autoinkrementējošs
    protected $keyType = 'string';  // Jo uuid ir string, nevis int
    protected $fillable = [
        'pircjs', // pircējs
        'ligums', // līgums
        'noslegsanas_datums', // noslēgšanas datums
        'm3_akt_uzdots', // m3 akt. uzdots
        'm3_nom_uzdots', // m3 nom. uzdots
        'm3_akt_piegadats', // m3 akt. piegādāts
        'm3_nom_piegadats', // m3 nom. piegādāts
        'm3_akt_osta', // m3 akt. osta
        'm3_nom_osta', // m3 nom. osta
        'm3_akt_rupnica', // m3 akt. rūpnīcā
        'm3_nom_rupnica', // m3 nom. rūpnīcā
        'cena_par_nom', // cena par nom.
        'cena_par_akt', // cena par akt.
        'valuta', // valūta
        'cena_fraht', // cena fraht
        'valuta_fraht', // valūta fraht
        'termins', // termiņš
        'piegades_nosacijumi', // piegādes nosacījumi
        'osta', // osta
    ];

    // Automātiska GUID ģenerēšana
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = Str::uuid()->toString();
            }
        });
    }
}
