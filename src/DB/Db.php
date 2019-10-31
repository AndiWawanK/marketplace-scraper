<?php

class DB
{
    private $db;

    public function __construct($host, $user, $pass, $db)
    {

        try {
            $this->db = new PDO("mysql:host=" . $host . ";dbname=" . $db, $user, $pass);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    public function insertStore($data)
    {
        $id_perusahan = substr(str_replace(".", "", microtime(true)), 8);

        $sql = "INSERT INTO `perusahaan`(
                 `id_perusahaan`,
                 `id_marketplace`,
                 `nama`,
                 `nama_toko`,
                 `keterangan`,
                 `alamat`,
                 `foto`,
                 `tgl_daftar`
                ) VALUES (
                    " . $id_perusahan . ",
                    1,
                    \"" . $data['name'] . "\",
                    \"" . $data['shopname'] . "\",
                    '" . $data['description'] . "',
                    \"" . $data['location'] . "\",
                    \"" . $data['shop_image'] . "\",
                    \"" . $data['join_date'] . "\"
                )";

        $this->db->query($sql);

        //    var_dump( $this->db->errorInfo());

        $id_toko = substr(str_replace(".", "", microtime(true)), 8);

        $sql = "INSERT INTO `toko`(
            `id_toko`,
            `id_perusahaan`,
            `id_marketplace`,
            `url`,
            `tgl_daftar`
            ) VALUES (
                " . $id_toko . ",
                " . $id_perusahan . ",
                1,
                \"" . $data['url'] . "\",
                \"" . $data['join_date'] . "\"
            )";

        $this->db->query($sql);

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
        
        $id_produk = substr(str_replace(".", "", microtime(true)), 8);

        $sql = "INSERT INTO `produk` (
                `id_produk`, 
                `id_toko`, 
                `nama`
            ) VALUES (
                ".$id_produk.",
                ".$id_toko.",
                '".$data['name']."'
            )";
        $this->db->query($sql);

        $id_data = substr(str_replace(".", "", microtime(true)), 8);

        $sql = "INSERT INTO `data_produk`(
                `id_data_produk`, 
                `id_produk`, 
                `rating`, 
                `jml_review`, 
                `diskon`, 
                `harga`, 
                `jml_barang`, 
                `jml_terjual`
            ) VALUES (
                ".$id_data.",
                ".$id_produk.",
                ".$data['rating'].",
                '".$data['review_count']."',
                '".$data['discount_price']."',
                '".$data['normal_price']."',
                '".$data['stock']."',
                '".$data['product_sold_count']."'
            )";
        
        $this->db->query($sql);

    }
}
