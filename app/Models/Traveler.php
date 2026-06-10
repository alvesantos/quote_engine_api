<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Traveler extends Model
{
    protected $guarded = [
        'id'
    ];

    protected $casts = [
        'birth_date'     => 'date',
        'addons'         => 'array',
        'addons_allowed' => 'array',
    ];

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }
}
