<?php

namespace App\Console\Commands;

use App\Product;
use App\Traits\CrawlerTrait;
use Illuminate\Console\Command;
use Mail;

class StyleChangeCommand extends Command
{
    use CrawlerTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'style:change';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily look style change from zalora';

    /**
     * The attribute that used by product.
     *
     * @var array
     */
    protected $attribute = [
        'title' => 0,
        'brand' => 0,
        'sku' => 0,
        'price' => 0,
        'priceDiscount' => 0,
        'imageLink' => 0,
        'category' => 0,
    ];

    /**
     * The attribute when data is empty
     * due to url's product not available or style has change
     *
     * @var integer
     */
    protected $emptyData = 0;

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

            //check empty data
            if (empty($data)) {
                $this->emptyData++;
                session()->pull('errorURL');
                session()->pull('errorNode');
            } else {
                $this->countAtrribute($data);
            }
        }

        // check style with counter
        // using array_keys because array_value not used yet.
        foreach (array_keys($this->attribute) as $value) {
            $this->compareCountAttribute($products, $value);
        }
    }

    /**
     * Counter the attribute data. If has not attribute count++.
     * @param $data  Show data from crawler.
     */
    public function countAtrribute($data)
    {
        $data['title']? $this->attribute['title'] += 0 : $this->attribute['title'] += 1;
        $data['brand']? $this->attribute['brand'] += 0 : $this->attribute['brand'] += 1;
        $data['sku']? $this->attribute['sku'] += 0 : $this->attribute['sku'] += 1;
        $data['price']? $this->attribute['price'] += 0 : $this->attribute['price'] += 1;
        $data['priceDiscount']? $this->attribute['priceDiscount'] += 0 : $this->attribute['priceDiscount'] += 1;
        $data['image_link']? $this->attribute['imageLink'] += 0 : $this->attribute['imageLink'] += 1;
        $data['category']? $this->attribute['category'] += 0 : $this->attribute['category'] += 1;
    }

    /**
     * Check the counter attribute data.
     * If same counter, send notification that style has change.
     * @param $products Product from database.
     * @param $key Array key from attribute.
     */
    public function compareCountAttribute($products, $key)
    {
        if ($this->attribute[$key] == $products->count() - $this->emptyData) {
            $this->info($key.' the style has change');

            $data = $key." style has changed";
            $this->sendNotification($data, 'style');
        } else {
            $this->info('the style doesn\'t change');
        }
    }

    /**
     * Send email notification.
     * @param $data  Show data from crawler.
     * @param $type The type of email.
     */
    public function sendNotification($data, $type)
    {
        Mail::later(10, 'emails.'.$type, compact('data'), function ($message) use ($data) {
            $message->to('hafizh@suitmedia.com', 'hafizh')
                ->subject('Zalora Change Style');
        });
    }
}
