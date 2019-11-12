<?php

require_once "vendor/autoload.php";
require_once __DIR__ . "/src/Marketplaces/Bukalapak.php";
require_once __DIR__ . "/src/Marketplaces/Shopee.php";
require_once __DIR__ . "/src/Marketplaces/Blanja.php";
require_once __DIR__ . "/src/DB/Db.php";

use Marketplaces\Bukalapak;
use Marketplaces\Shopee;

$start_time = microtime(true);

$marketplace = "bukalapak";

$db = new DB('localhost:8889', 'esmart2', 'root', 'root');


$store = $db->getStore($marketplace);

// $storeURL = [];
while ($stores = $store->fetch(PDO::FETCH_ASSOC)) {
    // $storeURL[] = $stores['url'];

    $bukalapak = new Bukalapak($stores['url']);
    $store_info = $bukalapak->getStoreInformation();
    echo "Scrap Store Information Successful\n";
    
    if ($store_info) {
        // var_dump($stores['id_perusahaan'],$stores['url']);
        $update_store = $db->updateStore($store_info, $stores['id_perusahaan']);
        echo "Update Store Information Successful \n";

        $product_links = $bukalapak->Productlinks;

        for ($i = 0; $i <= 1; $i++) {
            echo "get Data " . $product_links[$i] . "\n";

            if ($db->isExist($product_links[$i])) {
                echo "Product already saved, Skip to next product \n";
                continue;
            }

            $product = $bukalapak->getProduct($product_links[$i]);
            
            echo "Insert to DB \n";
            
            $db->insertProduct($product,$stores['id_toko']);
            $db->addToSavedLink($product_links[$i]);
        }

    } else {
        echo "Scrap Store Information Failed\n";
    }

}

// $bukalapak = new Bukalapak("https://www.bukalapak.com/u/rikky");

// $store_info = $bukalapak->getStoreInformation();
// $store_products = $bukalapak->getProducts();

//

// $id_toko = $db->insertStore($store_info);
// $insert_product = $db->insertProducts($store_products, $id_toko);

// // End clock time in seconds
$end_time = microtime(true);

// // Calculate script execution time
$execution_time = ($end_time - $start_time);

echo " Execution time of script = " . $execution_time . " sec";

// $store_file = fopen("store.txt", "w");
// fwrite($store_file,print_r($store_info,true));

// $product_file = fopen("products.txt", "w");
// fwrite($product_file, print_r($store_products, true));

// $debug_file = fopen("debug.txt","w");
// fwrite($debug_file," Execution time of script = " . $execution_time . " sec");
