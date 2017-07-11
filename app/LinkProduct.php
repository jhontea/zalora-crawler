<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LinkProduct extends Model
{
    protected $table = 'link_products';

    protected $fillable = [
        'url',
    ];
}
