<?php
/**
 * Website: Aisyiyah Digital Jawa Tengah
 * Halaman: Pustaka Digital (pustaka.php)
 * Fungsi: Menampilkan katalog arsip digital, buku panduan, modul, dan dokumen riset dengan pencarian & filter kategori
 */

include 'config/database.php';

$page_title = "Pustaka Digital - Aisyiyah Digital Jawa Tengah";
include 'includes/header.php';
include 'includes/navbar.php';

// 1. Menangkap Parameter Pencarian dan Filter Kategori
$cari    = isset($_GET['cari']) ? trim($_GET['cari']) : '';
$kat_id  = isset($_GET['kategori']) ? intval($_GET['kategori']) : 0;

// 2. Menyusun Kondisi WHERE pada Kueri SQL
$where_clauses = [];

if (!empty($cari)) {
    $cari_safe = mysqli_real_escape_string($conn, $cari);
    $where_clauses[] = "(p.judul LIKE '%$cari_safe%' OR p.penulis LIKE '%$cari_safe%' OR p.deskripsi LIKE '%$cari_safe%')";
}

if ($kat_id > 0) {
    $where_clauses[] = "p.kategori_id = $kat_id";
}

$where_sql = !empty($where_clauses) ? "WHERE " . implode(" AND ", $where_clauses) : "";

// 3. Menjalankan Kueri Pengambilan Data Pustaka
$sql = "SELECT p.*, k.nama_kategori 
        FROM pustaka p 
        LEFT JOIN kategori k ON p.kategori_id = k.id 
        $where_sql 
        ORDER BY p.id DESC";
$query_pustaka = mysqli_query($conn, $sql);

