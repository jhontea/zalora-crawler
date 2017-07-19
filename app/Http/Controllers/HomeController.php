<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\PriceNow;
use App\Product;
use App\Traits\CrawlerTrait;
use Cache;
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
        return view('home');
    }

    public function show($sku)
    {
        $product = $this->products->getCachedProducts()->where('sku', $sku)->first();

        ($product->priceChanges->count() > 5)?
        ($skip = $product->priceChanges->count() - 5) :
        $skip = 0;

        $priceChanges = $product->priceChanges()->skip($skip)->take(5)->get();

        return view('detail', compact('product', 'priceChanges'));
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

    public function category($category = 'all')
    {
        if ($category == 'all') {
            $products = $this->products->paginate(12);
            /*$currentPage = request()->get('page') ? (int)request()->get('page') : 1;
            $products = Cache::remember('products-' . $currentPage, 10, function (){
                return Product::with(['priceNow'])->paginate(12);
            });*/
        } else {
            $products = $this->products->where('category', $category)->paginate(12);
            /*$currentPage = request()->get('page') ? (int)request()->get('page') : 1;
            $products = Cache::remember('products-category:' . $currentPage, 10, function () use ($category){
                return Product::with(['priceNow'])->where('category', $category)->paginate(12);
            });*/
        }
        return view('index', compact('products', 'category'));
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
