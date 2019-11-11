-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Nov 05, 2019 at 12:51 AM
-- Server version: 5.7.23
-- PHP Version: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `esmart`
--

-- --------------------------------------------------------

--
-- Table structure for table `data_produk`
--

CREATE TABLE `data_produk` (
  `id_data_produk` int(11) NOT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `tgl_crawl` varchar(20) DEFAULT NULL,
  `rating` varchar(50) DEFAULT NULL,
  `jml_review` int(11) DEFAULT NULL,
  `diskon` varchar(50) DEFAULT NULL,
  `harga` varchar(50) DEFAULT NULL,
  `jml_barang` int(11) DEFAULT NULL,
  `kondisi` varchar(50) DEFAULT NULL,
  `jml_terjual` int(11) DEFAULT NULL,
  `jml_view` int(11) DEFAULT NULL,
  `waktu_proses` varchar(50) DEFAULT NULL,
  `jml_favorit` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `data_produk`
--

INSERT INTO `data_produk` (`id_data_produk`, `id_produk`, `tgl_crawl`, `rating`, `jml_review`, `diskon`, `harga`, `jml_barang`, `kondisi`, `jml_terjual`, `jml_view`, `waktu_proses`, `jml_favorit`) VALUES
(1, 1, '2019-11-5 7:48:3', '98.0%', 298, 'NULL', '29.000', 200, 'BARU', NULL, 27433, '± 14 jam ', 732),
(2, 2, '2019-11-5 7:48:13', '96.0%', 57, 'NULL', '37.500', 10, 'BARU', NULL, 8617, '± 14 jam ', 243);

-- --------------------------------------------------------

--
-- Table structure for table `foto_produk`
--

CREATE TABLE `foto_produk` (
  `id_foto_produk` int(11) NOT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `nama_file` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `id_parent` int(11) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `kurir`
--

CREATE TABLE `kurir` (
  `id_kurir` int(11) NOT NULL,
  `nama` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `kurir_produk`
--

CREATE TABLE `kurir_produk` (
  `id_kurir_produk` int(11) NOT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `id_kurir` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

CREATE TABLE `links` (
  `id` int(11) NOT NULL,
  `id_marketplace` int(11) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `type` enum('home','category','etalase','detail','shop') DEFAULT NULL,
  `is_download` enum('0','1') DEFAULT '0',
  `is_parse` enum('0','1') DEFAULT '0',
  `is_mapping` enum('0','1') DEFAULT '0',
  `is_upload` enum('0','1') DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `marketplace`
--

CREATE TABLE `marketplace` (
  `id_marketplace` int(11) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `keterangan` text,
  `tgl_input` datetime DEFAULT NULL,
  `inputer` varchar(18) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `marketplace`
--

INSERT INTO `marketplace` (`id_marketplace`, `nama`, `keterangan`, `tgl_input`, `inputer`) VALUES
(1, 'Bukalapak', NULL, '2019-10-25 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `perusahaan`
--

CREATE TABLE `perusahaan` (
  `id_perusahaan` int(11) NOT NULL,
  `id_marketplace` int(11) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `nama_toko` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `no_telp` varchar(15) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `tgl_daftar` varchar(20) DEFAULT NULL,
  `jml_view` int(11) DEFAULT NULL,
  `terakhir_login` datetime DEFAULT NULL,
  `status` enum('aktif','tidak aktif') DEFAULT NULL,
  `tgl_input` datetime DEFAULT NULL,
  `inputer` varchar(18) DEFAULT NULL,
  `kd_kota` int(11) DEFAULT NULL,
  `kd_provinsi` int(11) DEFAULT NULL,
  `lvl_reputasi` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `perusahaan`
--

INSERT INTO `perusahaan` (`id_perusahaan`, `id_marketplace`, `nama`, `nama_toko`, `email`, `no_telp`, `keterangan`, `alamat`, `foto`, `tgl_daftar`, `jml_view`, `terakhir_login`, `status`, `tgl_input`, `inputer`, `kd_kota`, `kd_provinsi`, `lvl_reputasi`) VALUES
(1, 1, 'A16COM', 'A16COM', NULL, NULL, '\n      \"UNTUK JADWAL PIK UP SILAKAN DI BACA CATATAN PELAPAK YA DI SANA SUDAH KAMI CANTUMKAN JADWAL N KETENTUAN OLDER,BUDAYAKAN MEMBACA\"\n    ', 'Bandung', 'https://s2.bukalapak.com/avt/26599131/small/logo.jpg', '23 April 2013', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(11) NOT NULL,
  `id_toko` int(11) DEFAULT NULL,
  `id_kategori` int(11) DEFAULT NULL,
  `kategori_marketplace` varchar(255) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `deskripsi` text,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `id_toko`, `id_kategori`, `kategori_marketplace`, `nama`, `deskripsi`, `keterangan`) VALUES
(1, 1, NULL, NULL, 'EARPHONE VIDO/WINDOW (EARPHONENYA KERE-HORE)', '#B167\n\"READY STOK\"\n*warna biru metalik kabel hitam (HABIS)\n*warna putih kabel putih\n*NON MIC (tampa mic)\n*\n\n\nYANG LAGI NYARI EARPHONE MURAH MERIAH TAPI KUALITAS YAHUD....\nWAKTUNYA NYOBAIN NIH EARPHONE VIDO YANG DULU NAMANYA WINDOW BERUBAH JADI VIDO TAPI KUALITAS TETEP SAMA GAN NGEBAS BANGET TAPI SUARA JELAS ENGGA KETUTUPAN BAS,JANGAN LIAT BENTUKNYA GAN BEKNTUKNYA SIH PASTI YAKIN BAKAL NIPU AGAN-AGAN TP KUALITAS YANG DI HASILKANYA BRO MANYOS.... UNTUK LENGKAPNYA AGAN-AGAN BISA CARI DI GOOGLE TENTANG KUALITAS NIH EARPHONE / CARI DI FORUM KERE-HORE\n\nCOCOK BANGET BUAT YG EARPHONE HP ORI ASLINYA ILANG/RUSAK\nHP BB/SAMSUNG/IPOD/IPHONE/DLL G ASIK KAN KALO HPNYA YAHUD TP DENGER MUSIK PAKE EARPHONE YANG SUARANYA ENGGA ASIK\n\n\n\n\n\n\nADA SEDIKIT TAMBAHAN NIH DIKIT ANE COBA PASANGIN NIH EARPHONE VIDO DENGAN MP3 SANSA ANE TERNYATA SUARANYA JAUH LEBIH BAIK PAKE EARPHONE VIDO KETIMBANG PAKE EARPHONE BAWAAN SANSA,SEMUA JENIS MUSIK ANE COBA ROOK,POP,JES,AKUSTIK,TERMASUK DANGDUT', 'KIRIMAN (YANG MO PAKE POS BACA DULU YA GAN )\nKiriman yang mengunakan POS max olderan masuk jam 14:00 (jam 2 siang) dari senin-jum at untuk olderan saptu/minggu akan dikirim hari senin,olderan di atas jam 2 siang dikirim hari berikutnya,kiriman POS tidak menutup kemungkinan dikirim +1hari dari olderan masuk.,dengan memilih kulir POS kami anggap sudah MENGETAHUI DAN MENSETUJUI,ketentuan older,sblm older silakan di baca dulu,older kami anggap sudah membaca & mensetujui,SEBELUM OLDER TOLONG DI PM DULU UNTUK MENANYAKAN STOK BARANG,JIKA BARANG DINYATAKAN READY STOK SILAKAN DI OLDE\",OLDER= NO CANSEL (jadi dipikirkan terlebih dahulu sblm older),JADWAL PENGIRIMAN,SENIN-JUMAT,JNE,J&T jam 16:00\njam 14:00\nPOS :jam 14:00,GOSEN/GRAB jam 15:00 (pik-up jam 15:30),untuk olderan yang terverifikasi di atas jam 15:30 kami kirim hari berikutnya 15:30 (dikarnakan gudang kami ada 2,jika barang ada di gudang 1,kami usahakan secepatnya,jika barang ada gudang 2 kami kirim jam 15:30 dikarnakan jarak yang jauh pegawe kami baru brangkat ke gudang 2 jam 15:00 untuk ambil badang di gudang2,agar tidak bulak-balik.),SAPTU,JNE,J&T jam 14:00,GOSEN/GRAB jam 14:00,(pik-up jam 15:30) untuk olderan yang terverifikasi di atas jam 15:30 kami kirim hari berikutnya 15:30 (dikarnakan gudang kami ada 2,jika barang ada di gudang 1,kami usahakan secepatnya,jika barang ada gudang 2 kami kirim jam 15:30 dikarnakan jarak yang jauh pegawe kami baru brangkat ke gudang 2 jam 15:00 untuk ambil badang di gudang2,agar tidak bulak-balik.),*untuk yang older dengan GOSEND/GRAB kami anggap sudah mengetahui tatacaranya yaitu tidak usah membayar ongkos kirim pada draiver,cz ongkos kirim sudah otomatis di bayarkan BUKALAPAK pada draiver dengan metode GOPAY/GRABPAY,jika draiver miminta ongkir anda bisa menjelaskanya pada draiver (kami tidak melayani keluhan draiver minta ongkoskirim disini sudah kami jelaskan),*Kiriman yang mengunakan max olderan masuk jam 14:00 (jam 2 siang) dari senin-jum at untuk olderan saptu/minggu akan dikirim hari senin,olderan di atas jam 2 siang dikirim hari berikutnya,kiriman tidak menutup kemungkinan dikirim +1hari dari olderan masuk.,dengan memilih kulir kami anggap sudah MENGETAHUI DAN MENSETUJUI,*DAFTAR BLACKLIS KONSUMEN,bagi konsumen yang memberikan penilayan 1 bintang/feedback negatif/logo cemberut ,otomatis masuk daftar BLACKLIS toko kami,garansi produk otomatis hangus,serta tidak akan kami layani dalam nex older,otomatis olderan anda akan kami CANSEL,MINGGU/TANGGAL MERAH LIBUR TIDAK ADA PENGIRIMAN,pengiriman kami usahkan dihari yang sama anda older jika waktu transaksi terverifikasi seperti yang telah dijelaskan di atas,paling telat keesokan harinya (namun hal ini sangat jarang terjadi jika bukan karna hal yang dalurat dan ada hal yang mendesak),pertanyaan stok/pm stok akan kami balas mulai dr jam 11siang-02 dini hari (jika ada pertanyaan blm dijawab mohon kesabaranya mungkin sedang tidur dulu/sibuk,harap dimaklumi admin juga masih manusia),kecepatan paket sampai kecepatan paket sampai tergantung expedisi & layanan yang anda pilih,jadi tolong dipirkan dulu dan dipertimbangkan dlm memilih expedisi yang akan dipilih,karna kecepatan paket sampai diluar wilayah kerja kami. untuk mempercepat pengiriman silakan masukan alamat sejelas mungkin dan patokan agar tidak terjadi alamat tidak lengkap/penerima tidak di kenal,No resi no resi akan kami up-date pada mlm hari mulai jam 21:00-24:00 (jadi blm ada no resi bukan brti blm dikirim,cz kami mengunakan pik-up expedisi agen langganan kami jadi pik up pengambilan barang sesuai jadwal)pertanyaan kapan barang dikirim/minta no resi kemungkinan besar tidak akan admin jawab karna sudah dijelaskan disini harap dimaklum cz admin juga blm tau berapa no resinya,yang pasti kami tidak akan memperlambat olderan dikirim cz semakin cepat barang diterima konsumen semakin cepat uang cair.,pengantian expedisi pengantian expedisi yang anda pilih deengan expedisi lain mungkin jika terjadi beberapa hal,misalkan ongkir tidak sesuai misalkan anda pilih expedisi A ongkir 10rb ternyata setelah admin cek ongkir ongkir expedisi A 15rb kemungkinan admin akan ganti dengan expedisi yang lain yang ongkirnya 10rb/menganti jenis layanan deengan yang sesuai ongkirnya (biasanya hal ini terjdi karna konsumen yang nakal,alamat di pedesaan di masukan kota dengan maksud hemat ongkir,kami sarankan tidak melakukan hal ini karna kmungkinan besar paket akan lama sampai/paket tidak sampai dan konsumen disuruh ambil ke pusat oleh pihak expedisi.,pergantian expedisi juga bisa terjadi jika pihak expedisi yang dipilih tidak bisa pik-up pada hari itu,misal pilih expedisi A teernyata hari itu expedisi A tidak bisa pik-up maka akan kami pik-up ke expedisi B dengan pertimbangan kecepatan sampai yang sama/setara dengan expedisi A,,COD : TIDAK MENERIMA COD,CUMAN KIRIM-KIRIM MENGUNAKAN EXPEDISI/GOSEN (untuk meng Efisiensiksn waktu),*UNTUK BARANG YANG DIRETUR/DIKEMBALIKAN ONGKOS KIRIM BALIK 100% DI TANGGUNG OLEH PEMBELI(apapun alasanya),TERKECUALI UNTUK BARANG YANG SALAH KIRIM OLEH PENJUAL ONGKIR BALIK DI TANGGUNG OLEH PENJUAL'),
(2, 1, NULL, NULL, 'Earbud SHARP MX300 MD (Bass Enak Harga Murah,Recomended)', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `id_review` int(11) NOT NULL,
  `id_product` int(11) DEFAULT NULL,
  `uraian` text,
  `tgl_review` datetime DEFAULT NULL,
  `rating` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tag_perusahaan`
--

CREATE TABLE `tag_perusahaan` (
  `id_tag_perusahaan` int(11) NOT NULL,
  `id_marketplace` int(11) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `nama_toko` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `tgl_daftar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tag_produk`
--

CREATE TABLE `tag_produk` (
  `id_tag_produk` int(11) NOT NULL,
  `id_marketplace` int(11) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `rating` varchar(255) DEFAULT NULL,
  `jml_review` varchar(255) DEFAULT NULL,
  `diskon` varchar(255) DEFAULT NULL,
  `harga` varchar(255) DEFAULT NULL,
  `jml_barang` varchar(255) DEFAULT NULL,
  `kondisi` varchar(255) DEFAULT NULL,
  `jml_terjual` varchar(255) DEFAULT NULL,
  `jml_view` varchar(255) DEFAULT NULL,
  `waktu_proses` varchar(255) DEFAULT NULL,
  `jml_favorit` varchar(255) DEFAULT NULL,
  `kategori_marketplace` varchar(255) DEFAULT NULL,
  `deskripsi` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `nama_kurir` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `toko`
--

CREATE TABLE `toko` (
  `id_toko` int(11) NOT NULL,
  `id_perusahaan` int(11) DEFAULT NULL,
  `id_marketplace` int(11) DEFAULT NULL,
  `url` varchar(45) DEFAULT NULL,
  `tgl_daftar` varchar(20) DEFAULT NULL,
  `tgl_input` datetime DEFAULT NULL,
  `inputer` varchar(18) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `toko`
--

INSERT INTO `toko` (`id_toko`, `id_perusahaan`, `id_marketplace`, `url`, `tgl_daftar`, `tgl_input`, `inputer`) VALUES
(1, 1, 1, 'https://www.bukalapak.com/u/rikky', '23 April 2013', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `data_produk`
--
ALTER TABLE `data_produk`
  ADD PRIMARY KEY (`id_data_produk`),
  ADD KEY `fk_data_produk_produk_idx` (`id_produk`);

--
-- Indexes for table `foto_produk`
--
ALTER TABLE `foto_produk`
  ADD PRIMARY KEY (`id_foto_produk`),
  ADD KEY `fk_foto_produk_produk_idx` (`id_produk`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `kurir`
--
ALTER TABLE `kurir`
  ADD PRIMARY KEY (`id_kurir`);

--
-- Indexes for table `kurir_produk`
--
ALTER TABLE `kurir_produk`
  ADD PRIMARY KEY (`id_kurir_produk`),
  ADD KEY `fk_kurir_produk_produk_idx` (`id_produk`),
  ADD KEY `fk_kurir_produk_kurir_idx` (`id_kurir`);

--
-- Indexes for table `links`
--
ALTER TABLE `links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_marketplaces_links_idx` (`id_marketplace`);

--
-- Indexes for table `marketplace`
--
ALTER TABLE `marketplace`
  ADD PRIMARY KEY (`id_marketplace`);

--
-- Indexes for table `perusahaan`
--
ALTER TABLE `perusahaan`
  ADD PRIMARY KEY (`id_perusahaan`),
  ADD KEY `index_sellers` (`nama`,`nama_toko`),
  ADD KEY `fk_marketplaces_sellers_idx` (`id_marketplace`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`),
  ADD KEY `index_products` (`nama`),
  ADD KEY `fk_produk_toko_idx` (`id_toko`),
  ADD KEY `fk_produk_kategori_idx` (`id_kategori`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id_review`),
  ADD KEY `fk_review_produk_idx` (`id_product`);

--
-- Indexes for table `tag_perusahaan`
--
ALTER TABLE `tag_perusahaan`
  ADD PRIMARY KEY (`id_tag_perusahaan`),
  ADD KEY `fk_marketplace_product_tags_idx` (`id_marketplace`);

--
-- Indexes for table `tag_produk`
--
ALTER TABLE `tag_produk`
  ADD PRIMARY KEY (`id_tag_produk`),
  ADD KEY `fk_marketplaces_seller_tags_idx` (`id_marketplace`);

--
-- Indexes for table `toko`
--
ALTER TABLE `toko`
  ADD PRIMARY KEY (`id_toko`),
  ADD KEY `fk_toko_perusahaan_idx` (`id_perusahaan`),
  ADD KEY `fk_toko_marketplace_idx` (`id_marketplace`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `marketplace`
--
ALTER TABLE `marketplace`
  MODIFY `id_marketplace` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `data_produk`
--
ALTER TABLE `data_produk`
  ADD CONSTRAINT `fk_data_produk_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `foto_produk`
--
ALTER TABLE `foto_produk`
  ADD CONSTRAINT `fk_foto_produk_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `kurir_produk`
--
ALTER TABLE `kurir_produk`
  ADD CONSTRAINT `fk_kurir_produk_kurir` FOREIGN KEY (`id_kurir`) REFERENCES `kurir` (`id_kurir`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_kurir_produk_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `links`
--
ALTER TABLE `links`
  ADD CONSTRAINT `fk_marketplaces_links` FOREIGN KEY (`id_marketplace`) REFERENCES `marketplace` (`id_marketplace`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `fk_produk_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_produk_toko` FOREIGN KEY (`id_toko`) REFERENCES `toko` (`id_toko`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `fk_review_produk` FOREIGN KEY (`id_product`) REFERENCES `produk` (`id_produk`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tag_perusahaan`
--
ALTER TABLE `tag_perusahaan`
  ADD CONSTRAINT `fk_marketplaces_product_tags` FOREIGN KEY (`id_marketplace`) REFERENCES `marketplace` (`id_marketplace`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tag_produk`
--
ALTER TABLE `tag_produk`
  ADD CONSTRAINT `fk_marketplaces_seller_tags` FOREIGN KEY (`id_marketplace`) REFERENCES `marketplace` (`id_marketplace`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `toko`
--
ALTER TABLE `toko`
  ADD CONSTRAINT `fk_toko_marketplace` FOREIGN KEY (`id_marketplace`) REFERENCES `marketplace` (`id_marketplace`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_toko_perusahaan` FOREIGN KEY (`id_perusahaan`) REFERENCES `perusahaan` (`id_perusahaan`) ON DELETE NO ACTION ON UPDATE NO ACTION;
