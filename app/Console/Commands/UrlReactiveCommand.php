<?php

namespace App\Console\Commands;

use App\Product;
use App\Traits\CrawlerTrait;
use Illuminate\Console\Command;

class UrlReactiveCommand extends Command
{
    use CrawlerTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'url:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check url that has been reactive again';

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
        //get all inactive products
        $products = Product::where('is_active', 0)->get();

        foreach ($products as $product) {
            $data = $this->crawlingData($product->url);

            //check data
            if ($data) {
                $product->update(['is_active' => 1]);
                $this->info($product->title." url's is being active again");
            }
        }
    }
}
