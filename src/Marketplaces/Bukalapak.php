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
        $browser = $this->puppeteer->launch(["headless" => true, "args" => ['--no-sandbox', '--disable-setuid-sandbox']]);
        $page = $browser->newPage();

        echo "Open page \n";

        $page->goto($this->url, array(
            "waitUntil" => "networkidle0",
            "timeout" => 0,
        ));

        echo "Open page finished \n";

        echo "Get store Information \n";

        $page->waitForSelector(".ut-store-name", ['visible' => true]);

        $store = $page->evaluate(JsFunction::createWithBody('
            let lvlReputasi = document.querySelector(".c-label--super-seller");
            let lvl_reputasi = "";
            if(!lvlReputasi){
                lvl_reputasi = "none";
            }else{
                lvl_reputasi = lvlReputasi.innerText;
            }
            return {
                "name": document.querySelector(".ut-store-name").innerText,
                "description": document.querySelectorAll(".c-tab__content__body")[1].innerText,
                "location": document.querySelector(".ut-store-city").innerText,
                "review_count": document.querySelector(".ut-total-feedback .c-link--quaternary").innerText,
                "product_count": document.querySelector("#merchant-page").dataset["productsCount"],
                "rating_count": "Unavaible",
                "store_image": document.querySelector(".c-avatar > img").getAttribute("src"),
                "join_date": document.querySelectorAll(".ut-join")[1].innerText,
                "lvl_reputasi": lvl_reputasi,
                "url": window.location.href
            }
        '));

        echo "Get Products link \n";

        $links = [];

        $page->waitForSelector(".c-pagination__btn .c-icon--arrow-forward", ['visible' => true]);


        $productLinks = $page->evaluate(JsFunction::createWithBody('
            let links = [];
            document.querySelectorAll(".c-product-card__name.js-tracker-product-link").forEach(el => {
                links.push(el.getAttribute("href"));
            })

            return links;
        '));

        $links = array_merge($links, $productLinks);

        $isDisabled = $page->evaluate(JsFunction::createWithBody('
            return !!document.querySelector(".c-pagination__btn .c-icon--arrow-forward").parentElement.getAttribute("disabled");
        '));

        while (!$isDisabled) {
            $page->waitForSelector(".c-pagination__btn .c-icon--arrow-forward", ['visible' => true]);
            $page->click(".c-pagination__btn .c-icon--arrow-forward");

            $page->waitForSelector(".c-product-card__name.js-tracker-product-link", ['visible' => true]);

            $products = $page->evaluate(JsFunction::createWithBody('
            let links = [];
            document.querySelectorAll(".c-product-card__name.js-tracker-product-link").forEach(el => {
                links.push(el.getAttribute("href"));
            })

            return links;
            '));

            $links = array_merge($links, $products);

            $isDisabled = $page->evaluate(JsFunction::createWithBody('
                return !!document.querySelector(".c-pagination__btn .c-icon--arrow-forward").parentElement.getAttribute("disabled");
            '));

        }
       
        $this->Productlinks = $links;
        
        $browser->close();

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
            "lvl_reputasi" => $data['lvl_reputasi'],
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
            "tanggal_crawl" => $data['tanggal_crawl'],
            "rating" => $data['rating'],
            // "rating" => (isset($data['rating']['average_rate'])) ? $data['rating']['average_rate'] : 0,
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
    public function saveProducts($product){
        // TO DO
        // Insert data product after scraping 1 product; ++
        

    }
    public function getProducts()
    {
        $products = [];

        $links = $this->Productlinks;

        echo "Get the first 10 Product data, total product : " . count($links) . "\n";
        // get 10 products
        for ($i = 1; $i <= 2; $i++) {
            $product = $this->getProduct($links[$i]);
            $products[] = $product;
            return $product;
        }

        // echo "Get all Product data, total product : " . count($links) . "\n";
        // //get all products
        // $count = 1;
        // foreach ($links as $link) {
        //     echo "get product " . $count . "/" . count($links) . "\n";
        //     $product = $this->getProduct($link);

        //     $products[] = $product;
        //     $count++;
        // }

        return true;
    }

    public function getProduct($link)
    {
        $browser = $this->puppeteer->launch(["headless" => true, "args" => ['--no-sandbox', '--disable-setuid-sandbox']]);
        $page = $browser->newPage();

        $page->goto($link, array(
            "waitUntil" => "networkidle0",
            "timeout" => 0,
        ));

        $page->waitForSelector(".c-product-detail__name", ['visible' => true]);

        $result = $page->evaluate(JsFunction::createWithBody('
                let current_datetime = new Date()
                let gambar = []
                let kurir = []
                let keterangan = []
                document.querySelectorAll(".js-product-image-gallery__main > .js-product-image-gallery__image").forEach(img => {
                    gambar.push(img.getAttribute("href"))
                });
                document.querySelectorAll(".qa-seller-shipping-courier-value > span").forEach(kur => {
                    kurir.push(kur.getAttribute("title"))
                });
                document.querySelectorAll(".c-seller-tnc__content > p").forEach(ket => {
                    keterangan.push(ket.innerText)
                })
                let diskons = document.querySelectorAll(".c-product-detail-price > span");
                let diskon = "";
                let harga = "";
                if(diskons.length == 1){
                    diskon = 0;
                    harga = document.querySelector(".c-product-detail-price > span").innerText.split("Rp")[1]
                }else{
                    diskon = document.querySelectorAll(".c-product-detail-price > span")[1].innerText.split("Rp")[1]
                    harga = document.querySelectorAll(".c-product-detail-price > span")[0].innerText.split("Rp")[1]
                }
                return {
                    "kategori": document.querySelectorAll(".c-breadcrumb > .c-breadcrumb__item > .c-breadcrumb__link")[1].innerText,
                    "nama": document.querySelector(".c-product-detail__name").innerText,
                    "deskripsi": document.querySelector(".js-collapsible-product-detail > p").innerText,
                    "keterangan": keterangan.toString(),
                    "foto_produk": gambar,
                    "kurir": kurir,
                    "tanggal_crawl": current_datetime.getFullYear() + "-" + (current_datetime.getMonth() + 1) + "-" + current_datetime.getDate() + " " + current_datetime.getHours() + ":" + current_datetime.getMinutes() + ":" + current_datetime.getSeconds(),
                    "rating": document.querySelector(".c-rating__fg").getAttribute("style").substr(7),
                    "jumlah_review": parseInt(document.querySelector(".qa-pd-review-tab").innerText),
                    "diskon": diskon,
                    "harga": harga,
                    "jumlah_barang": document.querySelector(".qa-pd-stock > strong > span").innerText.split(" ")[1],
                    "kondisi_barang": document.querySelector(".qa-pd-condition-value > span").innerText,
                    "jumlah_view": document.querySelector(".js-product-seen-value").innerText,
                    "waktu_proses": document.querySelectorAll(".qa-seller-delivery-duration-value > .u-fg--green-super-dark")[0].innerText,
                    "jumlah_favorit": document.querySelector(".qa-pd-favorited-value").innerText
                    
                }
            '));
        
      
        
        // echo "Get Feedback Links";

        // $links = [];

        // $page->click(".js-user-feedback-list");
        // var_dump($page);
        // $feedbackLinks = $page->evaluate(JsFunction::createWithBody('
        //     let links = []
        //     document.querySelector("")
        // '));
   
        $browser->close();

        return $result;

    }
}
