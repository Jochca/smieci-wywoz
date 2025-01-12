<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TownStreet extends Model
{
    protected $table = 'town_streets';

    protected $fillable = [
        'name',
        'town_id'
    ];

    public function town()
    {
        return $this->belongsTo(Town::class);
    }
}
