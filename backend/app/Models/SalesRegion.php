<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesRegion extends Model
{
    protected $guarded = ['id'];

    public function country()
    {
        return $this->belongsTo(SalesCountry::class, 'country_id');
    }
}

