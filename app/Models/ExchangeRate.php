<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ExchangeRate extends Model
{
    use HasFactory;

    /**
     * Tabulas nosaukums (ja nepieciešams, piemēram, ja nosaukums neatbilst konvencijai)
     */
    protected $table = 'exchange_rates';
    public $incrementing = false;   // Jo uuid nav autoinkrementējošs
    protected $keyType = 'string';  // Jo uuid ir string, nevis int

    /**
     * Masīvs ar aizpildāmajiem laukiem
     */
    protected $fillable = [
        'key',
        'date',
        'currency_from_url',
        'currency_from',
        'currency_to_url',
        'currency_to',
        'rate',
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
    
    public static function  fillFromHorizonApiData($session_guid){
        $data = HorizonApiData::query()
        ->select([
            't2.entry_key as t2_entry_key',
            't3.entry_key as t3_entry_key',
            't4.entry_value as t4_entry_value',
            't5.entry_value as t5_entry_value',
            't7.entry_value as t7_entry_value',
            't9.entry_value as t9_entry_value',
        ])
        ->from('horizon_api_data as t1')
        ->leftJoin('horizon_api_data as t2', function ($join) {
            $join->on('t2.parent_guid', '=', 't1.guid')
                 ->where('t2.entry_path', 'like', '/collection/row/%');
        })
        ->leftJoin('horizon_api_data as t3', function ($join) {
            $join->on('t3.parent_guid', '=', 't2.guid')
                 ->where('t3.entry_key', '=', 'SVK');
        })
        ->leftJoin('horizon_api_data as t4', function ($join) {
            $join->on('t4.parent_guid', '=', 't3.guid')
                 ->where('t4.entry_key', '=', 'KURSS');
        })
        ->leftJoin('horizon_api_data as t5', function ($join) {
            $join->on('t5.parent_guid', '=', 't3.guid')
                 ->where('t5.entry_key', '=', 'SPEKA_NO');
        })
        ->leftJoin('horizon_api_data as t6', function ($join) {
            $join->on('t6.parent_guid', '=', 't3.guid')
                 ->where('t6.entry_key', '=', 'PK_VALNO');
        })
        ->leftJoin('horizon_api_data as t7', function ($join) {
            $join->on('t7.parent_guid', '=', 't6.guid')
                 ->where('t7.entry_key', '=', 'href');
        })
        ->leftJoin('horizon_api_data as t8', function ($join) {
            $join->on('t8.parent_guid', '=', 't3.guid')
                 ->where('t8.entry_key', '=', 'PK_VALUZ');
        })
        ->leftJoin('horizon_api_data as t9', function ($join) {
            $join->on('t9.parent_guid', '=', 't8.guid')
                 ->where('t9.entry_key', '=', 'href');
        })
        ->where('t1.entry_path', '=', '/collection/row')
        ->where('t1.entry_key', '=', 'row')
        ->where('t1.session_guid', $session_guid)
        ->get();

        foreach ($data as $row) {
            $data = ExchangeRate::firstOrNew(
                [
                    'key' => Carbon::parse($row->t5_entry_value)->format('Y-m-d') . '-' . trim($row->t7_entry_value) . '-' . trim($row->t9_entry_value),
                ],
                [
                    'date' => Carbon::parse($row->t5_entry_value)->format('Y-m-d'),
                    'currency_from_url' => $row->t7_entry_value,
                    'currency_from' => null,
                    'currency_to_url' => $row->t9_entry_value,
                    'currency_to' => null,
                    'rate' => round($row->t4_entry_value, 8),
                ]

            );
            $data->save();
        }
    }

}
