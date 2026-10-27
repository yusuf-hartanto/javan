<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesCountry extends Model
{
    protected $guarded = ['id'];

    public function sales()
    {
        return $this->belongsTo(Sales::class, 'sales_id');
    }

    public function regions()
    {
        return $this->hasMany(SalesRegion::class, 'country_id');
    }
}

