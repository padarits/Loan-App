<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class ExpenseClassifier extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'expense_classifiers';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'parent_id',
        'code',
        'name',
        'name_for_search',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'string',
        'parent_id' => 'string',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
            // Normalize fields
            $model->normalizeFields();
        });

        static::updating(function ($model) {
            // Normalize fields
            $model->normalizeFields();
        });
    }   
    
    
    /**
     * Normalize fields by removing diacritics.
     */
    private function normalizeFields()
    {
        $this->name_for_search = $this->removeDiacritics($this->name_for_search ?: $this->name);
    }
    
    /**
     * Replace diacritics with plain characters.
     */
    public function removeDiacritics(string $text): string
    {
        $transliterationMap = [
            'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g',
            'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
            'š' => 's', 'ū' => 'u', 'ž' => 'z',
            'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G',
            'Ī' => 'I', 'Ķ' => 'K', 'Ļ' => 'L', 'Ņ' => 'N',
            'Š' => 'S', 'Ū' => 'U', 'Ž' => 'Z',
        ];

        return strtr($text, $transliterationMap);
    }
}
