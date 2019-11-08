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

$marketplace = "bukalapak";

$db = new DB('localhost:8889','esmart2','root','root');
$store = $db->getStore($marketplace);

// $storeURL = [];
while($stores = $store->fetch(PDO::FETCH_ASSOC)){
    // $storeURL[] = $stores['url'];

    $bukalapak = new Bukalapak($stores['url']);
    $store_info = $bukalapak->getStoreInformation();
    if($store_info){
        echo "Scrap Store Information Successful\n";
        // var_dump($stores['url'], $stores['id_perusahaan']);
        $update_store = $db->updateStore($store_info, $stores['id_perusahaan']);
        
        // $store_products = $bukalapak->getProducts();
        // var_dump($store_products);
        // if($store_products){
        //     echo "Scrap Product Store Successful\n";
        // }else{
        //     echo "Scrap Product Store Failed\n";
        // }

    }else{
        echo "Scrap Store Information Failed\n";
    }

}
// foreach($storeURL as $url){
    
    
// }
// $bukalapak = new Bukalapak("https://www.bukalapak.com/u/rikky");

// $store_info = $bukalapak->getStoreInformation();
// $store_products = $bukalapak->getProducts();

// 

// $id_toko = $db->insertStore($store_info);
// $insert_product = $db->insertProducts($store_products, $id_toko);

if($insert_product){
    echo "Insert Data Success! \n";
}else{
    echo "Failed to Insert Data! \n";
}





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
