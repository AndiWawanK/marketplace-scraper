<?php

require_once "vendor/autoload.php";
require_once __DIR__ . "/src/Marketplaces/Tokopedia.php";
require_once __DIR__ . "/src/DB/Db.php";

use Marketplaces\Tokopedia;

$start_time = microtime(true);

// $marketplace = "bukalapak";

// $db = new DB('localhost:8889', 'esmart2', 'root', 'root');


$tokopedia = new Tokopedia("https://www.tokopedia.com/revantine/page/2");

$store_info = $tokopedia->getStoreInformation();

if($store_info) {
    // $update_store = $db->updateStore($store_info, $stores['id_perusahaan']);
    // echo "Update Store Information Successful \n";
    $product_links = $tokopedia->Productlinks;

    for ($i = 0; $i <= 1; $i++) {
        echo "get Data " . $product_links[$i] . "\n";
        // if ($db->isExist($product_links[$i])) {
        //     echo "Product already saved, Skip to next product \n";
        //     continue;
        // }
        $product = $tokopedia->getProduct($product_links[$i]);
        
        var_dump($product);

        // echo "Insert to DB \n";
        // $db->insertProduct($product,$stores['id_toko']);
        // $db->addToSavedLink($product_links[$i]);
    }
}




// // End clock time in seconds
$end_time = microtime(true);

// // Calculate script execution time
$execution_time = ($end_time - $start_time);
echo " Execution time of script = " . $execution_time . " sec";

