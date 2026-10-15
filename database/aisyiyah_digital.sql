-- ========================================================
-- Database: aisyiyah_jateng
-- Website: Aisyiyah Digital Jawa Tengah
-- ========================================================

CREATE DATABASE IF NOT EXISTS `aisyiyah_digital` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `aisyiyah_digital`;

-- --------------------------------------------------------
-- 1. Tabel: admins
-- Fungsi: Menyimpan data akun pengelola website (Admin)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admins` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `nama_lengkap` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- 2. Tabel: kategori
-- Fungsi: Menyimpan pengelompokan untuk artikel, pustaka, atau kegiatan
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `kategori` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_kategori` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `tipe` ENUM('artikel', 'pustaka', 'kegiatan') DEFAULT 'artikel',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- 3. Tabel: tokoh
-- Fungsi: Menyimpan profil tokoh-tokoh pejuang & pimpinan Aisyiyah
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tokoh` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama` VARCHAR(150) NOT NULL,
  `peran_jabatan` VARCHAR(150) DEFAULT NULL,
  `tempat_lahir` VARCHAR(100) DEFAULT NULL,
  `tanggal_lahir` DATE DEFAULT NULL,
  `wafat` VARCHAR(100) DEFAULT '-',
  `biografi` TEXT NOT NULL,
  `jasa_perjuangan` TEXT DEFAULT NULL,
  `foto` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- 4. Tabel: sejarah
-- Fungsi: Menyimpan tonggak sejarah dan narasi timeline masa ke masa
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `sejarah` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `judul` VARCHAR(200) NOT NULL,
  `tahun` VARCHAR(20) NOT NULL,
  `deskripsi_singkat` TEXT NOT NULL,
  `konten_lengkap` TEXT DEFAULT NULL,
  `foto` VARCHAR(255) DEFAULT NULL,
  `urutan` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- 5. Tabel: kegiatan
-- Fungsi: Menyimpan agenda acara, kegiatan dakwah, & musyawarah
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `kegiatan` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_kegiatan` VARCHAR(200) NOT NULL,
  `deskripsi` TEXT NOT NULL,
  `tanggal_mulai` DATE NOT NULL,
  `tanggal_selesai` DATE DEFAULT NULL,
  `lokasi` VARCHAR(255) NOT NULL,
  `penyelenggara` VARCHAR(150) DEFAULT 'Pimpinan Wilayah Aisyiyah Jawa Tengah',
  `gambar` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('akan_datang', 'berlangsung', 'selesai') DEFAULT 'akan_datang',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- 6. Tabel: artikel
-- Fungsi: Menyimpan berita, tulisan, dan opini publik
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `artikel` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `kategori_id` INT DEFAULT NULL,
  `admin_id` INT DEFAULT NULL,
  `judul` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `isi` LONGTEXT NOT NULL,
  `gambar` VARCHAR(255) DEFAULT NULL,
  `views` INT DEFAULT 0,
  `status` ENUM('draft', 'published') DEFAULT 'published',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_artikel_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_artikel_admin` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- 7. Tabel: pustaka
-- Fungsi: Menyimpan katalog arsip digital (buku, dokumen, PDF)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `pustaka` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `kategori_id` INT DEFAULT NULL,
  `judul` VARCHAR(255) NOT NULL,
  `penulis` VARCHAR(150) DEFAULT NULL,
  `tahun_terbit` VARCHAR(10) DEFAULT NULL,
  `deskripsi` TEXT DEFAULT NULL,
  `cover` VARCHAR(255) DEFAULT NULL,
  `file_dokumen` VARCHAR(255) NOT NULL,
  `download_count` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_pustaka_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- 8. Tabel: galeri
