<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class EmployeeForPosition extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'employee_for_position';
    protected $keyType = 'string';
    public $incrementing = false;

    // Aizpildāmie lauki
    protected $fillable = [
        'id',
        'employee_id',
        'position_id',
        'department_id',
        'is_head'
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
     * Attiecības ar tabulu User.
     */
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    /**
     * Attiecības ar tabulu Department.
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    
    /**
     * Attiecības ar tabulu EmployeePosition.
     */
    public function position()
    {
        return $this->belongsTo(EmployeePosition::class, 'position_id');
    }
}
