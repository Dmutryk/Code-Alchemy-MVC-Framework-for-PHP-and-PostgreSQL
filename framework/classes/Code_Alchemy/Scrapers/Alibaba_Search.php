<?php


namespace Code_Alchemy\Scrapers;

/**
 * Class Alibaba_Search
 * @package Code_Alchemy\Scrapers
 *
 * Scrapes data from Alibaba.com
 *
 */
class Alibaba_Search extends Website_Scraper {

    public function __construct( $query ){

//        $this->url = 'http://www.alibaba.com/trade/search?IndexArea=product_en&CatId=&SearchText='.$query;

        $this->url = 'http://www.alibaba.com/';

    }

}