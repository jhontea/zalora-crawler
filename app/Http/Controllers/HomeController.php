<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\PriceNow;
use App\Product;
use App\Traits\CrawlerTrait;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    use CrawlerTrait;

    public function __construct(Product $products)
    {
        $this->products = $products;
    }

    public function index()
    {
        //$products = $this->products->getCachedProducts();
        //$products = $this->products->paginate(12);

        return view('home', compact('products'));
    }

    public function category()
    {
        $products = $this->products->paginate(12);
        return view('index', compact('products'));
    }

    public function show($sku)
    {
        $product = $this->products->where('sku', $sku)->first();
        return view('detail', compact('product'));
    }

    public function create()
    {
        $data = request()->all();
        $product = $this->products->create($data);

        PriceNow::create([
            'item_id' => $product->id,
            'price' => $data['price'],
            'price_discount' => $data['price_discount'],
            'status' => 'equal'
        ]);

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
