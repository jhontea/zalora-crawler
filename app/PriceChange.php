<?php

namespace App;

use Cache;
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

    public static function boot()
    {
        parent::boot();

        static::saved(function () {
            Cache::forget('list_products');
        });
    }
}
