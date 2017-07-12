<?php

namespace App\Console\Commands;

use App\Product;
use App\Traits\CrawlerTrait;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PriceNowCommand extends Command
{
    use CrawlerTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'price:now';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the product price daily';

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
            //get data from crawling
            $data = $this->crawlingData($product->url);

            if ($data) {
                if ($product->priceChanges->count()) {
                    if ($product->priceChanges->count() < 2) {
                        //compare with product
                        $this->compareWithProduct($product, $data);
                    } else {
                        //compare price change
                        $this->compareWithPriceChange($product, $data);
                    }
                } /*else {
                    $this->updateData($product, $data, 'equal');
                }*/
            }
        }
    }

    public function compareWithProduct($product, $data)
    {
        $priceChange = $product->priceChanges->first();

        if ($priceChange->price_discount < $product->price_discount) {
            $this->updateData($product, $data, 'lower');
        } elseif ($priceChange->price_discount > $product->price_discount) {
            $this->updateData($product, $data, 'higher');
        } elseif ($priceChange->price < $product->price) {
            $this->updateData($product, $data, 'lower');
        } elseif ($priceChange->price > $product->price) {
            $this->updateData($product, $data, 'higher');
        }
    }

    public function compareWithPriceChange($product, $data)
    {
        $priceChanges = $product->priceChanges()->orderBy('created_at', 'desc')->limit(2)->get();

        if ($priceChanges[0]->price_discount < $priceChanges[1]->price_discount) {
            $this->updateData($product, $data, 'lower');
        } elseif ($priceChanges[0]->price_discount > $priceChanges[1]->price_discount) {
            $this->updateData($product, $data, 'higher');
        } elseif ($priceChanges[0]->price < $priceChanges[1]->price) {
            $this->updateData($product, $data, 'lower');
        } elseif ($priceChanges[0]->price > $priceChanges[1]->price) {
            $this->updateData($product, $data, 'higher');
        }
    }

    public function updateData($product, $data, $status)
    {
        $product->priceNow->update([
            'price' => $data['price'],
            'price_discount' => $data['price_discount'],
            'status' => $status
        ]);

        if ($status != 'equal' && $product->priceNow->updated_at->diff(Carbon::now())->days > 14) {
            $product->priceNow->update([
                'status' => 'equal'
            ]);
            $status = "change to equal due the product price has not change more than 2 weeks";
        }

        $this->info('product '.$product->title.' change to '.$status);
    }
}
