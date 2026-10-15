<?php
/**
 * Website: Aisyiyah Digital Jawa Tengah
 * Halaman: Berita & Artikel Publik (artikel.php)
 * Fungsi: Menampilkan daftar artikel, berita, dan kabar dakwah dari database MySQL
 */

include 'config/database.php';

$page_title = "Berita & Artikel - Aisyiyah Digital Jawa Tengah";
include 'includes/header.php';
include 'includes/navbar.php';

// 1. Menangkap Filter dan Pencarian
$cari   = isset($_GET['cari']) ? trim($_GET['cari']) : '';
$kat_id = isset($_GET['kategori']) ? intval($_GET['kategori']) : 0;

$where_clauses = ["a.status = 'published'"];

if (!empty($cari)) {
    $cari_safe = mysqli_real_escape_string($conn, $cari);
    $where_clauses[] = "(a.judul LIKE '%$cari_safe%' OR a.isi LIKE '%$cari_safe%')";
}

if ($kat_id > 0) {
    $where_clauses[] = "a.kategori_id = $kat_id";
}

$where_sql = "WHERE " . implode(" AND ", $where_clauses);

// 2. Kueri Mengambil Artikel
$sql = "SELECT a.*, k.nama_kategori, adm.nama_lengkap 
        FROM artikel a 
        LEFT JOIN kategori k ON a.kategori_id = k.id 
        LEFT JOIN admins adm ON a.admin_id = adm.id 
        $where_sql 
        ORDER BY a.id DESC";
$query_artikel = mysqli_query($conn, $sql);