// Mengambil Daftar Kategori Khusus Pustaka untuk Tombol Filter
$query_kategori = mysqli_query($conn, "SELECT DISTINCT k.id, k.nama_kategori 
                                       FROM kategori k 
                                       INNER JOIN pustaka p ON k.id = p.kategori_id 
                                       ORDER BY k.nama_kategori ASC");

// Menghitung total seluruh arsip di database
$total_semua_arsip = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM pustaka"))['total'] ?? 0;
?>

<!-- ================= 1. BANNER HEADER ================= -->
<section class="py-5 position-relative overflow-hidden" 
         style="background: linear-gradient(135deg, var(--primary-green-dark) 0%, var(--primary-green) 100%); color: #fff;">
    <div class="container position-relative py-3 text-center">
        <span class="badge bg-warning text-dark px-3 py-2 fw-bold mb-2">Khazanah Digital</span>
        <h1 class="text-white fw-bold mb-3 display-5">Pustaka & Arsip Digital Aisyiyah</h1>
        <p class="text-white-50 mx-auto mb-0" style="max-width: 720px; font-size: 1.1rem; line-height: 1.8;">
            Koleksi buku pedoman resmi, modul perkaderan, kajian historis, serta dokumen riset ilmiah gerakan perempuan berkemajuan di Jawa Tengah yang dapat diakses secara terbuka.
        </p>
    </div>
</section>

<!-- ================= 2. BREADCRUMB ================= -->
<div class="bg-white border-bottom py-3">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="index.php" class="text-success text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item active text-muted" aria-current="page">Pustaka Digital</li>
            </ol>
        </nav>
    </div>
</div>

<!-- ================= 3. BAR PENCARIAN & FILTER KATEGORI ================= -->
<section class="py-4" style="background-color: #f7f5ed; border-bottom: 1px solid var(--border-color);">
    <div class="container">
        <div class="row g-3 align-items-center justify-content-between">
            <!-- Form Input Pencarian -->
            <div class="col-lg-6">
                <form action="pustaka.php" method="GET" class="d-flex gap-2">
                    <?php if ($kat_id > 0) : ?>
                        <input type="hidden" name="kategori" value="<?= $kat_id ?>">
                    <?php endif; ?>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" name="cari" 
                               placeholder="Cari judul buku, penulis, atau kata kunci..." 
                               value="<?= htmlspecialchars($cari) ?>">
                        <button type="submit" class="btn btn-green px-4">Cari</button>
                    </div>
                    <?php if (!empty($cari) || $kat_id > 0) : ?>
                        <a href="pustaka.php" class="btn btn-outline-secondary d-flex align-items-center" title="Reset Filter">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Ringkasan Jumlah Koleksi -->
            <div class="col-lg-5 text-lg-end">
                <span class="badge bg-white text-dark border p-2 px-3 fw-semibold shadow-sm">
                    <i class="bi bi-book-half text-success me-1"></i>
                    Menampilkan: <strong><?= mysqli_num_rows($query_pustaka) ?></strong> dari total <?= $total_semua_arsip ?> Dokumen
                </span>
            </div>
        </div>

        <!-- Tombol Filter Kategori (Pills) -->
        <div class="d-flex flex-wrap gap-2 mt-3 pt-3 border-top border-secondary border-opacity-10 align-items-center">
            <span class="small fw-bold text-muted me-2"><i class="bi bi-funnel-fill text-warning me-1"></i>Kategori:</span>
            
            <a href="pustaka.php<?= !empty($cari) ? '?cari=' . urlencode($cari) : '' ?>" 
               class="btn btn-sm <?= ($kat_id === 0) ? 'btn-green' : 'btn-outline-secondary bg-white' ?> rounded-pill px-3">
                Semua Kategori
            </a>

            <?php while ($kat = mysqli_fetch_assoc($query_kategori)) : ?>
                <?php 
                $active = ($kat_id == $kat['id']) ? 'btn-green' : 'btn-outline-secondary bg-white';
                $url_kat = "pustaka.php?kategori=" . $kat['id'] . (!empty($cari) ? '&cari=' . urlencode($cari) : '');
                ?>
                <a href="<?= $url_kat ?>" class="btn btn-sm <?= $active ?> rounded-pill px-3">
                    <?= htmlspecialchars($kat['nama_kategori']) ?>
                </a>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- ================= 4. DAFTAR KATALOG PUSTAKA ================= -->
<section class="py-5">
    <div class="container">
        <!-- Notifikasi Pencarian Aktif -->
        <?php if (!empty($cari)) : ?>
            <div class="alert alert-light border d-flex justify-content-between align-items-center mb-4 py-2">
                <span>Hasil pencarian untuk kata kunci: <strong class="text-success">"<?= htmlspecialchars($cari) ?>"</strong></span>
                <a href="pustaka.php" class="small text-danger text-decoration-none">Hapus Pencarian</a>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <?php if (mysqli_num_rows($query_pustaka) > 0) : ?>
                <?php while ($doc = mysqli_fetch_assoc($query_pustaka)) : ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 border rounded-4 shadow-sm bg-white overflow-hidden d-flex flex-column" 
                             style="transition: transform 0.3s ease, box-shadow 0.3s ease;">
                            
                            <!-- Bagian Cover Dokumen -->
                            <div class="position-relative" style="height: 220px; background: linear-gradient(135deg, #0d472a 0%, #166534 100%);">
                                <?php if (!empty($doc['cover']) && file_exists('assets/uploads/pustaka/' . $doc['cover'])) : ?>
                                    <img src="assets/uploads/pustaka/<?= htmlspecialchars($doc['cover']) ?>" 
                                         alt="<?= htmlspecialchars($doc['judul']) ?>" 
                                         class="w-100 h-100" style="object-fit: cover;">
                                <?php else : ?>
                                    <!-- Ilustrasi Sampul Buku Khas Islami -->
                                    <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-center p-3 text-white">
                                        <div class="rounded-circle p-3 mb-2" style="background: rgba(197, 160, 89, 0.25); border: 2px solid var(--gold);">
                                            <i class="bi bi-journal-text fs-2 text-warning"></i>
                                        </div>
                                        <span class="small fw-bold text-white-50 text-uppercase" style="letter-spacing: 0.1em; font-size: 0.72rem;">Dokumen Digital</span>
                                    </div>
                                <?php endif; ?>

                                <!-- Badge Kategori di Pojok -->
                                <span class="badge position-absolute top-0 start-0 m-3 px-3 py-2 fw-semibold" 
                                      style="background-color: var(--gold); color: #fff; font-size: 0.75rem;">
                                    <i class="bi bi-tag-fill me-1"></i>
                                    <?= htmlspecialchars($doc['nama_kategori'] ?? 'Pustaka') ?>
                                </span>

                                <!-- Tahun Terbit -->
                                <?php if (!empty($doc['tahun_terbit'])) : ?>
                                    <span class="badge bg-dark bg-opacity-75 position-absolute bottom-0 end-0 m-3 px-2 py-1 small">
                                        <?= htmlspecialchars($doc['tahun_terbit']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <!-- Bagian Konten Teks -->
                            <div class="p-4 d-flex flex-column flex-grow-1">
                                <!-- Judul Buku -->
                                <h5 class="fw-bold mb-2 text-dark" style="font-size: 1.15rem; line-height: 1.4;">
                                    <?= htmlspecialchars($doc['judul']) ?>
                                </h5>

                                <!-- Penulis & Penerbit -->
                                <p class="text-muted small mb-3">
                                    <i class="bi bi-person-fill text-success me-1"></i>
                                    Penulis: <strong><?= htmlspecialchars($doc['penulis'] ?? 'PWA Jawa Tengah') ?></strong>
                                </p>

                                <!-- Deskripsi Ringkas -->
                                <p class="small text-secondary mb-4 flex-grow-1" style="line-height: 1.6;">
                                    <?= htmlspecialchars(mb_strimwidth($doc['deskripsi'] ?? 'Naskah pustaka resmi Aisyiyah Jawa Tengah.', 0, 115, '...')) ?>
                                </p>

                                <!-- Baris Bawah: Counter & Tombol Unduh -->
                                <div class="pt-3 border-top d-flex justify-content-between align-items-center mt-auto">
                                    <span class="small text-muted" title="Jumlah Pengunduh">
                                        <i class="bi bi-cloud-arrow-down-fill text-success me-1"></i>
                                        <?= $doc['download_count'] ?> kali
                                    </span>

                                    <!-- Tombol Akses/Unduh Dokumen -->
                                    <?php 
                                    $path_file = 'assets/uploads/pustaka/' . $doc['file_dokumen'];
                                    $file_ada  = !empty($doc['file_dokumen']) && file_exists($path_file);
                                    ?>

                                    <?php if ($file_ada) : ?>
                                        <a href="<?= $path_file ?>" target="_blank" download class="btn btn-outline-gold btn-sm px-3 py-2 d-flex align-items-center gap-1">
                                            <i class="bi bi-file-earmark-pdf-fill text-danger"></i>
                                            <span>Unduh PDF</span>
                                        </a>
                                    <?php else : ?>
                                        <button class="btn btn-sm btn-light border text-muted px-3 py-2 d-flex align-items-center gap-1" disabled title="Berkas sedang dalam digitalisasi">
                                            <i class="bi bi-clock-history"></i>
                                            <span>Arsip Digital</span>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else : ?>
                <!-- Tampilan Jika Data Tidak Ditemukan -->
                <div class="col-12 text-center py-5">
                    <div class="p-5 bg-white rounded-4 border shadow-sm mx-auto" style="max-width: 600px;">
                        <i class="bi bi-journal-x text-muted" style="font-size: 4rem;"></i>
                        <h4 class="mt-3 text-secondary fw-bold">Dokumen Tidak Ditemukan</h4>
                        <p class="text-muted small mb-4">
                            Tidak ada arsip dokumen yang sesuai dengan kata kunci atau kategori yang Anda pilih.
                        </p>
                        <a href="pustaka.php" class="btn btn-outline-gold px-4 py-2">
                            <i class="bi bi-arrow-clockwise me-1"></i> Tampilkan Semua Pustaka
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php
include 'includes/footer.php';
?>
