<?php

namespace App;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use Searchable;

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

    public static function getCachedProducts()
    {
        $listProducts = Cache::rememberForever('list_products', function () {
            return static::with(['priceChanges', 'priceNow'])->get();
        });
        return $listProducts;
    }

    public static function getCachedCategory()
    {
        $listCategories = Cache::rememberForever('list_categories', function () {
            return static::all()->groupBy('category');
        });
        return $listCategories;
    }

    public static function boot()
    {
        parent::boot();

        static::saved(function () {
            Cache::forget('list_products');
            Cache::forget('list_categories');
        });
    }
}
