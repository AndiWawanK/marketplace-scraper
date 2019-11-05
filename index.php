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
$store_products = $bukalapak->getProducts();

$db = new DB('localhost:8889','esmart','root','root');

$id_toko = $db->insertStore($store_info);

$insert_product = $db->insertProducts($store_products, $id_toko);
// if($id_toko){
//     echo "Insert Store Information Success";

// }else{
//     echo "Failed to Insert Store Information";
// }

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
