<?php

namespace App\Http\Controllers;

use App\Product;
use Exception;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search()
    {
        request()->get('query')?
            $data = request()->get('query') :
            $data = request()->get('searchWord');
        try {
            config(['scout.driver' => 'algolia']);
            $products = Product::search($data)->paginate(12);
        } catch (Exception $e) {
            config(['scout.driver' => 'mysql']);
            $products = Product::search($data)->paginate(12);
        }

        return view('search.index', compact('products', 'data'));
    }
}
