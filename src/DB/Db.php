<?php

class DB
{
    // private $db;

    public function __construct($host, $db, $user, $pass)
    {

        // $this->db = new PDO("mysql:host=".$host.";dbname=".$db, $user, $pass);
        try {
            $this->db = new PDO("mysql:host=" . $host . ";dbname=" . $db, $user, $pass);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }

    }
    public function getStore($storeName)
    {
        $store = "SELECT toko.id_toko, toko.id_perusahaan, toko.url, marketplace.nama
        FROM toko
        JOIN marketplace ON toko.id_marketplace = marketplace.id_marketplace
        WHERE marketplace.nama = '$storeName' LIMIT 2";
        $stores = $this->db->query($store);
        return $stores;
    }

    public function updateStore($storeInfo, $idPerusahaan)
    {
        // get kd_kota, kd_provinsi
        $keyword = "Kota " . $storeInfo['location'];
        $kodeAlamat = "SELECT *  FROM `kabupaten` WHERE `nama` LIKE '%$keyword%'";
        $resultIdAlamat = $this->db->query($kodeAlamat);
        $kd_alamat = $resultIdAlamat->fetch(PDO::FETCH_ASSOC);
        // var_dump($kd_kota);

        // change format join_date
        $tgl_daftar = $this->formatTanggal($storeInfo['join_date']);
        // var_dump($tgl_daftar);

        $updatePerusahaan = "UPDATE perusahaan SET
                            nama_toko='$storeInfo[shopname]',
                            keterangan='$storeInfo[description]',
                            alamat='$storeInfo[location]',
                            foto='$storeInfo[shop_image]',
                            tgl_daftar='$tgl_daftar',
                            kd_kota='$kd_alamat[kd_kota]',
                            kd_provinsi='$kd_alamat[kd_provinsi]',
                            lvl_reputasi='$storeInfo[lvl_reputasi]'
                            WHERE id_perusahaan = '$idPerusahaan'";

        $this->db->query($updatePerusahaan);
        // $tes = $this->db->query($updatePerusahaan);
        // var_dump($updatePerusahaan);

    }

    public function formatTanggal($tglDaftar)
    {
        $tgl_daftar = explode(' ', $tglDaftar);

        $month = [
            'Januari' => "01",
            'Februari' => "02",
            'Maret' => "03",
            'April' => "04",
            'Mei' => "05",
            'Juni' => "06",
            'Juli' => "07",
            'Agustus' => "08",
            'September' => "09",
            'Oktober' => "10",
            'November' => "11",
            'Desember' => "12",
        ];
        foreach ($month as $key => $months) {
            if ($tgl_daftar[1] == $key) {
                $date = (int) $tgl_daftar[0];
                $year = (int) $tgl_daftar[2];

                $tanggal_daftar = $year . "-" . $months . "-" . $date . " 00:00:00.000000";
                $format = date('Y-m-d H:i:s.u', strtotime($tanggal_daftar));
                return $format;
            }
            // var_dump($key);
            // die;
        }
    }

    // public function insertStore($data)
    // {
    //     // $create_id_perusahaan = substr(str_replace(".", "", microtime(true)), 10);
    //     // $id_perusahan = (int)$create_id_perusahaan;
    //     // $id_perusahan = "";

    //     $getid = "SELECT * FROM perusahaan ORDER BY id_perusahaan DESC LIMIT 1";
    //     $resultId = $this->db->query($getid);
    //     $resultsId = $resultId->fetchAll(PDO::FETCH_ASSOC);

    //     $id_perusahan = $resultsId[0]['id_perusahaan'];

    //     if($resultsId == NULL){
    //         $id_perusahan = 1;
    //         $sql = "INSERT INTO `perusahaan` (
    //             `id_perusahaan`,
    //             `id_marketplace`,
    //             `nama`,
    //             `nama_toko`,
    //             `keterangan`,
    //             `alamat`,
    //             `foto`,
    //             `tgl_daftar`
    //             ) VALUES (
    //                 $id_perusahan,
    //                 1,
    //                 '$data[name]',
    //                 '$data[shopname]',
    //                 '$data[description]',
    //                 '$data[location]',
    //                 '$data[shop_image]',
    //                 '$data[join_date]'
    //             )";
    //          $this->db->query($sql);
    //     }else{
    //         $id_perusahan = $resultsId[0]['id_perusahaan'] + 1;
    //         $sql = "INSERT INTO `perusahaan` (
    //             `id_perusahaan`,
    //             `id_marketplace`,
    //             `nama`,
    //             `nama_toko`,
    //             `keterangan`,
    //             `alamat`,
    //             `foto`,
    //             `tgl_daftar`
    //             ) VALUES (
    //                 $id_perusahan,
    //                 1,
    //                 '$data[name]',
    //                 '$data[shopname]',
    //                 '$data[description]',
    //                 '$data[location]',
    //                 '$data[shop_image]',
    //                 '$data[join_date]'
    //             )";
    //          $this->db->query($sql);

