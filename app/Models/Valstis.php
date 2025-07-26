<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
class Valstis extends Model
{
    use HasFactory;
    public $incrementing = false;   // Jo uuid nav autoinkrementējošs
    protected $keyType = 'string';  // Jo uuid ir string, nevis int
    // Norādām, kuras kolonnas var masveidā aizpildīt
    protected $fillable = [
        'valsts_href',
        'valsts',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Automātiski ģenerē GUID, ja tas nav norādīts
            if (empty($model->id)) {
                $model->id = Str::uuid();
            }
        });
    }

    public static function  fillFromHorizonApiData($session_guid){
        $data = DB::table('horizon_api_data as t1')
            ->leftJoin('horizon_api_data as t3', function ($join) {
                $join->on('t3.parent_guid', '=', 't1.guid')
                     ->where('t3.entry_key', '=', 'KODS');
            })
            ->leftJoin('horizon_api_data as t4', function ($join) {
                $join->on('t4.parent_guid', '=', 't1.guid')
                     ->where('t4.entry_key', '=', 'PK_VALSTS');
            })
            ->leftJoin('horizon_api_data as t5', function ($join) {
                $join->on('t5.parent_guid', '=', 't4.guid')
                     ->where('t5.entry_key', '=', 'href');
            })
            ->leftJoin('horizon_api_data as t6', function ($join) {
                $join->on('t6.parent_guid', '=', 't1.guid')
                     ->where('t6.entry_key', '=', 'NOSAUK');
            })
            ->where('t1.entry_path', '=', '/entity')
            ->where('t1.entry_key', '=', 'entity')
            ->select([
                't3.entry_value as t3_entry_value',
                't5.entry_value as t5_entry_value',
                't6.entry_value as t6_entry_value',
            ])
            ->where('t1.session_guid', $session_guid)
            ->get();
        
        foreach ($data as $row) {
            $data = Valstis::firstOrNew(
                [
                    'valsts_href' => trim($row->t5_entry_value),
                ],
                [
                    'valsts' => $row->t3_entry_value,
                ]
            );
            $data->save();
        }
    }
}
