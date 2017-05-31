<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PriceChange extends Model
{
    protected $table = 'price_changes';

    protected $fillable = [
        'item_id',
        'price',
        'price_discount',
        'discount'
    ];

    public function product()
    {
        return $this->belongsTo(App\Product::class, 'item_id');
    }
}
