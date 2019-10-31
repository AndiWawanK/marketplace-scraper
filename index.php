<?php

require_once "vendor/autoload.php";
require_once __DIR__."/src/Marketplaces/Bukalapak.php";
require_once __DIR__."/src/Marketplaces/Shopee.php";
require_once __DIR__."/src/Marketplaces/Blanja.php";
require_once __DIR__ . "/src/DB/Db.php";


use Marketplaces\Bukalapak;
use Marketplaces\Shopee;
use Marketplaces\Blanja;


$start_time = microtime(true);

$bukalapak = new Bukalapak("https://www.bukalapak.com/u/rikky");

$store_info = $bukalapak->getStoreInformation();
// $store_products = $bukalapak->getProducts();

// $db = new DB("202.47.80.110", "root", "mplacesisfor84!", "esmart");

// $id_toko = $db->insertStore($store_info);


// // End clock time in seconds
$end_time = microtime(true);

// // Calculate script execution time
$execution_time = ($end_time - $start_time);

echo " Execution time of script = " . $execution_time . " sec";
$product_file = fopen("products.txt", "w");
fwrite($product_file,print_r($store_info,true));