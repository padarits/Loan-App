<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PowerBiAtskaite2 extends Model
{
    use HasFactory;

    protected $table = 'public.power_bi_view_1'; // Norāda tabulas nosaukumu (ja tas atšķiras no modeļa nosaukuma daudzskaitlī)

    protected $primaryKey = 'c_id'; // Primārās atslēgas kolonna
    public $incrementing = false; // UUID nav auto-increment
    protected $keyType = 'integer'; // UUID ir string, nevis integer
    
    /**
     * Lietojamais datubāzes savienojums.
     *
     * @var string
     */
    protected $connection = 'pgsql_secondary';

    protected $fillable = [
    ];


}

