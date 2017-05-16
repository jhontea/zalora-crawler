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
}