<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Mpdf\Tag\Ins;

class InsuranceData extends Model
{
    use HasFactory;

    protected $table = 'insurance_data'; // Tabulas nosaukums
    public $incrementing = false;   // Jo uuid nav autoinkrementējošs
    protected $keyType = 'string';  // Jo uuid ir string, nevis int

    protected $fillable = [
        'nosauk',
        'reg_nr',
        'summa_db_pv',
        'href',
        'valuta',
        'insured_amount',
        'insured_currency',
        'balance',
        'company_href',
        'PVN_REGNR',   // Jaunais lauks
        'PK_VALSTS',    // Jaunais lauks
        'VALSTS',
        'insured_company',
        'companyId',
        'insurance_identifier_type',
        'insured_amount_company_name'
    ]; // Masveida aizpildāmas kolonnas

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

        static::saving(function ($model) {
            $insured_amount = 0;
            if (($model->valuta) == ($model->insured_currency)) {
                $insured_amount = $model->insured_amount;
            }
            $model->balance = $insured_amount - $model->summa_db_pv;
        });
    }

    public static function  fillFromHorizonApiData($session_guid)
    {
        $data = HorizonApiData::query()
            ->select([
                't4.entry_value as t4_entry_value',
                't5.entry_value as t5_entry_value',
                't7.entry_value as t7_entry_value',
                't9.entry_value as t9_entry_value',
                't11.entry_value as t11_entry_value',
                't12.entry_value as t12_entry_value',
                't14.entry_value as t14_entry_value',
            ])
            ->from('horizon_api_data as t1')
            ->leftJoin('horizon_api_data as t2', 't2.parent_guid', '=', 't1.guid')
            ->leftJoin('horizon_api_data as t3', function ($join) {
                $join->on('t3.parent_guid', '=', 't2.guid')
                    ->where('t3.entry_key', '=', 'K');
            })
            ->leftJoin('horizon_api_data as t4', function ($join) {
                $join->on('t4.parent_guid', '=', 't3.guid')
                    ->where('t4.entry_key', '=', 'NOSAUK');
            })
            ->leftJoin('horizon_api_data as t5', function ($join) {
                $join->on('t5.parent_guid', '=', 't3.guid')
                    ->where('t5.entry_key', '=', 'REG_NR');
            })
            ->leftJoin('horizon_api_data as t6', function ($join) {
                $join->on('t6.parent_guid', '=', 't3.guid')
                    ->where('t6.entry_key', '=', 'DNV');
            })
            ->leftJoin('horizon_api_data as t7', function ($join) {
                $join->on('t7.parent_guid', '=', 't6.guid')
                    ->where('t7.entry_key', '=', 'SUMMA_DB_PV');
            })
            ->leftJoin('horizon_api_data as t8', function ($join) {
                $join->on('t8.parent_guid', '=', 't6.guid')
                    ->where('t8.entry_key', '=', 'PK_VAL');
            })
            ->leftJoin('horizon_api_data as t9', function ($join) {
                $join->on('t9.parent_guid', '=', 't8.guid')
                    ->where('t9.entry_key', '=', 'href');
            })
            ->leftJoin('horizon_api_data as t10', function ($join) {
                $join->on('t10.parent_guid', '=', 't3.guid')
                    ->where('t10.entry_key', '=', 'PK_KLIENTS');
            })
            ->leftJoin('horizon_api_data as t11', function ($join) {
                $join->on('t11.parent_guid', '=', 't10.guid')
                    ->where('t11.entry_key', '=', 'href');
            })
            ->leftJoin('horizon_api_data as t12', function ($join) {
                $join->on('t12.parent_guid', '=', 't3.guid')
                    ->where('t12.entry_key', '=', 'PVN_REGNR');
            })
            ->leftJoin('horizon_api_data as t13', function ($join) {
                $join->on('t13.parent_guid', '=', 't3.guid')
                    ->where('t13.entry_key', '=', 'PK_VALSTS');
            })
            ->leftJoin('horizon_api_data as t14', function ($join) {
                $join->on('t14.parent_guid', '=', 't13.guid')
                    ->where('t14.entry_key', '=', 'href');
            })
            ->where('t1.entry_path', '/collection/row')
            ->where('t1.entry_key', 'row')
            ->where('t1.session_guid', $session_guid)
            ->get();

        InsuranceData::truncate();
        foreach ($data as $row) {
            $data = new InsuranceData();
            $data->nosauk = $row->t4_entry_value;
            $data->reg_nr = $row->t5_entry_value;
            $data->summa_db_pv = round($row->t7_entry_value, 2);
            $data->href = $row->t9_entry_value;
            $data->company_href = $row->t11_entry_value;
            $data->PVN_REGNR = $row->t12_entry_value;
            $data->PK_VALSTS = $row->t14_entry_value;

            $currencyHref = \App\Models\CurrencyHref::where('href', $data->href)->first();
            if ($currencyHref) {
                $data->valuta = $currencyHref->currency;
            }

            $data->save();
        }
    }
    
    public static function  fillFromInsuranceApiData($session_guid, InsuranceData &$insuranceDataRecord)
    {
        $results = DB::table('horizon_api_data as t1')
            ->leftJoin('horizon_api_data as t2', 't2.parent_guid', '=', 't1.guid')
            ->leftJoin('horizon_api_data as t3', 't3.parent_guid', '=', 't2.guid')
            ->leftJoin('horizon_api_data as t4', function ($join) {
                $join->on('t4.parent_guid', '=', 't3.guid')
                    ->where('t4.entry_key', '=', 'companyId');
            })
            ->leftJoin('horizon_api_data as t5', 't5.parent_guid', '=', 't4.guid')
            ->leftJoin('horizon_api_data as t6', function ($join) {
                $join->on('t6.parent_guid', '=', 't3.guid')
                    ->where('t6.entry_key', '=', 'legalData');
            })
            ->leftJoin('horizon_api_data as t7', function ($join) {
                $join->on('t7.parent_guid', '=', 't6.guid')
                    ->where('t7.entry_key', '=', 'companyName');
            })
            ->where('t1.entry_path', '/results')
            ->where('t4.entry_key', 'companyId')
            ->where('t1.session_guid', $session_guid)
            ->select(
                't4.entry_value as t4_entry_value',
                't7.entry_value as t7_entry_value'
            )
            ->first();

        if ($results && self::checkInsuranceApiData($session_guid, $insuranceDataRecord, $results->t7_entry_value)) {
            $insuranceDataRecord->insured_company = $results->t7_entry_value;
            $insuranceDataRecord->companyId = $results->t4_entry_value;
            return true;
        }
        return false;
    }
    public static function  checkInsuranceApiData($session_guid, InsuranceData &$insuranceDataRecord, $insured_company)
    {
        $reg_num = trim($insuranceDataRecord->reg_nr);
        $free_num = $reg_num; //preg_replace("/./", '', $reg_num);
        $free_num_a = substr($free_num, 0, 2);
        $PVN_REGNR = trim($insuranceDataRecord->PVN_REGNR);
        $free_num2 = $PVN_REGNR; //preg_replace("/./", '', $PVN_REGNR);

        $result = DB::table('public.horizon_api_data as t1')
            ->select(
                't4.entry_value as t4_entry_value',
                't5.entry_value as t5_entry_value',
                't6.entry_value as t6_entry_value'
            )
            ->leftJoin('public.horizon_api_data as t3', 't3.parent_guid', '=', 't1.guid')
            ->leftJoin('public.horizon_api_data as t4', function ($join) {
                $join->on('t4.parent_guid', '=', 't3.guid')
                    ->where('t4.entry_key', '=', 'idTypeCode');
            })
            ->leftJoin('public.horizon_api_data as t5', function ($join) {
                $join->on('t5.parent_guid', '=', 't3.guid')
                    ->where('t5.entry_key', '=', 'idValue');
            })
            ->leftJoin('public.horizon_api_data as t6', function ($join) {
                $join->on('t6.parent_guid', '=', 't3.guid')
                    ->where('t6.entry_key', '=', 'isNationalIdentifier');
            })
            ->where('t1.entry_path', 'LIKE', '/results/%/company/companyIdentifiers')
            ->where('t1.session_guid', $session_guid)
            ->where(function ($query) use ($free_num, $free_num_a, $free_num2) {
                $query->where('t5.entry_value', 'LIKE', "$free_num")
                    ->orWhere('t5.entry_value', 'LIKE', "$free_num_a")
                    ->orWhere('t5.entry_value', 'LIKE', "$free_num2");
            })
            ->first();

        if ($result) {
            return true;
        } else {
            $result = DB::table('public.horizon_api_data as t1')
                ->select(
                    't4.entry_value as t4_entry_value',
                    't5.entry_value as t5_entry_value',
                    't6.entry_value as t6_entry_value'
                )
                ->leftJoin('public.horizon_api_data as t3', 't3.parent_guid', '=', 't1.guid')
                ->leftJoin('public.horizon_api_data as t4', function ($join) {
                    $join->on('t4.parent_guid', '=', 't3.guid')
                        ->where('t4.entry_key', '=', 'idTypeCode');
                })
                ->leftJoin('public.horizon_api_data as t5', function ($join) {
                    $join->on('t5.parent_guid', '=', 't3.guid')
                        ->where('t5.entry_key', '=', 'idValue');
                })
                ->leftJoin('public.horizon_api_data as t6', function ($join) {
                    $join->on('t6.parent_guid', '=', 't3.guid')
                        ->where('t6.entry_key', '=', 'isNationalIdentifier');
                })
                ->where('t1.entry_path', 'LIKE', '/results/%/company/companyIdentifiers')
                ->where('t1.session_guid', $session_guid)
                ->get();
            $counter = 0;
            foreach ($result as $row) {
                if ($counter == 0) {
                    $insured_company = '?' . $insured_company . ', ' . $row->t5_entry_value;
                } else {
                    $insured_company = $insured_company . ', ' . $row->t5_entry_value;
                }
                $counter++;
            }

            $insuranceDataRecord->insured_company = $insured_company;
            $insuranceDataRecord->save();
        }
        return false;
    }

    public static function  updateAmount($session_guid)
    {
        $results = DB::table('horizon_api_data as t1')
            ->leftJoin('horizon_api_data as t4', function ($join) {
                $join->on('t4.parent_guid', '=', 't1.parent_guid')
                    ->where('t4.entry_key', '=', 'totalAmount');
            })
            ->leftJoin('horizon_api_data as t5', function ($join) {
                $join->on('t5.parent_guid', '=', 't1.parent_guid')
                    ->where('t5.entry_key', '=', 'totalAmountCurrencyCode');
            })
            ->leftJoin('horizon_api_data as t6', function ($join) {
                $join->on('t6.parent_guid', '=', 't1.parent_guid')
                    ->where('t6.entry_key', '=', 'companyName');
            })
            ->where('t1.entry_path', 'LIKE', '/%/companyId')
            ->where('t1.entry_key', 'companyId')
            ->whereRaw('CAST(t4.entry_value AS DECIMAL) > 0')
            ->select(
                't1.entry_value',
                't4.entry_value as t4_entry_value',
                't5.entry_value as t5_entry_value',
                't6.entry_value as t6_entry_value'
            )
            ->where('t1.session_guid', $session_guid)
            ->get();

        //, InsuranceData &$insuranceDataRecord
        if ($results) {
            foreach ($results as $row) {
                $insuranceDataRecord = InsuranceData::where('companyId', $row->entry_value)
                    ->where('valuta', $row->t5_entry_value)
                    ->first();
                if ($insuranceDataRecord) {
                    $insuranceDataRecord->insured_amount = $row->t4_entry_value;
                    $insuranceDataRecord->insured_currency = $row->t5_entry_value;
                    $insuranceDataRecord->insured_amount_company_name = $row->t6_entry_value;
                    $insuranceDataRecord->save();
                }
            }
            return true;
        }
        return false;
    }
}
