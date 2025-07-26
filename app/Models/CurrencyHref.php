<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CurrencyHref extends Model
{
    use HasFactory;

    /**
     * Tabulas nosaukums
     */
    protected $table = 'currency_href';
    public $incrementing = false;   // Jo uuid nav autoinkrementējošs
    protected $keyType = 'string';  // Jo uuid ir string, nevis int
    /**
     * Masīvs ar aizpildāmajiem laukiem
     */
    protected $fillable = [
        'currency',
        'href',
    ];

    /**
     * Boot the model.
     *
     * Automatically generate a GUID when creating a new record.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }  

    /**
     * Piepilda valūtu ar to href.
     * 
     * @param string $session_guid Sesijas GUID
     * 
     * @return void
     */
    public static function  fillFromHorizonApiData($session_guid){
        $data = HorizonApiData::query()
        ->select([
            't1.entry_value',
            't4.entry_value as t4_entry_value',
        ])
        ->from('horizon_api_data as t1')
        ->leftJoin('horizon_api_data as t2', function ($join) {
            $join->on('t2.guid', '=', 't1.parent_guid')
                 ->where('t2.entry_key', '=', 'PK_VAL');
        })
        ->leftJoin('horizon_api_data as t3', 't3.guid', '=', 't2.parent_guid')
        ->leftJoin('horizon_api_data as t4', function ($join) {
            $join->on('t4.parent_guid', '=', 't3.parent_guid')
                 ->where('t4.entry_key', '=', 'title');
        })
        ->where('t1.entry_path', '/entity/PK_VAL/href')
        ->where('t1.session_guid', $session_guid)
        ->get();
        
        foreach ($data as $row) {
            $data = CurrencyHref::firstOrNew(
                [
                    'href' => trim($row->entry_value),
                ],
                [
                    'currency' => $row->t4_entry_value,
                ]
            );
            $data->save();
        }
    }
}
