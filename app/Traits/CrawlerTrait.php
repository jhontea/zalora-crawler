<?php

namespace App\Traits;

use Exception;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

trait CrawlerTrait
{
    /*
    |--------------------------------------------------------------------------
    | Crawler Trait
    |--------------------------------------------------------------------------
    |
    | This controller handle data from front-end style zalora. If error happen,
    | check the style from zalora website if anything changed from the original
    | page.
    |
    */

    /**
     * Where to change style if anything changed from the original page
     *
     * @var string
     */

    protected $style = [
        "title"                 => "#product-box > div > div.js-left-main > section >
                                    div.clearfix.box > div.l-productDetail.lfloat.box >
                                    div > div.prd-hd.box > h1 > div.product__title.fsm",
        'brand'                 => '#product-box > div > div.js-left-main > section >
                                    div.clearfix.box > div.l-productDetail.lfloat.box > div >
                                    div.prd-hd.box > h1 > div.js-prd-brand.product__brand > a',
        'sku'                   => '#configSku',
        'price'                 => '#priceAndEd > div > div > span',
        'priceDiscount'         => '#priceAndEd > div > div.price-box__special-price >
                                    span > span.js-detail_updateSku_lowestPrice > span.value',
        'image'                 => '#prdImage',
        'category'              => '#content > div.l-pageWrapper.l-productPage >
                                    div.breadcrumb.box.title-bar.ui-bg-light-primary.pvl >
                                    div.b-breadcrumb.lfloat > ul > li:nth-child(3) > a > span',
        'category-lastChild'    => '#content > div.l-pageWrapper.l-productPage >
                                    div.breadcrumb.box.title-bar.pal > div.b-breadcrumb.lfloat >
                                    ul > li.active.prs.last-child > span',
    ];

    /**
     * Where to crawl the data.
     *
     * @return array
     */

    public function crawlingData($url)
    {
        //check URL
        $html = $this->checkUrl($url);
        if (!$html) {
            return 0;
        }

        //check Node List
        try {
            $crawler = new Crawler($html);
            $data = [
                'title'         => $crawler->filter($this->style['title'])->text(),
                'brand'         => $crawler->filter($this->style['brand'])->text(),
                'sku'           => $crawler->filter($this->style['sku'])->attr('value'),
                'price'         => $crawler->filter($this->style['price'])->count()?
                    preg_replace('/\D/', "", $crawler->filter($this->style['price'])->text()) : '',
                'priceDiscount' => $crawler->filter($this->style['priceDiscount'])->count()?
                    preg_replace('/\D/', "", $crawler->filter($this->style['priceDiscount'])->text()) : '',
                'image'         => $crawler->filter($this->style['image'])->attr('src'),
                'category'      => $crawler->filter($this->style['category'])->count()?
                    $crawler->filter($this->style['category'])->text() :
                    $crawler->filter($this->style['category-lastChild'])->text(),
                'url' => $url
            ];
            $data['discount'] = $data['priceDiscount']?
                round(($data['price'] - $data['priceDiscount']) / $data['price'] * 100) : 0;
            return $data;
        } catch (Exception $e) {
            session()->put('errorNode', 'The current node list is empty. File '.
                $e->getTrace()[0]['file'].' line '.$e->getTrace()[0]['line']);
            return 0;
        }
    }

    /**
     * Where to check the url five times.
     *
     * @return boolean
     */

    public function checkUrl($url)
    {
        $client = new Client();
        $maxCheck = 5;

        while ($maxCheck) {
            try {
                $request = $client->get($url)->getBody()->getContents();
                return $request;
            } catch (Exception $e) {
                if ($e->getCode() == 404) {
                    session()->put('errorURL', 'URL not available');
                } else {
                    if (array_key_exists('Host', $e->getRequest()->getHeaders())) {
                        session()->put('errorURL', 'Time has running out. Please check the connection');
                    } else {
                        session()->put('errorURL', 'Must be a valid URL.');
                    }
                }
                $maxCheck--;
            }
        }
        return 0;
    }
}
