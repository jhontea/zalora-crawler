<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        ini_set('max_execution_time', 3000);

        $client = new Client();
        $products = [];
        $pageError = [];

        for ($page = 1; $page < 2; $page++) {
            try {
                $url = 'https://www.zalora.co.id/men/produk-baru/?sort=popularity&dir=desc&page='
                .$page.'&gender=men&enable_visual_sort=1&category_id=';
                $request = $client->get($url)->getBody()->getContents();
                $script = preg_match('/"docs":\[{(.*)\]},"facet_counts":/siU', $request, $matchesScript);
                if ($script) {
                    $splitProduk = explode('"meta":', $matchesScript[1]);
                    unset($splitProduk[0]);

                    foreach ($splitProduk as $key => $value) {
                        preg_match('/"brand":"(.*)","name"/siU', $value, $matchesBrand);
                        preg_match('/"link":"(.*)","image":/siU', $value, $matchesLink);
                        preg_match('/"name":"(.*)","special/siU', $value, $matchesName);
                        preg_match('/"price":"(.*)","activated_at"/siU', $value, $matchesPrice);
                        preg_match(
                            '/"special_price":"(.*)","price"/siU',
                            $value,
                            $matchesPriceDiscount
                        );
                        preg_match('/"image":"(.*)","image_gallery"/siU', $value, $matchesImageLink);
                        $products[] = [
                            'url' => "https://www.zalora.co.id/".$matchesLink[1],
                            'name' => $matchesName[1],
                            'brand' => $matchesBrand[1],
                            'price' => (int)str_replace(".", "", $matchesPrice[1]),
                            'priceDiscount' => (int)str_replace(".", "", $matchesPriceDiscount[1])?
                                (int)str_replace(".", "", $matchesPriceDiscount[1]) :
                                (int)str_replace(".", "", $matchesPrice[1]),
                            'imageLink' => $matchesImageLink[1]
                        ];

                        if ($key == count($splitProduk) - 5) {
                            break;
                        }
                    }
                } else {
                    $page--;
                    break;
                }
            } catch (Exception $e) {
                if ($e->getCode() == 404) {
                    //url is gone
                    session()->put('errorURL', 'URL not available');
                } else {
                    if (array_key_exists('Host', $e->getRequest()->getHeaders())) {
                        //check connection
                        session()->put('errorURL', 'Time has running out. Please check the connection');
                    } else {
                        //url must be using http or https
                        session()->put('errorURL', 'Must be a valid URL.');
                    }
                }
                $pageError[] = $page;
            }
            sleep(10);
        }
        //dd($products);
        return view('test.index', compact('products', 'pageError'));
    }
}
