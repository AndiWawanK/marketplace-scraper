<?php

namespace Marketplaces;

use GuzzleHttp\Client;

class Shopee
{
    public function __construct($url)
    {
        $url_path = parse_url($url, PHP_URL_PATH);
        $paths = explode("/", $url_path);

        $this->username = end($paths);
        $this->api = new Client();
        $this->url = $url;
    }

    public function getStoreInformation()
    {
        $res = $this->api->get('https://shopee.co.id/api/v2/shop/get?username=' . $this->username);
        $body = (string) $res->getBody();
        $data = json_decode($body, true);

        $result = $this->formatOutput($data['data']);

        return $result;

    }

    public function formatOutput($data)
    {
        $result = array(
            "name" => $data['name'],
            "shopname" => $data['name'],
            "description" => $data['description'],
            "location" => $data['place'],
            "review_count" => $data['rating_bad'] + $data['rating_good'] + $data['raitng_normal'],
            "product_count" => $data['item_count'],
            "rating_count" => (float)$data['rating_star'],
            "url" => $this->url,
        );

        return $result;
    }
}
