<?php

require_once "vendor/autoload.php";
require_once __DIR__ . "/src/Marketplaces/Tokopedia.php";
require_once __DIR__ . "/src/DB/Db.php";

use Marketplaces\Tokopedia;

$start_time = microtime(true);

// $marketplace = "bukalapak";

// $db = new DB('localhost:8889', 'esmart2', 'root', 'root');


$tokopedia = new Tokopedia("https://www.tokopedia.com/revantine");

$store_info = $tokopedia->getStoreInformation();
// $store_products = $bukalapak->getProducts();
var_dump($store_info);
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
