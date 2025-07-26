<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

class Department extends Model
{
    use HasFactory, HasUuids;

    /**
     * Masīvs, kas norāda, kuri lauki ir masveidā aizpildāmi.
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'parent_code',
        'contact_person',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'zip',
    ];

    /**
     * Norādām, ka primārā atslēga ir UUID.
     */
    protected $keyType = 'string';
    public $incrementing = false;

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
     * The parent department.
     *
     * @return BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Department::class, 'parent_code', 'code');
    }
}
