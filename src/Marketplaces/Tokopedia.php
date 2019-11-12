<?php

namespace Marketplaces;

use Nesk\Puphpeteer\Puppeteer;
use Nesk\Rialto\Data\JsFunction;

class Tokopedia
{
    private $url;
    private $puppeteer;

    public function __construct($url)
    {
        $this->url = $url;
        $this->puppeteer = new Puppeteer();
    }

    public function getStoreInformation(){
        $browser = $this->puppeteer->launch(["headless" => true, "args" => ['--no-sandbox', '--disable-setuid-sandbox']]);
        $page = $browser->newPage();

        echo "Open page \n";

        $page->goto($this->url, array(
            "waitUntil" => "networkidle0",
            "timeout" => 0,
        ));

        echo "Open page finished \n";

        echo "Get store Information \n";

        $page->waitForSelector(".css-14uf4nq-unf-heading", ['visible' => true, "timeout" => 9000]);

        $store = $page->evaluate(JsFunction::createWithBody('
            return {
                "name": document.querySelector(".css-14uf4nq-unf-heading > span")
            }
        '));

        var_dump($store);
        // die;

        // echo "Get Products link \n";

        // $links = [];

        // $page->waitForSelector(".c-pagination__btn .c-icon--arrow-forward", ['visible' => true]);


        // $productLinks = $page->evaluate(JsFunction::createWithBody('
        //     let links = [];
        //     document.querySelectorAll(".c-product-card__name.js-tracker-product-link").forEach(el => {
        //         links.push(el.getAttribute("href"));
        //     })

        //     return links;
        // '));

        // $links = array_merge($links, $productLinks);

        // $isDisabled = $page->evaluate(JsFunction::createWithBody('
        //     return !!document.querySelector(".c-pagination__btn .c-icon--arrow-forward").parentElement.getAttribute("disabled");
        // '));

        // while (!$isDisabled) {
        //     $page->waitForSelector(".c-pagination__btn .c-icon--arrow-forward", ['visible' => true]);
        //     $page->click(".c-pagination__btn .c-icon--arrow-forward");

        //     $page->waitForSelector(".c-product-card__name.js-tracker-product-link", ['visible' => true]);

        //     $products = $page->evaluate(JsFunction::createWithBody('
        //         let links = [];
        //         document.querySelectorAll(".c-product-card__name.js-tracker-product-link").forEach(el => {
        //             links.push(el.getAttribute("href"));
        //         })
        //         return links;
        //     '));

        //     $links = array_merge($links, $products);

        //     $isDisabled = $page->evaluate(JsFunction::createWithBody('
        //         return !!document.querySelector(".c-pagination__btn .c-icon--arrow-forward").parentElement.getAttribute("disabled");
        //     '));

        // }
       
        // $this->Productlinks = $links;
        
        $browser->close();

        return $this->formatOutput($store);
    }

    public function formatOutput($data){
        $result = array(
            "name" => $data['name'],
            "shopname" => $data['name'],
            "description" => $data['description'],
            "location" => $data['location'],
            "shop_image" => $data['store_image'],
            "join_date" => $data['join_date'],
            "review_count" => (int) str_replace(".", "", $data["review_count"]),
            "product_count" => $data['product_count'],
            "rating_count" => $data['rating_count'],
            "lvl_reputasi" => $data['lvl_reputasi'],
            "url" => $data["url"],
        );

        return $result;
    }

}
