<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disposals extends Model
{
    protected $table = 'disposals';

    protected $fillable = [
        'town_id',
        'town_street_id',
        'waste_types',
        'date',
        'type'
    ];

    protected $casts = [
        'streets' => 'array',
        'waste_types' => 'array',
        'notes' => 'array',
    ];
}
