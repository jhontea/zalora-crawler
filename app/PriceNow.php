<?php

namespace App;

use Cache;
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

    public static function boot()
    {
        parent::boot();

        static::saved(function () {
            Cache::forget('list_products');
        });

        static::updated(function () {
            Cache::forget('list_products');
        });
    }
}
