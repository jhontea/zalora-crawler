<?php

namespace App\Console\Commands;

use App\LinkProduct;
use App\Mail\SendNotification;
use App\PriceNow;
use App\Product;
use App\Traits\CrawlerTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Mail;

class ProductCrawlCommand extends Command
{
    use CrawlerTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:crawl';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $linkCrawls = LinkProduct::all();
        $countExist = 0;
        $countError = 0;
        $countStore = 0;

        foreach ($linkCrawls as $key => $link) {
            $exist = $this->checkLinkExist($link['url']);
            if ($exist) {
                //$this->info($link['url']." is exist");
                $countExist++;
            } else {
                $data = $this->crawlingData($link['url']);

                //check data
                if (empty($data)) {
                    //Show error from crawling data
                    $this->checkErrorEmptyData();
                    $countError++;
                } else {
                    //Process the data
                    $this->storeData($data);
                    $countStore++;
                }

                if ($key % 10 == 0) {
                    sleep(3);
                }
            }
        }

        $this->info("Exist: ".$countExist);
        $this->error("Error: ".$countError);
        $this->info("Store: ".$countStore);

        $data = [
            'countExist' => $countExist,
            'countError' => $countError,
            'countStore' => $countStore
        ];

        $this->sendNotification($data, 'product-crawl');
    }

    public function checkLinkExist($url)
    {
        return Product::where('url', $url)->exists();
    }

    public function checkErrorEmptyData()
    {
        if (session()->has('errorURL')) {
            //update to non-active
            //$product->update(['is_active' => 0]);

            //url not available, SEND EMAIL
            //$data = $product;
            //$data['error'] =  session()->pull('errorURL');

            $this->alert(session()->get('errorURL'));
            //$this->sendNotification($data, 'error');
        } elseif (session()->has('errorNode')) {
            //style has change, SEND EMAIL
            //$data = $product;
            //$data['error'] = session()->pull('errorNode');

            $this->alert(session()->get('errorNode'));
            //this->sendNotification($data, 'error');
        }
    }

    public function storeData($data)
    {
        $product = Product::create($data);

        PriceNow::create([
            'item_id' => $product->id,
            'price' => $data['price'],
            'price_discount' => $data['price_discount'],
            'status' => 'equal'
        ]);

        $this->info($data['title']." store to database");
    }

    public function sendNotification($data, $type)
    {
        /*Mail::send('emails.'.$type, compact('data'), function ($message) use ($data) {
            $message->to('hafizh@suitmedia.com', 'hafizh')
                ->subject('Zalora Product Crawl');
        });*/

        $title = "Zalora Product Info";
        $when = Carbon::now()->addSecond(5);
        $this->info('send email after 5 second');
        Mail::to('hafizh@suitmedia.com', 'hafizh')->later($when, new SendNotification($data, $type, $title));
    }
}