// Kategori Khusus Artikel untuk Tombol Filter
$query_kategori = mysqli_query($conn, "SELECT DISTINCT k.id, k.nama_kategori 
                                       FROM kategori k 
                                       INNER JOIN artikel a ON k.id = a.kategori_id 
                                       WHERE a.status = 'published' 
                                       ORDER BY k.nama_kategori ASC");

$total_artikel = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM artikel WHERE status = 'published'"))['total'] ?? 0;
?>

<!-- ================= 1. BANNER HEADER ================= -->
<section class="py-5 position-relative overflow-hidden" 
         style="background: linear-gradient(135deg, var(--primary-green-dark) 0%, var(--primary-green) 100%); color: #fff;">
    <div class="container position-relative py-3 text-center">
        <span class="badge bg-warning text-dark px-3 py-2 fw-bold mb-2">Publikasi & Kabar</span>
        <h1 class="text-white fw-bold mb-3 display-5">Berita & Artikel Aisyiyah Jawa Tengah</h1>
        <p class="text-white-50 mx-auto mb-0" style="max-width: 720px; font-size: 1.1rem; line-height: 1.8;">
            Kumpulan warta dakwah, gagasan pemikiran perempuan berkemajuan, liputan kegiatan persyarikatan, dan wawasan kemaslahatan umat dari seluruh wilayah Jawa Tengah.
        </p>
    </div>
</section>

<!-- ================= 2. BREADCRUMB ================= -->
<div class="bg-white border-bottom py-3">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="index.php" class="text-success text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item active text-muted" aria-current="page">Artikel & Berita</li>
            </ol>
        </nav>
    </div>
</div>

<!-- ================= 3. FILTER & PENCARIAN ================= -->
<section class="py-4" style="background-color: #f7f5ed; border-bottom: 1px solid var(--border-color);">
    <div class="container">
        <div class="row g-3 align-items-center justify-content-between">
            <!-- Form Pencarian -->
            <div class="col-lg-6">
                <form action="artikel.php" method="GET" class="d-flex gap-2">
                    <?php if ($kat_id > 0) : ?>
                        <input type="hidden" name="kategori" value="<?= $kat_id ?>">
                    <?php endif; ?>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" name="cari" 
                               placeholder="Cari topik atau judul artikel..." 
                               value="<?= htmlspecialchars($cari) ?>">
                        <button type="submit" class="btn btn-green px-4">Cari</button>
                    </div>
                    <?php if (!empty($cari) || $kat_id > 0) : ?>
                        <a href="artikel.php" class="btn btn-outline-secondary d-flex align-items-center" title="Reset Filter">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Counter Info -->
            <div class="col-lg-5 text-lg-end">
                <span class="badge bg-white text-dark border p-2 px-3 fw-semibold shadow-sm">
                    <i class="bi bi-newspaper text-success me-1"></i>
                    Menampilkan: <strong><?= mysqli_num_rows($query_artikel) ?></strong> dari total <?= $total_artikel ?> Artikel Tayang
                </span>
            </div>
        </div>

        <!-- Filter Kategori Kancing (Pills) -->
        <div class="d-flex flex-wrap gap-2 mt-3 pt-3 border-top border-secondary border-opacity-10 align-items-center">
            <span class="small fw-bold text-muted me-2"><i class="bi bi-funnel-fill text-warning me-1"></i>Kategori:</span>
            
            <a href="artikel.php<?= !empty($cari) ? '?cari=' . urlencode($cari) : '' ?>" 
               class="btn btn-sm <?= ($kat_id === 0) ? 'btn-green' : 'btn-outline-secondary bg-white' ?> rounded-pill px-3">
                Semua Kategori
            </a>

            <?php while ($kat = mysqli_fetch_assoc($query_kategori)) : ?>
                <?php 
                $active = ($kat_id == $kat['id']) ? 'btn-green' : 'btn-outline-secondary bg-white';
                $url_kat = "artikel.php?kategori=" . $kat['id'] . (!empty($cari) ? '&cari=' . urlencode($cari) : '');
                ?>
                <a href="<?= $url_kat ?>" class="btn btn-sm <?= $active ?> rounded-pill px-3">
                    <?= htmlspecialchars($kat['nama_kategori']) ?>
                </a>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- ================= 4. DAFTAR KARTU ARTIKEL ================= -->
<section class="py-5">
    <div class="container">
        <?php if (!empty($cari)) : ?>
            <div class="alert alert-light border d-flex justify-content-between align-items-center mb-4 py-2">
                <span>Hasil pencarian artikel untuk: <strong class="text-success">"<?= htmlspecialchars($cari) ?>"</strong></span>
                <a href="artikel.php" class="small text-danger text-decoration-none">Hapus Pencarian</a>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <?php if (mysqli_num_rows($query_artikel) > 0) : ?>
                <?php while ($art = mysqli_fetch_assoc($query_artikel)) : ?>
                    <?php
                    $path_img = 'assets/uploads/artikel/' . $art['gambar'];
                    $ada_img  = !empty($art['gambar']) && file_exists($path_img);
                    ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 border rounded-4 shadow-sm bg-white overflow-hidden d-flex flex-column"
                             style="transition: transform 0.3s ease, box-shadow 0.3s ease;">
                            
                            <!-- Gambar Sampul Artikel -->
                            <div class="position-relative overflow-hidden" style="height: 220px; background: linear-gradient(135deg, #0d472a, #166534);">
                                <?php if ($ada_img) : ?>
                                    <img src="<?= $path_img ?>" 
                                         alt="<?= htmlspecialchars($art['judul']) ?>" 
                                         class="w-100 h-100" style="object-fit: cover;">
                                <?php else : ?>
                                    <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-white">
                                        <i class="bi bi-newspaper fs-1 text-warning mb-1 opacity-75"></i>
                                        <span class="small fw-semibold text-white-50">Aisyiyah Digital Jateng</span>
                                    </div>
                                <?php endif; ?>

                                <!-- Badge Kategori -->
                                <span class="badge position-absolute top-0 start-0 m-3 px-3 py-2 fw-semibold" 
                                      style="background-color: var(--gold); color: #fff; font-size: 0.75rem;">
                                    <?= htmlspecialchars($art['nama_kategori'] ?? 'Kabar Berita') ?>
                                </span>
                            </div>

                            <!-- Konten Teks Kartu -->
                            <div class="p-4 d-flex flex-column flex-grow-1">
                                <div class="text-muted small mb-2 d-flex align-items-center gap-2" style="font-size: 0.8rem;">
                                    <i class="bi bi-calendar3"></i> <?= date('d M Y', strtotime($art['created_at'])) ?>
                                    <span>•</span>
                                    <i class="bi bi-eye"></i> <?= $art['views'] ?> kali
                                </div>

                                <h5 class="fw-bold mb-2 text-dark" style="font-size: 1.18rem; line-height: 1.4;">
                                    <a href="detail-artikel.php?id=<?= $art['id'] ?>" class="text-dark text-decoration-none">
                                        <?= htmlspecialchars($art['judul']) ?>
                                    </a>
                                </h5>

                                <p class="small text-secondary mb-4 flex-grow-1" style="line-height: 1.6;">
                                    <?= htmlspecialchars(mb_strimwidth(strip_tags($art['isi']), 0, 120, '...')) ?>
                                </p>

                                <div class="pt-3 border-top mt-auto d-flex justify-content-between align-items-center">
                                    <a href="detail-artikel.php?id=<?= $art['id'] ?>" class="btn btn-outline-gold btn-sm d-inline-flex align-items-center gap-1">
                                        <span>Baca Selengkapnya</span>
                                        <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else : ?>
                <div class="col-12 text-center py-5">
                    <div class="p-5 bg-white rounded-4 border shadow-sm mx-auto" style="max-width: 600px;">
                        <i class="bi bi-journal-x text-muted" style="font-size: 4rem;"></i>
                        <h4 class="mt-3 text-secondary fw-bold">Artikel Tidak Ditemukan</h4>
                        <p class="text-muted small mb-4">
                            Belum ada artikel yang sesuai dengan kata kunci atau kategori yang Anda pilih.
                        </p>
                        <a href="artikel.php" class="btn btn-outline-gold px-4 py-2">
                            <i class="bi bi-arrow-clockwise me-1"></i> Tampilkan Semua Artikel
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
