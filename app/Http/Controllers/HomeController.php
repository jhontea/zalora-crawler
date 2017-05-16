<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Product;
use App\Traits\CrawlerTrait;

class HomeController extends Controller
{
    use CrawlerTrait;

    public function __construct(Product $products)
    {
        $this->products = $products;
    }

    public function index()
    {
        $products = $this->products->all();
        return view('home', compact('products'));
    }

    public function create()
    {
        $data = request()->all();
        $this->products->create($data);

        return redirect('/');
    }

    public function crawl()
    {
        $products = $this->products->where('url', request()->get('url'));

        //check exist product
        if ($products->exists()) {
            $data = $products->first();
            $data['exist'] = true;
        } else {
            //use crawler trait to get the data
            $data = $this->crawlingData(request()->get('url'));
            //check error data
            if ($data) {
                $data['exist'] = false;
            }
        }


        return view('home', ['data' => $data, 'products' => $this->products->all()]);
    }
}
