<?php

class DB{
    // private $db;

    public function __construct($host,$db, $user, $pass){

        // $this->db = new PDO("mysql:host=".$host.";dbname=".$db, $user, $pass);
        try {
            $this->db = new PDO("mysql:host=".$host.";dbname=".$db, $user, $pass);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    
    }
    public function getStore($storeName){
        $store = "SELECT toko.id_toko, toko.id_perusahaan, toko.url, marketplace.nama
        FROM toko 
        JOIN marketplace ON toko.id_marketplace = marketplace.id_marketplace
        WHERE marketplace.nama = '$storeName'";
        $stores = $this->db->query($store);
        return $stores;   
    }
    public function insertStore($data)
    {
        // $create_id_perusahaan = substr(str_replace(".", "", microtime(true)), 10);
        // $id_perusahan = (int)$create_id_perusahaan;
        // $id_perusahan = "";

        $getid = "SELECT * FROM perusahaan ORDER BY id_perusahaan DESC LIMIT 1";
        $resultId = $this->db->query($getid);
        $resultsId = $resultId->fetchAll(PDO::FETCH_ASSOC);

        $id_perusahan = $resultsId[0]['id_perusahaan'];

        if($resultsId == NULL){
            $id_perusahan = 1;
            $sql = "INSERT INTO `perusahaan` (
                `id_perusahaan`, 
                `id_marketplace`, 
                `nama`, 
                `nama_toko`, 
                `keterangan`, 
                `alamat`, 
                `foto`, 
                `tgl_daftar`
                ) VALUES (
                    $id_perusahan, 
                    1, 
                    '$data[name]',
                    '$data[shopname]',
                    '$data[description]',
                    '$data[location]',
                    '$data[shop_image]',
                    '$data[join_date]'
                )";
             $this->db->query($sql);
        }else{
            $id_perusahan = $resultsId[0]['id_perusahaan'] + 1;
            $sql = "INSERT INTO `perusahaan` (
                `id_perusahaan`, 
                `id_marketplace`, 
                `nama`, 
                `nama_toko`, 
                `keterangan`, 
                `alamat`, 
                `foto`, 
                `tgl_daftar`
                ) VALUES (
                    $id_perusahan, 
                    1, 
                    '$data[name]',
                    '$data[shopname]',
                    '$data[description]',
                    '$data[location]',
                    '$data[shop_image]',
                    '$data[join_date]'
                )";
             $this->db->query($sql);

        }

        //    var_dump( $this->db->errorInfo());
        $getLastIdToko = "SELECT * FROM toko ORDER BY id_toko DESC LIMIT 1";
        $idToko = $this->db->query($getLastIdToko);
        $resultIdToko = $idToko->fetchAll(PDO::FETCH_ASSOC);

        $id_toko = $resultIdToko[0]['id_toko'];

        if($resultIdToko == NULL){
            $id_toko = 1;
            
            $sql = "INSERT INTO `toko`(
                `id_toko`,
                `id_perusahaan`,
                `id_marketplace`,
                `url`,
                `tgl_daftar`
                ) VALUES (
                    $id_toko,
                    $id_perusahan,
                    1, 
                    '$data[url]',
                    '$data[join_date]'
                )";
        
            $this->db->query($sql);
        }else{
            $id_toko = $resultIdToko[0]['id_toko'] + 1;
            $sql = "INSERT INTO `toko`(
                `id_toko`,
                `id_perusahaan`,
                `id_marketplace`,
                `url`,
                `tgl_daftar`
                ) VALUES (
                    $id_toko,
                    $id_perusahan,
                    1, 
                    '$data[url]',
                    '$data[join_date]'
                )";
    
            $this->db->query($sql);
        }
        return $id_toko;
    }

    public function insertProducts($products,$id_toko)
    {
        foreach ($products as $product) {
            $this->insertProduct($product,$id_toko);
        }
    }

    public function insertProduct($data,$id_toko)
    {
        
        $getLastIdProduct = "SELECT * FROM produk ORDER BY id_produk DESC LIMIT 1";
        $idProduct = $this->db->query($getLastIdProduct);
        $resultIdProduct = $idProduct->fetchAll(PDO::FETCH_ASSOC);

        $id_produk = $resultIdProduct[0]['id_produk'];

        if($resultIdProduct == NULL){
            $id_produk = 1;
            $deskripsi = trim(preg_replace("/'/", " ",$data['deskripsi']));
            $keterangan = trim(preg_replace("/'/", " ",$data['keterangan']));
            $sql = "INSERT INTO `produk` (
                `id_produk`, 
                `id_toko`, 
                `nama`,
                `deskripsi`,
                `keterangan`
                ) VALUES (
                    $id_produk,
                    $id_toko,
                    '$data[nama]',
                    '". $deskripsi ."',
                    '". $keterangan ."'
                )"; 
            $this->db->query($sql);
        }else{
            $id_produk = $resultIdProduct[0]['id_produk'] + 1;
            $sql = "INSERT INTO `produk` (
                `id_produk`, 
                `id_toko`, 
                `nama`,
                `deskripsi`,
                `keterangan`
                ) VALUES (
                    $id_produk,
                    $id_toko,
                    '$data[nama]',
                    '". $deskripsi ."',
                    '". $keterangan ."'
                )";
            $this->db->query($sql);
        }
        
        $getLastDataProduct = "SELECT * FROM data_produk ORDER BY id_data_produk DESC LIMIT 1";
        $idDataProduct = $this->db->query($getLastDataProduct);
        $resultDataIdProduct = $idDataProduct->fetchAll(PDO::FETCH_ASSOC);

        $id_data_produk = $resultDataIdProduct[0]['id_data_produk'];

        if($resultDataIdProduct == NULL){
            $id_data_produk = 1;

            $sql = "INSERT INTO `data_produk`(
                `id_data_produk`, 
                `id_produk`, 
                `tgl_crawl`,
                `rating`, 
                `jml_review`, 
                `diskon`, 
                `harga`, 
                `jml_barang`, 
                `kondisi`, 
                `jml_view`,
                `waktu_proses`,
                `jml_favorit`
                ) VALUES (
                    $id_data_produk,
                    $id_produk,
                    '$data[tanggal_crawl]',
                    '$data[rating]',
                    $data[jumlah_review],
                    '$data[diskon]',
                    '$data[harga]',
                    $data[jumlah_barang],
                    '$data[kondisi_barang]',
                    $data[jumlah_view],
                    '$data[waktu_proses]',
                    $data[jumlah_favorit]
                )";
        
            $this->db->query($sql);
            // var_dump($sql);
        }else{
            $id_data_produk = $resultDataIdProduct[0]['id_data_produk'] + 1;

            $sql = "INSERT INTO `data_produk`(
                `id_data_produk`, 
                `id_produk`, 
                `tgl_crawl`,
                `rating`, 
                `jml_review`, 
                `diskon`, 
                `harga`, 
                `jml_barang`, 
                `kondisi`, 
                `jml_view`,
                `waktu_proses`,
                `jml_favorit`
                ) VALUES (
                    $id_data_produk,
                    $id_produk,
                    '$data[tanggal_crawl]',
                    '$data[rating]',
                    '$data[jumlah_review]',
                    '$data[diskon]',
                    '$data[harga]',
                    '$data[jumlah_barang]',
                    '$data[kondisi_barang]',
                    '$data[jumlah_view]',
                    '$data[waktu_proses]',
                    '$data[jumlah_favorit]'
                )";
        
            $this->db->query($sql);

        }
        

    }
}
