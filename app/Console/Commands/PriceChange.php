<?php

namespace App\Console\Commands;

use App\Product;
use App\Traits\CrawlerTrait;
use Illuminate\Console\Command;

class PriceChange extends Command
{
    use CrawlerTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'price:change';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily look price change from zalora';

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
        //get all active products
        $products = Product::where('is_active', 1)->get();

        foreach ($products as $product) {
            $data = $this->crawlingData($product->url);

            //check data
            if (empty($data)) {
                //Show error from crawling data
                if (session()->has('errorURL')) {
                    //url not available, SEND EMAIL
                    $this->info(session()->pull('errorURL'));
                } elseif (session()->has('errorNode')) {
                    //frontend has change, SEND EMAIL
                    $this->info(session()->pull('errorNode'));
                }
            } else {
                //Process the data
                $this->checkDataExist($data, $product);
            }
        }
    }

    public function checkDataExist($data, $product)
    {
        //exist data
        $exist = $product->where('price', $data['price'])->where('price_discount', $data['priceDiscount'])->exists();

        if ($exist) {
            $this->info($product->title." Price hasn't change");
        } else {
            $this->info($product->title." Price has change");
        }
    }
}
