<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PriceNow extends Model
{
    protected $table = 'price_nows';

    protected $fillable = [
        'item_id',
        'price',
        'price_discount',
        'status'
    ];

    public function product()
    {
        return $this->belongsTo(App\Product::class, 'item_id');
    }
}