    //     }

    //     //    var_dump( $this->db->errorInfo());
    //     $getLastIdToko = "SELECT * FROM toko ORDER BY id_toko DESC LIMIT 1";
    //     $idToko = $this->db->query($getLastIdToko);
    //     $resultIdToko = $idToko->fetchAll(PDO::FETCH_ASSOC);

    //     $id_toko = $resultIdToko[0]['id_toko'];

    //     if($resultIdToko == NULL){
    //         $id_toko = 1;

    //         $sql = "INSERT INTO `toko`(
    //             `id_toko`,
    //             `id_perusahaan`,
    //             `id_marketplace`,
    //             `url`,
    //             `tgl_daftar`
    //             ) VALUES (
    //                 $id_toko,
    //                 $id_perusahan,
    //                 1,
    //                 '$data[url]',
    //                 '$data[join_date]'
    //             )";

    //         $this->db->query($sql);
    //     }else{
    //         $id_toko = $resultIdToko[0]['id_toko'] + 1;
    //         $sql = "INSERT INTO `toko`(
    //             `id_toko`,
    //             `id_perusahaan`,
    //             `id_marketplace`,
    //             `url`,
    //             `tgl_daftar`
    //             ) VALUES (
    //                 $id_toko,
    //                 $id_perusahan,
    //                 1,
    //                 '$data[url]',
    //                 '$data[join_date]'
    //             )";

    //         $this->db->query($sql);
    //     }
    //     return $id_toko;
    // }

    // public function insertProducts($products,$id_toko)
    // {
    //     foreach ($products as $product) {
    //         $this->insertProduct($product,$id_toko);
    //     }
    // }

    public function isExist($url)
    {
        $sql = "SELECT * FROM `links` WHERE link='".$url."'";

        return ($this->db->query($sql)->rowCount() > 0) ? true : false;
    }

    public function addToSavedLink($url)
    {
        $sql = "INSERT INTO `links`(
            `link`,
            `is_download`,
            `is_parse`,
            `is_mapping`
            ) VALUES (
                '".$url."',
                1,
                1,
                1
            )";

        $this->db->query($sql);
    }

    public function insertProduct($data, $id_toko)
    {
        $deskripsi = trim(preg_replace("/'/", " ", $data['deskripsi']));
        $keterangan = trim(preg_replace("/'/", " ", $data['keterangan']));

        $sql = "INSERT INTO `produk` (
                `id_toko`,
                `nama`,
                `deskripsi`,
                `keterangan`
                ) VALUES (
                    " . $id_toko . ",
                    '$data[nama]',
                    '" . $deskripsi . "',
                    '" . $keterangan . "'
                )";

        $this->db->query($sql);

        $id_product = $this->db->lastInsertId();
        $this->insertFotoProduct($data, $id_product);
        echo "produk ID " . $id_product . "\n";
        $kondisi_barang = strtolower($data['kondisi_barang']);
        $harga = (int)str_replace(".","", $data['harga']);
        $rating = (int)$data['rating'];
        
        $sql = "INSERT INTO `data_produk`(
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
                    $id_product,
                    '$data[tanggal_crawl]',
                    '$rating',
                    '$data[jumlah_review]',
                    '$data[diskon]',
                    '$harga',
                    '$data[jumlah_barang]',
                    '$kondisi_barang',
                    '$data[jumlah_view]',
                    '$data[waktu_proses]',
                    '$data[jumlah_favorit]'
                )";

        $this->db->query($sql);

        // $id_data_product = $this->db->lastInsertId();
    
        // echo "produk data ID " . $id_data_product . "\n";
    }
    public function insertFotoProduct($data, $id_product){
        $foto_product = $data['foto_produk'];
        $kurir_product = $data['kurir'];
        foreach($foto_product as $foto_products){
            $sql = "INSERT INTO `foto_produk` (  
                `id_produk`,
                `nama_file`
                ) VALUES (
                    $id_product,
                    '$foto_products'
                )";
            $this->db->query($sql);
            // var_dump($this->db->errorInfo());
        }

        foreach($kurir_product as $kurir_products){
            $sql = "SELECT *  FROM `kurir` WHERE `nama` LIKE '%$kurir_products%'";
            $courier = $this->db->query($sql);
            while($couriers = $courier->fetch(PDO::FETCH_ASSOC)){
                $this->insertKurirProduct($id_product, $couriers['id_kurir']);
            }
        }
    }
    public function insertKurirProduct($id_product, $id_kurir){
        $sql = "INSERT INTO `kurir_produk` (
            `id_produk`,
            `id_kurir`
            ) VALUES (
                $id_product,
                $id_kurir
            )";
        $this->db->query($sql);
    }
}
