<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Product;
use App\Traits\CrawlerTrait;

class HomeController extends Controller
{
    use CrawlerTrait;

    public function index()
    {
        return view('home');
    }

    public function crawl()
    {
        $products = Product::where('url', request()->get('url'));

        if ($products->exists()) {
            $data = $products->first();
            $data['exist'] = true;
        } else {
            //use crawler trait to get the data
            $data = $this->crawlingData(request()->get('url'));
            if ($data) {
                $data['exist'] = false;
            }
        }


        return view('home', compact('data'));
    }

    public function create()
    {
        $data = request()->all();
        Product::create($data);

        return redirect('/');
    }
}
