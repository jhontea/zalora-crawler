<?php

namespace App\Console\Commands;

use App\LinkProduct;
use App\Mail\SendNotification;
use App\Product;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Mail;

class ProductNewCommand extends Command
{
    protected $newCount = 0;
    protected $existCount = 0;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:new';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the new product daily';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client = new Client();
        $pageError = [];

        for ($page = 1; $page < 999; $page++) {
            try {
                $url = 'https://www.zalora.co.id/men/produk-baru/?sort=popularity&dir=desc&page='
                .$page.'&gender=men&enable_visual_sort=1&category_id=';
                $request = $client->get($url)->getBody()->getContents();
                $script = preg_match('/"docs":\[{(.*)\]},"facet_counts":/siU', $request, $matchesScript);

                if ($script) {
                    $splitProduk = explode('"meta":', $matchesScript[1]);
                    unset($splitProduk[0]);

                    $this->createLink($splitProduk);
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
            sleep(5);
        }

        $data = [
            'newCount' => $this->newCount,
            'existCount' => $this->existCount,
            'pageError' => $pageError
        ];

        $this->sendNotification($data, 'product-new');

        foreach ($pageError as $error) {
            $this->info($error);
        }

        //$this->info("Exist: ".$this->existCount);
        //$this->info("New: ".$this->$newCount);
    }

    public function createLink($splitProduk)
    {
        foreach ($splitProduk as $key => $value) {
            preg_match('/"link":"(.*)","image":/siU', $value, $matchesLink);
            $exist = $this->checkDataExist("https://www.zalora.co.id/".$matchesLink[1]);

            if ($exist) {
                //$this->info($matchesLink[1]." is exist");
                $this->existCount++;
            } else {
                $this->info($matchesLink[1]." save to database");
                $this->newCount++;
                LinkProduct::create([
                    'url' => "https://www.zalora.co.id/".$matchesLink[1]
                ]);
            }

            if ($key == count($splitProduk) - 5) {
                break;
            }
        }
    }

    public function checkDataExist($url)
    {
        //exist data
        $existProduct = Product::where('url', $url)->exists();
        $existLinkProduct = LinkProduct::where('url', $url)->exists();

        return ($existProduct || $existLinkProduct);
    }

    public function sendNotification($data, $type)
    {
        $title = "Zalora Product Info";
        $when = Carbon::now()->addSecond(5);
        $this->info('send email after 5 second');
        Mail::to('hafizh@suitmedia.com', 'hafizh')->later($when, new SendNotification($data, $type, $title));
    }
}
