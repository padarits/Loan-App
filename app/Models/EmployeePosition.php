<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

class EmployeePosition extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'employee_positions';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'position_name',
        'position_for_department_id',
        'is_head',
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
     * Attiecības ar tabulu Department.
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'position_for_department_id');
    }}
