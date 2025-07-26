<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ProcessingResult extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'uuid',
        'processing_status',
        'processing_message',
    ];

    //protected $casts = [
    //    'processing_message' => 'array', // Automatically cast JSON column to array
    //];
}