-- Fungsi: Menyimpan koleksi foto kegiatan dakwah & pemberdayaan
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `galeri` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `kegiatan_id` INT DEFAULT NULL,
  `judul` VARCHAR(200) NOT NULL,
  `deskripsi` TEXT DEFAULT NULL,
  `foto` VARCHAR(255) NOT NULL,
  `tanggal_foto` DATE DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_galeri_kegiatan` FOREIGN KEY (`kegiatan_id`) REFERENCES `kegiatan` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================================================
-- DATA AWAL (SEEDER / SAMPLE DATA)
-- ========================================================

-- Akun Default Admin:
-- Username: admin
-- Password: password hash untuk 'admin123'
INSERT INTO `admins` (`id`, `username`, `password`, `nama_lengkap`, `email`) VALUES
(1, 'admin', '$2y$10$Tex8w1YTIAF1Sg3dHhy8ceR6jGKBy6iQREL2/Pk13I.GikwoXrG2.', 'Administrator PWA Jateng', 'admin@aisyiyah-jateng.or.id')
ON DUPLICATE KEY UPDATE `username`=`username`;

-- Kategori Awal
INSERT INTO `kategori` (`id`, `nama_kategori`, `slug`, `tipe`) VALUES
(1, 'Berita & Kegiatan', 'berita-kegiatan', 'artikel'),
(2, 'Opini & Pemikiran', 'opini-pemikiran', 'artikel'),
(3, 'Pendidikan & Dakwah', 'pendidikan-dakwah', 'artikel'),
(4, 'Buku Panduan', 'buku-panduan', 'pustaka'),
(5, 'Surat Keputusan & AD-ART', 'sk-ad-art', 'pustaka'),
(6, 'Riset & Jurnal', 'riset-jurnal', 'pustaka')
ON DUPLICATE KEY UPDATE `slug`=`slug`;

-- Data Awal Sejarah & Timeline
INSERT INTO `sejarah` (`id`, `judul`, `tahun`, `deskripsi_singkat`, `konten_lengkap`, `urutan`) VALUES
(1, 'Kelahiran Gerakan Perempuan Sapa Tresno', '1914', 'Awal mula perkumpulan perempuan di Kauman Yogyakarta yang menjadi cikal bakal berdirinya Aisyiyah.', 'Sapa Tresno dibimbing langsung oleh KH. Ahmad Dahlan dan Nyai Ahmad Dahlan untuk meningkatkan harkat dan martabat wanita muslimah melalui pendidikan dan dakwah Islam.', 1),
(2, 'Peresmian Nama Aisyiyah', '1917', 'Pengesahan dan peresmian nama perkumpulan Aisyiyah bertepatan dengan 27 Rajab 1335 H.', 'Nama Aisyiyah dinisbatkan kepada Siti Aisyah r.a., istri Nabi Muhammad SAW, dengan harapan agar gerakan perempuan ini dapat meneladani kecerdasan, ketakwaan, dan keberanian beliau.', 2),
(3, 'Rintisan Cabang Aisyiyah Pertama di Jawa Tengah', '1923', 'Aisyiyah mulai melebarkan sayap dakwah ke wilayah Jawa Tengah seperti Surakarta, Pekalongan, dan Kudus.', 'Gerakan perempuan berkemajuan meluas ke berbagai karesidenan di Jawa Tengah melalui pendirian sekolah kader, pengajian wanita, dan pertolongan sosial kesehatan.', 3),
(4, 'Muktamar Pertama di Jawa Tengah (Surakarta)', '1929', 'Surakarta menjadi tuan rumah Kongres Muhammadiyah & Aisyiyah yang bersejarah.', 'Pertemuan akbar ini semakin memperkuat konsolidasi organisasi di seluruh wilayah Jawa Tengah dan memantapkan peran wanita dalam ranah pendidikan publik.', 4),
(5, 'Era Kepemimpinan Wilayah Modern', '2022', 'Musyawarah Wilayah (Musywil) Aisyiyah Jawa Tengah meneguhkan komitmen gerakan perempuan berkemajuan.', 'PWA Jawa Tengah terus memperluas jejaring ribuan ranting, cabang, dan daerah di 35 kabupaten/kota, membina ratusan TK ABA, RS/Klinik PKU, serta panti asuhan.', 5)
ON DUPLICATE KEY UPDATE `id`=`id`;

-- Data Awal Tokoh
INSERT INTO `tokoh` (`id`, `nama`, `peran_jabatan`, `tempat_lahir`, `tanggal_lahir`, `wafat`, `biografi`, `jasa_perjuangan`, `foto`) VALUES
(1, 'Nyai Hj. Ahmad Dahlan (Siti Walidah)', 'Pendiri Aisyiyah & Pahlawan Nasional', 'Yogyakarta', '1872-01-03', 'Yogyakarta, 31 Mei 1946', 'Nyai Siti Walidah adalah tokoh emansipasi perempuan muslim yang mendirikan perkumpulan Sapa Tresno dan memimpin Aisyiyah pada masa awal. Beliau menginspirasi berdirinya cabang-cabang Aisyiyah di tanah Jawa termasuk Jawa Tengah.', 'Mendirikan sekolah asrama putri (Kweekschool Aisyiyah), menggerakkan buta aksara perempuan, dan memimpin gerakan ketahanan pangan di masa perang kemerdekaan.', 'nyai-dahlan.jpg'),
(2, 'Hj. Siti Hayinah Mawardi', 'Tokoh Kongres Perempuan 1928 & Penggerak Aisyiyah', 'Yogyakarta', '1906-05-10', '1996', 'Siti Hayinah adalah salah satu orator muda Aisyiyah dalam Kongres Perempuan Indonesia Pertama tahun 1928. Aktif membina gerakan Aisyiyah di berbagai karesidenan Jawa Tengah.', 'Memperjuangkan hak pendidikan kaum wanita, reformasi hukum perkawinan perlindungan anak dan perempuan, serta penerbitan majalah Suara Aisyiyah.', 'siti-hayinah.jpg')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- Data Awal Artikel
INSERT INTO `artikel` (`id`, `kategori_id`, `admin_id`, `judul`, `slug`, `isi`, `gambar`, `views`, `status`) VALUES
(1, 1, 1, 'Musyawarah Pimpinan Wilayah Aisyiyah Jawa Tengah Teguhkan Dakwah Berkemajuan', 'musyawarah-pimpinan-wilayah-aisyiyah-jawa-tengah-teguhkan-dakwah-berkemajuan', '<p>Pimpinan Wilayah Aisyiyah (PWA) Jawa Tengah menggelar agenda strategis dalam rangka memperkuat konsolidasi kelembagaan di seluruh daerah. Kegiatan ini menekankan integrasi dakwah di bidang pendidikan anak usia dini, pemberdayaan ekonomi perempuan, dan layanan kesehatan masyarakat.</p><p>Gerakan perempuan berkemajuan Aisyiyah terus beradaptasi dengan era transformasi digital tanpa meninggalkan nilai-nilai akhlakul karimah dan keislaman yang autentik.</p>', 'musywil-jateng.jpg', 125, 'published'),
(2, 3, 1, 'Peran PAUD & TK ABA di Jawa Tengah dalam Membentuk Karakter Anak', 'peran-paud-tk-aba-di-jawa-tengah-dalam-membentuk-karakter-anak', '<p>Taman Kanak-kanak Aisyiyah Bustanul Athfal (TK ABA) telah menjadi ikon pendidikan usia dini di Jawa Tengah selama puluhan tahun. Pendekatan pembelajaran yang ramah anak, berakar pada akidah yang lurus serta adab mulia, terus melahirkan generasi unggul yang cerdas dan mandiri.</p>', 'tk-aba.jpg', 84, 'published')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- Data Awal Kegiatan
INSERT INTO `kegiatan` (`id`, `nama_kegiatan`, `deskripsi`, `tanggal_mulai`, `tanggal_selesai`, `lokasi`, `penyelenggara`, `gambar`, `status`) VALUES
(1, 'Seminar Nasional Pemberdayaan Ekonomi Perempuan Berkemajuan', 'Pemberdayaan UMKM perempuan binaan Aisyiyah Jawa Tengah berbasis digitalisasi pemasaran.', '2026-10-15', '2026-10-16', 'Semarang (PWA Jawa Tengah)', 'Majelis Ekonomi PWA Jateng', 'seminar-ekonomi.jpg', 'akan_datang'),
(2, 'Bakti Sosial & Pemeriksaan Kesehatan Serentak', 'Pemeriksaan tensi, gula darah, dan edukasi stunting gratis oleh RS/Klinik Aisyiyah di 35 Kabupaten/Kota se-Jawa Tengah.', '2026-08-20', '2026-08-20', 'Klinik PKU & Balai Cabang se-Jateng', 'Majelis Kesehatan PWA Jateng', 'baksos-kesehatan.jpg', 'selesai')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- Data Awal Pustaka
INSERT INTO `pustaka` (`id`, `kategori_id`, `judul`, `penulis`, `tahun_terbit`, `deskripsi`, `cover`, `file_dokumen`, `download_count`) VALUES
(1, 4, 'Buku Panduan Kader Aisyiyah Abad Kedua', 'Pimpinan Pusat & PWA Jateng', '2023', 'Buku pegangan resmi mengenai sistem perkaderan dan pembinaan keanggotaan Aisyiyah.', 'buku-kader.jpg', 'panduan-kader-aisyiyah.pdf', 38),
(2, 5, 'Pedoman Hidup Islami Warga Muhammadiyah (PHIWM)', 'PP Muhammadiyah', '2020', 'Panduan nilai akhlak dan etika hidup bermasyarakat bagi warga persyarikatan.', 'phiwm.jpg', 'pedoman-phiwm.pdf', 72)
ON DUPLICATE KEY UPDATE `id`=`id`;

-- Data Awal Galeri
INSERT INTO `galeri` (`id`, `kegiatan_id`, `judul`, `deskripsi`, `foto`, `tanggal_foto`) VALUES
(1, 2, 'Pemeriksaan Kesehatan Gratis Warga Lansia', 'Dokumentasi tim medis Aisyiyah memeriksa kesehatan masyarakat di balai desa.', 'galeri-baksos-1.jpg', '2026-08-20'),
(2, 1, 'Pameran Produk Olahan Majelis Ekonomi PWA', 'Stand bazar produk kerajinan dan makanan sehat binaan kader ranting Jawa Tengah.', 'galeri-bazar-1.jpg', '2026-09-01')
ON DUPLICATE KEY UPDATE `id`=`id`;
