<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class HorizonApiData extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'horizon_api_data';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'guid',
        'parent_guid',
        'entry_number',
        'session_guid',
        'entry_path',
        'entry_key',
        'entry_value',
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
            if (empty($model->guid)) {
                $model->guid = (string) Str::uuid();
            }
        });
    }

    /**
     * Saves data to the database.
     *
     * @param array $data   
     * @param string $full_path
     * @param string $session_guid
     *
     * @return string
     */
    public static function SaveData($data, $parent_guid = null, &$entry_number = 0, string $parent_key = '', string $parent_path = '', string $session_guid = null)
    {
        $current_parent_guid = $parent_guid;
        if (is_null($session_guid)) {
            $session_guid = (string) Str::uuid();
        }
        
        $parent_path .= ($parent_key != '' ? '/' . $parent_key : '');
        $parent_guid = self::SaveEntryData($parent_key, is_array($data) ? null : $data, $current_parent_guid, $entry_number, $parent_path, $session_guid);

        if (is_array($data)) {
            foreach ($data as $key2 => $value2) {
                $entry_number++;
                self::SaveData($value2, $parent_guid, $entry_number, $key2, $parent_path, $session_guid);
            }
        }
        return $session_guid;
    }
    public static function truncateData(){
        HorizonApiData::truncate();
    }

    private static function SaveEntryData($key, $value, $parent_guid, $entry_number,  string $parent_path, string $session_guid)
    {
        $model = [];
        $model['parent_guid'] = $parent_guid ? $parent_guid : null;
        $model['session_guid'] = $session_guid;
        $model['entry_number'] = $entry_number;
        $model['entry_path'] = $parent_path ? $parent_path : '/';
        $model['entry_key'] = $key;
        $model['entry_value'] = $value;
        $result = self::create($model);
        return $result->guid;
    }
}
