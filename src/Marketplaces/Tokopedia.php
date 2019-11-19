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
        $browser = $this->puppeteer->launch(["headless" => false, "args" => ['--no-sandbox', '--disable-setuid-sandbox']]);
        $page = $browser->newPage();
    
        echo "Open page \n";
        $page->setViewport(["width" => 1060, "height" => 768]);
        $page->goto($this->url, array(
            "waitUntil" => "networkidle0",
            "timeout" => 0,
        ));

        echo "Open page finished \n";

        echo "Get store Information \n";
        
        // $page->waitForSelector(".css-z606dp-unf-btn", ['visible' => true]);

        $page->click(".css-z606dp-unf-btn");
        $page->waitForSelector(".css-149rvan-unf-modal",['visible' => true]);
        

        $store = $page->evaluate(JsFunction::createWithBody('
            return {
                "name": document.querySelector(".css-14uf4nq-unf-heading > span").innerText,
                "author": document.querySelector(".css-c04u4w-unf-heading").innerText,
                "information": document.querySelector(".css-1x8q13v-unf-heading > span").innerText,
                "location": document.querySelector(".css-1x8q13v-unf-heading > div").innerText,
                "store_image": document.querySelector(".css-1ew46s1-unf-img > img").getAttribute("src"),
                "join_date": document.querySelector(".css-jg7uhu-unf-heading").innerText,
                "store_url": window.location.href,
                "count_review": document.querySelector(".css-1oz5w6c-unf-heading").innerText
            }
        '));

        // var_dump($store);
        $page->waitForSelector(".css-149rvan-unf-modal",['visible' => true]);
        $page->click(".css-10n77n5-unf-modal__icon");


        echo "Get Products link \n";

        $links = [];

        $page->waitForSelector(".css-merchant-3leNUqwk", ['visible' => true]);

        $productLinks = $page->evaluate(JsFunction::createWithBody('
            let links = [];
            document.querySelectorAll(".css-16wn18y").forEach(el => {
                links.push(el.getAttribute("href"));
            })

            return links;
        '));

        $links = array_merge($links, $productLinks);

        $this->Productlinks = $links;
        
        $browser->close();

        return $this->formatOutput($store);
    }

    public function formatOutput($data){
        $result = array(
            "name" => $data['name'],
            "author" => $data['author'],
            "information" => $data['information'],
            "location" => $data['location'],
            "store_image" => $data['store_image'], 
            "join_date" => $data['join_date'], 
            "store_url" => $data['store_url'],
            "count_review" => intval(preg_replace("/[^A-Za-z0-9\  ]/", "", $data['count_review']))
        );

        return $result;
    }

   

    public function getProduct($link){
        $browser = $this->puppeteer->launch(["headless" => false, "args" => ['--no-sandbox', '--disable-setuid-sandbox']]);
        $page = $browser->newPage();

        $page->goto($link, array(
            "waitUntil" => "networkidle0",
            "timeout" => 0,
        ));

        $page->waitForSelector(".rvm-product-title", ['visible' => true]);

        $result = $page->evaluate(JsFunction::createWithBody('
            let current_datetime = new Date();
            let images = [];
            let couriers = [];
            document.querySelectorAll(".slick-track > .content-img > .content-img-wrapper > .content-img-relative > img").forEach(img => {
                images.push(img.getAttribute("src"));
            });
            document.querySelectorAll(".rvm-merchant-box > .rvm-shipping-support > .rvm-shipping-support__img-holder > img").forEach(cur => {
                couriers.push(cur.getAttribute("title"));
            });
            return {
                "category": document.querySelectorAll(".breadcrumb > li")[4].innerText,
                "name": document.querySelector(".rvm-product-title > span").innerText,
                "description": document.querySelector(".product-summary__content").innerText,
                "product_images": images,
                "courier": couriers,
                "date_crawl": current_datetime.getFullYear() + "-" + (current_datetime.getMonth() + 1) + "-" + current_datetime.getDate() + " " + current_datetime.getHours() + ":" + current_datetime.getMinutes() + ":" + current_datetime.getSeconds(),
                "rating": document.querySelector(".reviewsummary-rating-score").innerText,
                "review_count": document.querySelector(".review-container").innerText,
                "price": document.querySelector(".rvm-price > input").getAttribute("value"),
                "count_products": null,
                "product_condition": document.querySelectorAll(".rvm-product-info > .rvm-product-info--item > .inline-block > .rvm-product-info--item_value ")[2].innerText,
                "view_count": document.querySelectorAll(".rvm-product-info > .rvm-product-info--item > .inline-block > .view-count")[0].innerText,
                "time_process": null,
                "favorite_amount": null
            }
        '));

        $browser->close();
        return $this->formatProducts($result);
    }
    public function formatProducts($data){
        // TODO
        // Convert view_count to decimal number  
        $result = array(
            "category" => $data["category"], 
            "name" => $data["name"],
            "description" => $data["description"],
            "product_images" => $data["product_images"], 
            "courier" => $data["courier"],
            "date_crawl" => $data["date_crawl"],
            "rating" => explode("/", $data["rating"])[0],
            "review_count" => preg_replace("/[^0-9]/","",$data["review_count"]),
            "price" => $data["price"],
            "count_products" => $data["count_products"],
            "product_condition" => strtolower($data["product_condition"]),
            "view_count" => $data["view_count"],
            "time_process" => $data["time_process"]
        );
        return $result;
    }
}
