<?php

namespace Marketplaces;

use Nesk\Puphpeteer\Puppeteer;
use Nesk\Rialto\Data\JsFunction;

class Bukalapak
{
    private $url;
    private $puppeteer;

    public function __construct($url)
    {
        $this->url = $url;
        $this->puppeteer = new Puppeteer();
    }

    public function getStoreInformation()
    {
        $browser = $this->puppeteer->launch(["headless" => true]);
        $page = $browser->newPage();

        echo "Open page";

        $page->goto($this->url, array(
            "waitUntil" => "networkidle0",
            "timeout" => 0,
        ));

        echo "Open page finished";

        echo "Get store Information";

        $store = $page->evaluate(JsFunction::createWithBody('
            return {
                "name": document.querySelector(".ut-store-name").innerText,
                "description": document.querySelectorAll(".c-tab__content__body")[1].innerText,
                "location": document.querySelector(".ut-store-city").innerText,
                "review_count": document.querySelector(".ut-total-feedback .c-link--quaternary").innerText,
                "product_count": document.querySelector("#merchant-page").dataset["productsCount"],
                "rating_count": "Unavaible",
                "store_image": document.querySelector(".c-avatar > img").getAttribute("src"),
                "join_date": document.querySelectorAll(".ut-join")[1].innerText,
                "url": window.location.href
            }
        '));
        

        echo "Get Products link";

        $links = [];

        $products = $page->evaluate(JsFunction::createWithBody('
            let links = [];
            document.querySelectorAll(".c-product-card__name.js-tracker-product-link").forEach(el => {
                links.push(el.getAttribute("href"));
            })

            return links;
        '));

        $links = array_merge($links, $products);

        $page->click(".c-pagination__btn .c-icon--arrow-forward");
        foreach($links as $go){
            $page->goto($go);
            $prds = $page->evaluate(JsFunction::createWithBody('
                let gambar = []
                let kurir = []
                document.querySelectorAll(".js-product-image-gallery__main > .js-product-image-gallery__image").forEach(img => {
                    gambar.push(img.getAttribute("href"))
                });
                document.querySelectorAll(".qa-seller-shipping-courier-value > span").forEach(kur => {
                    kurir.push(kur.getAttribute("title"))
                });
                
                
                return {
                    "kategori": document.querySelectorAll(".c-breadcrumb > .c-breadcrumb__item > .c-breadcrumb__link")[1].innerText,
                    "nama": document.querySelector(".c-product-detail__name").innerText,
                    "deskripsi": document.querySelector(".js-collapsible-product-detail > p").innerText,
                    "keterangan": "Unvailable",
                    "foto_produk": gambar,
                    "kurir": kurir
                }
            '));
            var_dump($prds);

        }
        
        // var_dump($links);
        // die;
        // foreach($links as $link){
        //     $getproduct = $links->evaluate(JsFunction::createWithBody('
        //         document.querySelector(".c-product-card__name .js-tracker-product-link").click();
        //     '));

        //     var_dump($getproduct);
        //     die;
        // }
        
        
        
        // $prd = $page->evaluate(JsFunction::createWithBody('
        //     return {
        //         "name": document.querySelector(".c-product-detail__name .qa-pd-name")
        //     }
        // '));

        // var_dump($prd);
        // die;

        // $isDisabled = $page->evaluate(JsFunction::createWithBody('
        //     return !!document . querySelector(".c-pagination__btn .c-icon--arrow-forward") . parentElement . getAttribute("disabled");
        // '));

        // while (!$isDisabled) {
        //     // $page->evaluate(JsFunction::createWithBody('
        //     //     document.querySelector(".c-pagination__btn .c-icon--arrow-forward").click();
        //     // '));

        //     $page->click(".c-pagination__btn .c-icon--arrow-forward");

        //     // $products = $page->evaluate(JsFunction::createWithBody('
        //     // let links = [];
        //     // document.querySelectorAll(".c-product-card__name.js-tracker-product-link").forEach(el => {
        //     //     links.push(el.getAttribute("href"));
        //     // })

        //     // return links;
        //     // '));

        //     // $links = array_merge($links, $products);

        //     $page->waitForNavigation([ "waitUntil" => 'networkidle0' ]);

        //     $isDisabled = $page->evaluate(JsFunction::createWithBody('
        //         return !!document . querySelector(".c-pagination__btn .c-icon--arrow-forward") . parentElement . getAttribute("disabled");
        //     '));


        // }

        $browser->close();
        // var_dump($links);
        
        // var_dump($store);
        return $this->formatOutput($store);
    }

    public function formatOutput($data)
    {
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
            "url" => $data["url"],
        );

        return $result;
    }

    public function formatProduct($data)
    {
        $result = array(
            "nama" => $data['nama'],
            "kategori" => $data['kategori'],
            "deskripsi" => $data['deskripsi'],
            "keterangan" => $data['keterangan'],
            "gambar" => $data['foto_produk']['large_urls'][0],
            "kurir" => $data['kurir'],
            "rating" => (isset($data['rating']['average_rate'])) ? $data['rating']['average_rate'] : 0,
            "review_count" => "Unavaible",
            "discount" => (empty($data['deal'])) ? 0 : $data['deal']['percentage'],
            "initial_price" => (empty($data['deal'])) ? $data['price'] : $data['deal']['original_price'],
            "discount_price" => (empty($data['deal'])) ? 0 : $data['deal']['discount_price'],
            "normal_price" => $data['price'],
            "stock" => $data['stock'],
            "product_sold_count" => $data['stats']['sold_count'],
            "product_visible_count" => $data['stats']['view_count'],

        );

        return $result;
    }

    public function getProducts()
    {
        $limit = 20;
        $total_page = floor($this->product_count / 20);
        $offset = 0;

        $products = [];

        for ($i = 1; $i <= $total_page; $i++) {
            $link = "https://api.bukalapak.com/stores/" . $this->merchant_id . "/products/?offset=" . $offset . "&limit=" . $limit . "&access_token=" . $this->auth_token;
            $res = $this->api->get($link);
            $body = (string) $res->getBody();

            $data = json_decode($body, true);

            foreach ($data['data'] as $product) {
                $products[] = $this->formatProduct($product);
            }

            $offset = (($i + 1) - 1) * $limit;
        }

        return $products;
    }
}
