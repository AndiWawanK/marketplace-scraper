<?php

namespace Marketplaces;

use PHPHtmlParser\Dom;
use GuzzleHttp\Client;


/**
 * Blanja.com scraper library
 */
class Blanja  
{

    /** @var Client $requester GuzzleHttp\Client instance */
    protected $requester;

    /**
     * @param String $url Description
     **/
    public function __construct(String $url) {
        $this->url = $url;
    }

    public function getStoreInformation()
    {
        $request = file_get_contents("https://www.blanja.com/store/gameland");

        $dom = new Dom();

        // var_dump($request);
        $dom->load($request);

        $store_name = $dom->find('h1')->innerHtml;

        var_dump($store_name);
    }
    
}
