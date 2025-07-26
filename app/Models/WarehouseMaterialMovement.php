<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Casts\QuantityFormatCast;
use App\Casts\MoneyFormatCast;
use App\Casts\StrToLowerFormatCast;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class WarehouseMaterialMovement extends Model
{
    use HasFactory;
    use HasUuids;
    protected $primaryKey = 'guid';
    public $incrementing = false;   // Jo uuid nav autoinkrementējošs
    protected $keyType = 'string';  // Jo uuid ir string, nevis int
    // 'type' => 'required|in:010_none,020_application,030_received,040_dispensed,050_written_off,060_added_to_inventory,070_removed_from_inventory,080_in_transit,090_canceled,100_balance',
    public const TypeNone = '010_none';
    public const TypeApplication = '020_application';
    public const TypeReceived = '030_received';
    public const TypeDispensed = '040_dispensed';
    public const TypeSent = '045_sent';
    public const TypeWrittenOff = '050_written_off';
    public const TypeAddedToInventory = '060_added_to_inventory';
    public const TypeRemovedFromInventory = '070_removed_from_inventory';
    public const TypeInTransit = '080_in_transit';
    public const TypeCanceled = '090_canceled';
    public const TypeBalance = '100_balance';

    public const WarehouseTypeNone = 'none';
    public static $warehouseFilter;

    protected $fillable = [
        'guid',
        'parent_guid',
        'article',  // Jaunais lauks
        'article_id',  // Jaunais lauks
        'date',
        'code',
        'status', // Pievienots status lauks
        'order_number',
        'name',
        'name_2',
        'material_grade',
        'unit',
        'quantity',
        'price_per_unit',
        'total_price',
        'supplier',
        'recipient',
        'due_date',
        'invoice_number',
        'supplier_company',
        'warehouse_date',
        'issued',
        'code_2',
        'type',
        'recipient_guid',
        'warehouse_code',
        'external_int_id',
        'prev_warehouse_code',
        'invoice_date'
        //'created_by',
        //'updated_by',
        //'internal_warehouse_sum',
        //'delta_quantity'
    ];

    protected $casts = [
        'issued' => 'boolean',
        'date' => 'date',
        'due_date' => 'date',
        'warehouse_date' => 'date',
        'invoice_date' => 'date',
        'loaded_at' => 'datetime',
        'quantity' => QuantityFormatCast::class,
        'price_per_unit' => MoneyFormatCast::class,
        'total_price' => MoneyFormatCast::class,
        'delta_quantity' => QuantityFormatCast::class,
        'unit' => StrToLowerFormatCast::class,
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
            if (Auth::check()) {
                $model->created_by = Auth::id();
                $model->updated_by = Auth::id();
            }
            $model->rec_type = $model->type; 
            if (!$model->prev_warehouse_code){
                $model->prev_warehouse_code = $model->warehouse_code;
            }
        });

        static::saving(function ($model) {
            if(is_null($model->guid)){
                $model->guid = Str::uuid();
                if (Auth::check()) {
                    $model->created_by = Auth::id();
                }
            }
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            } else {
                $model->updated_by = null;
            }
            $model->rec_type = $model->type;
            if (!$model->prev_warehouse_code){
                $model->prev_warehouse_code = $model->warehouse_code;
            }

            // Palaidam kodu transakcijas ietvaros, lai saglabātu modeli un aprēķinus
            return DB::transaction(function () use ($model) {
                $newArticle_id = $model->article_id;
                $oldArticle_id = $model->getOriginal('article_id');
                $newWarehouse_code = $model->warehouse_code;
                $oldWarehouse_code = $model->getOriginal('warehouse_code');
                $newUnit = $model->unit;
                $oldUnit = $model->getOriginal('unit');

                // Aprēķinām vērtības pirms saglabāšanas
                self::setTotalFor($model);
                self::setInternalWarehouseSum($model);
    
                // Saglabājam pašu modeli ar atjauninātajām vērtībām transakcijā
                $model->saveQuietly();
                self::setRelatedEntriesQuantity($model->parent_guid);
                self::entryCalcFields($model);
                self::setBalanceQuantity($newArticle_id, $oldArticle_id, $newWarehouse_code, $oldWarehouse_code, $newUnit, $oldUnit);
                $model->saveQuietly();
            });
        });

        static::deleting(function ($model) {
            // Palaidam kodu transakcijas ietvaros, lai dzēstu modeli un veiktu nepieciešamos aprēķinus
            return DB::transaction(function () use ($model) {   
                $parent_guid = $model->parent_guid;
                $newArticle_id = $model->article_id;
                $oldArticle_id = $model->getOriginal('article_id');
                $newWarehouse_code = $model->warehouse_code;
                $oldWarehouse_code = $model->getOriginal('warehouse_code');
                $newUnit = $model->unit;
                $oldUnit = $model->getOriginal('unit');
                // Dzēšam pašu modeli
                $model->deleteQuietly();
                self::setRelatedEntriesQuantity($parent_guid);
                self::setBalanceQuantity($newArticle_id, $oldArticle_id, $newWarehouse_code, $oldWarehouse_code, $newUnit, $oldUnit);
            });
        });
    }
    
    private static function entryCalcFields(WarehouseMaterialMovement $entry){
        $entry->delta_quantity = $entry->quantity - $entry->related_entries_quantity;
        switch ($entry->type) {
            case self::TypeApplication:
                if ($entry->delta_quantity < 0) {
                    $entry->delta_quantity = 0;
                } 
                break;
            case self::TypeReceived:
                break;
            case self::TypeDispensed:
                break;
            default:
                break;
        }
    }

    private static function setBalanceQuantity($newArticle_id, $oldArticle_id, $newWarehouse_code, $oldWarehouse_code, $newUnit, $oldUnit)
    {
        self::setBalanceQuantityFor($newArticle_id, $newWarehouse_code, $newUnit);
        if ($oldArticle_id !== $newArticle_id or $oldWarehouse_code !== $newWarehouse_code or $oldUnit !== $newUnit){
            self::setBalanceQuantityFor($oldArticle_id, $oldWarehouse_code, $oldUnit);
        }
    }

    private static function setBalanceQuantityFor($article_id, $warehouse_code, $unit)
    {
        if (!$warehouse_code){
            $warehouse_code = self::WarehouseTypeNone;
        }

        if ($article_id) {
            $latestRecord = WarehouseMaterialMovement::where('type', '!=', '100_bilance')
                ->where('article_id', $article_id)
                ->where('unit', $unit)
                ->orderBy('date', 'desc')
                ->first();

            $balance = WarehouseMaterialMovement::firstOrCreate(
                ['article_id' => $article_id, 'type' => self::TypeBalance, 'warehouse_code' => $warehouse_code, 'unit' => $unit], // Attributes to search for
                [
                    //'article_id' => $article_id,
                    //'type' => self::TypeBalance,
                    //'warehouse_code' => $warehouse_code,
                    //'unit' => $unit,
                    'article' => $latestRecord->article,
                    'date' => $latestRecord->date, // Carbon::now()->toDateString(),
                    'code' => 'none',
                    'order_number' => 'none',
                    'name' => $latestRecord->name,
                    'name_2' => $latestRecord->name_2,
                    'material_grade' => $latestRecord->material_grade,
                    'quantity' => 0,
                    'price_per_unit' => 0,
                    'supplier' => 'none',
                ] // Attributes to set if creating
            );
            $balance->warehouse_code = $warehouse_code;
            $balance->article_id = $article_id;
            $balance->article = $latestRecord->article;
            $balance->date = $latestRecord->date;
            $balance->name = $latestRecord->name;
            $balance->name_2= $latestRecord->name_2;
            $balance->material_grade= $latestRecord->material_grade;
            $balance->unit = $unit;
            $balance->quantity = WarehouseMaterialMovement::where('article_id', $article_id)
                                                            ->where('unit', $unit)
                                                            ->where('warehouse_code', $warehouse_code)
                                                            ->sum('internal_warehouse_sum');
            $balance->saveQuietly();
        }
    }

    private static function setRelatedEntriesQuantity($parent_guid)
    {
        if ($parent_guid) {
            $parent = WarehouseMaterialMovement::find($parent_guid);
            if($parent) {
                $parent->related_entries_quantity = $parent->children->sum('quantity');
                self::entryCalcFields($parent);
                $parent->saveQuietly();
            }
        }
    }

    public static function setTotalFor($model)
    {
        $quantity = 0;
        $price_per_unit = 0;

        // Atjaunina total_price, ja pieejami price_per_unit un quantity
        if (isset($model->price_per_unit) && isset($model->quantity)) {
            $quantity = $model->quantity;
            $price_per_unit = $model->price_per_unit;
        }
        $model->total_price = self::getTotalFor($quantity, $price_per_unit);
    } 

    public static function getTotalFor($quantity, $price_per_unit)
    {
        return round($quantity * $price_per_unit, 6);
    }   

    public static function setWarehouseFilter($warehouse_code){
        //parbaudīt vai ir tiesības
        self::$warehouseFilter = $warehouse_code;
    }

    public static function setWhereForWarehouse(&$query){
        if (self::$warehouseFilter and self::$warehouseFilter != self::WarehouseTypeNone){
            $query->where('warehouse_code', '=', self::$warehouseFilter);
        }
    }
    
    public static function setWhereForBalance(&$query){
        $query->where('type', '=', WarehouseMaterialMovement::TypeBalance);
              //->orWhere('type', '=', WarehouseMaterialMovement::TypeInTransit);
    }

    public static function setWhereForApplicationForApi(&$query){
        $query->where('type', '=', WarehouseMaterialMovement::TypeApplication);
              //->orWhere('type', '=', WarehouseMaterialMovement::TypeInTransit);
    }
    
    public static function setWhereForApplication(&$query){
        $query->where('type', '=', WarehouseMaterialMovement::TypeApplication)
              ->orWhere('type', '=', WarehouseMaterialMovement::TypeInTransit);
    }
    
    public static function setWhereForStockReceipt(&$query){
        $query->where('type', '=', WarehouseMaterialMovement::TypeReceived)
              ->orWhere('type', '=', WarehouseMaterialMovement::TypeAddedToInventory);
    }

    public static function setWhereForStockDispatch(&$query){
        $query->where('type', '=', WarehouseMaterialMovement::TypeDispensed)
              ->orWhere('type', '=', WarehouseMaterialMovement::TypeRemovedFromInventory)
              ->orWhere('type', '=', WarehouseMaterialMovement::TypeWrittenOff)
              ->orWhere('type', '=', WarehouseMaterialMovement::TypeCanceled);
    }
    /**
     * Funkcija, kas maina `internal_warehouse_sum` zīmi atkarībā no `type`
     */
    private static function setInternalWarehouseSum($model)
    {
        $positiveTypes = [
            //'020_application',
            self::TypeReceived,
            self::TypeAddedToInventory
        ];

        $negativeTypes = [
            self::TypeDispensed,
            self::TypeWrittenOff,
            self::TypeRemovedFromInventory,
            self::TypeSent
        ];

        // Ja `type` ir pozitīvs, padariet `internal_warehouse_sum` pozitīvu
        if (in_array($model->type, $positiveTypes)) {
            $model->internal_warehouse_sum = abs($model->quantity);
        }
        // Ja `type` ir negatīvs, padariet `internal_warehouse_sum` negatīvu
        elseif (in_array($model->type, $negativeTypes)) {
            $model->internal_warehouse_sum = -abs($model->quantity);
        }
        // Citādi, ja `type` ir '010_none', '080_in_transit', vai '090_canceled', atstājiet kā nulli
        else {
            $model->internal_warehouse_sum = 0;
        }
    }

    // Attiecība ar WarehouseMaterial
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_code', 'warehouse_code');
    }

    // Attiecība ar vecāko WarehouseMaterialMovement
    public function recipientUser()
    {
        return $this->belongsTo(User::class, 'recipient_guid', 'id');
    }

    // Attiecība ar vecāko WarehouseMaterialMovement
    public function parent()
    {
        return $this->belongsTo(WarehouseMaterialMovement::class, 'parent_guid', 'guid');
    }

    // Attiecība ar bērna WarehouseMaterialMovement ierakstiem
    public function children()
    {
        return $this->hasMany(WarehouseMaterialMovement::class, 'parent_guid', 'guid');
    }
    
    /**
     * Izveido klonu pašreizējam ierakstam, iestata jaunu `guid` un iestata oriģinālā ieraksta `guid` kā `parent_guid`.
     *
     * @return WarehouseMaterialMovement
     */
    public function cloneWithThisGuidAsParent($type = self::TypeNone)
    {
        // Izveido jaunu WarehouseMaterialMovement instance ar oriģinālā ieraksta datiem
        $clone = $this->replicate();

        $clone->guid = null;         
        // Iestata jaunu `guid` un piešķir oriģinālā ieraksta `guid` kā `parent_guid`
        $clone->parent_guid = $this->guid;
        $clone->type = $type;
        
        // Saglabā klonēto ierakstu datubāzē
        //$clone->save();

        return $clone;
    }
}

