<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'url',
        'sku',
        'title',
        'brand',
        'price',
        'price_discount',
        'image_link',
        'discount',
        'is_active',
        'category'
    ];

    public function priceChanges()
    {
        return $this->hasMany(PriceChange::class, 'item_id');
    }

    public function priceNow()
    {
        return $this->hasOne(PriceNow::class, 'item_id');
    }
}
