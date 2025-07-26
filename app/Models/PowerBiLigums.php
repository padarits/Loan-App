<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PowerBiLigums extends Model
{
    use HasFactory;
    use HasUuids;
    protected $primaryKey = 'guid';
    public $incrementing = false;   // Jo uuid nav autoinkrementējošs
    protected $keyType = 'string';  // Jo uuid ir string, nevis int

    /**
     * Tabulas nosaukums.
     *
     * @var string
     */
    protected $table = 'powerbi_ligumi';

    /**
     * Aizpildāmie lauki.
     *
     * @var array
     */
    protected $fillable = [
        'pircejs',
        'ligums',
        'noslegsanas_datums',
        'm3akt_uzdots',
        'm3akt_piegadats',
        'cena_par_m3',
        'valuta',
        'izpildes_termins',
        'apmaksas_dienas',
        'janvaris',
        'februaris',
        'marts',
        'aprilis',
        'maijs',
        'junijs',
        'julijs',
        'augusts',
        'septembris',
        'oktobris',
        'novembris',
        'decembris',
        'n_janvaris',
        'n_februaris',
        'n_marts',
        'n_aprilis',
        'n_maijs',
        'n_junijs',
        'Atlikums',
        'uzdots',
        'piegadats',
        'tips',
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
            $atlikums = round($model->uzdots - $model->piegadats, 0);
            $model->Atlikums = $atlikums;
            $summa = round($atlikums * $model->cena_par_m3, 0);
            $datums = Carbon::parse($model->izpildes_termins)->addDays($model->apmaksas_dienas);
            $year = $datums->year;
            if ($year === Carbon::now()->year) {
                switch ($datums->month) {
                    case 1:
                        $model->janvaris = $summa;
                        break;
                    case 2:
                        $model->februaris = $summa;
                        break;
                    case 3:
                        $model->marts = $summa;
                        break;
                    case 4:
                        $model->aprilis = $summa;
                        break;
                    case 5:
                        $model->maijs = $summa;
                        break;
                    case 6:
                        $model->junijs = $summa;
                        break;
                    case 7:
                        $model->julijs = $summa;
                        break;
                    case 8:
                        $model->augusts = $summa;
                        break;
                    case 9:
                        $model->septembris = $summa;
                        break;
                    case 10:
                        $model->oktobris = $summa;
                        break;
                    case 11:
                        $model->novembris = $summa;
                        break;
                    case 12:
                        $model->decembris = $summa;
                        break;
                }
            } else if ($year === Carbon::now()->year + 1) {
                switch ($datums->month) {
                    case 1:
                        $model->n_janvaris = $summa;
                        break;
                    case 2:
                        $model->n_februaris = $summa;
                        break;
                    case 3:
                        $model->n_marts = $summa;
                        break;
                    case 4:
                        $model->n_aprilis = $summa;
                        break;
                    case 5:
                        $model->n_maijs = $summa;
                        break;
                    case 6:
                        $model->n_junijs = $summa;
                        break;
                }
            }
        });
    }
}
