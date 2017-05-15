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
        //use crawler trait to get the data
        $data = $this->crawlingData(request()->get('url'));
        //check if the data already exist
        $data['exist'] = Product::where('url', request()->get('url'))->exists();

        return view('home', compact('data'));
    }

    public function create()
    {
        $data = request()->all();
        Product::create($data);

        return redirect('/');
    }
}
