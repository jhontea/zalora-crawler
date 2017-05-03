<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Traits\CrawlerTrait;

class HomeController extends Controller
{
    use CrawlerTrait;

    public function index()
    {
        return view('welcome');
    }

    public function crawl()
    {
        $data = $this->crawlingData(request()->get('url'));
        return view('welcome', compact('data'));
    }
}
