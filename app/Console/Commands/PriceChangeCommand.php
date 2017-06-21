<?php

namespace App\Console\Commands;

use App\PriceChange;
use App\Product;
use App\Traits\CrawlerTrait;
use Illuminate\Console\Command;
use Mail;

class PriceChangeCommand extends Command
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
                $this->checkErrorEmptyData($data, $product);
            } else {
                //Process the data
                $this->checkDataExist($data, $product);
            }
        }
    }

    public function checkErrorEmptyData($data, $product)
    {
        if (session()->has('errorURL')) {
            //update to non-active
            $product->update(['is_active' => 0]);

            //url not available, SEND EMAIL
            $data = $product;
            $data['error'] =  session()->pull('errorURL');

            $this->info(session()->get('errorURL'));
            $this->sendNotification($data, 'error');
        } elseif (session()->has('errorNode')) {
            //style has change, SEND EMAIL
            $data = $product;
            $data['error'] = session()->pull('errorNode');

            $this->info(session()->get('errorNode'));
            $this->sendNotification($data, 'error');
        }
    }

    public function checkDataExist($data, $product)
    {
        //exist data
        $exist = $product->where('price', $data['price'])->where('price_discount', $data['priceDiscount'])->exists();

        if ($exist) {
            $this->info($product->title." Price hasn't change");
        } else {
            if ($product->priceChanges->count()) {
                $this->oldPriceChange($data, $product);
            } else {
                $this->newPriceChange($data, $product);
            }
        }
    }

    public function newPriceChange($data, $product)
    {
        if ($data['priceDiscount']) {
            $this->storeData($data, $product, 1);
        } else {
            $this->info($product->title." Price hasn't change");
        }
    }

    public function oldPriceChange($data, $product)
    {
        $pivot = $product->priceChanges->where('pivot', 1)->first();

        if ($data['priceDiscount'] < $pivot->price_discount) {
            $pivot->pivot = 0;
            $pivot->save();
            $this->storeData($data, $product, 1);
        } elseif ($data['priceDiscount'] > $pivot->price_discount) {
            $this->storeData($data, $product, 0);
        } elseif ($data['price'] < $pivot->price) {
            $this->storeData($data, $product, 1);
            $pivot->pivot = 0;
            $pivot->save();
        } elseif ($data['price'] > $pivot->price) {
            $this->storeData($data, $product, 0);
        } else {
            $this->info($product->title." Price hasn't change");
        }
    }

    public function storeData($data, $product, $pivot)
    {
        PriceChange::create([
            'item_id' => $product->id,
            'price' => $data['price'],
            'price_discount' => $data['priceDiscount'],
            'discount' => $data['discount'],
            'pivot' => $pivot,
        ]);

        $this->sendNotification($data, 'change');
    }

    public function sendNotification($data, $type)
    {
        Mail::send('emails.'.$type, compact('data'), function ($message) use ($data) {
            $message->to('hafizh@suitmedia.com', 'hafizh')
                ->subject('Zalora Change Price');
        });
    }
}
