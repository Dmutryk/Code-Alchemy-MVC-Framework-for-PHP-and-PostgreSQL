<?php


namespace Code_Alchemy\Scrapers;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Helpers\Code_Alchemy_Root_Path;


require_once (string) new Code_Alchemy_Root_Path."/include/simple_html_dom.php";

/**
 * Class Website_Scraper
 * @package Code_Alchemy\Scrapers
 *
 * Scrapes data from a website, and presents it back as an array
 */
class Website_Scraper extends Array_Representable_Object{

    /**
     * @var string URL to scrape
     */
    protected $url = '';

    /**
     * @var string Raw data
     */
    private $raw_data = '';

    /**
     * @return $this
     */
    public function scrape(){

        $this->raw_data = file_get_html( $this->url );

        \FB::info($this->raw_data->plaintext);

        return $this;

    }

}