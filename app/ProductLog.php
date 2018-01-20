<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductLog extends Model
{
    protected $fillable = [
        'product_id',
        'causer',
        'cause_id',
        'quantity',
        'remarks',
    ];

    public function log()
    {
        return $this->morphTo();
    }

}
