<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search()
    {
        $data = request()->get('searchWord');
        $products = Product::search($data)->paginate(12);

        return view('search.index', compact('products', 'data'));
    }
}
