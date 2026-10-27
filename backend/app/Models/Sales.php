<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $guarded = ['id'];

    public function countries()
    {
        return $this->hasMany(SalesCountry::class, 'sales_id');
    }
}

