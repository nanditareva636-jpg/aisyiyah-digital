<?php
/**
 * Website: Aisyiyah Digital Jawa Tengah
 * Halaman: Detail Baca Artikel (detail-artikel.php)
 * Fungsi: Menampilkan isi penuh artikel, menambah counter views, dan menampilkan rekomendasi tulisan lain
 */

include 'config/database.php';

// 1. Menangkap ID Artikel
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header("Location: artikel.php");
    exit;
}

// 2. Menambah Angka Penayangan (Views Counter)
mysqli_query($conn, "UPDATE artikel SET views = views + 1 WHERE id = $id");

// 3. Mengambil Data Artikel Lengkap
$sql = "SELECT a.*, k.nama_kategori, adm.nama_lengkap 
        FROM artikel a 
        LEFT JOIN kategori k ON a.kategori_id = k.id 
        LEFT JOIN admins adm ON a.admin_id = adm.id 
        WHERE a.id = $id AND a.status = 'published'";
$result  = mysqli_query($conn, $sql);
$artikel = mysqli_fetch_assoc($result);

// Jika artikel tidak ditemukan, kembalikan ke daftar artikel
if (!$artikel) {
    header("Location: artikel.php");
    exit;
}

$page_title = $artikel['judul'] . " - Aisyiyah Digital Jawa Tengah";
include 'includes/header.php';
include 'includes/navbar.php';

// Artikel Terkait / Terbaru untuk Sidebar
$query_terkait = mysqli_query($conn, "SELECT id, judul, created_at, gambar 
                                      FROM artikel 
                                      WHERE id != $id AND status = 'published' 
                                      ORDER BY id DESC LIMIT 4");

$path_gambar = 'assets/uploads/artikel/' . $artikel['gambar'];
$ada_gambar  = !empty($artikel['gambar']) && file_exists($path_gambar);
?>

<!-- ================= 1. BREADCRUMB ================= -->
<div class="bg-white border-bottom py-3">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="index.php" class="text-success text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item"><a href="artikel.php" class="text-success text-decoration-none">Artikel</a></li>
                <li class="breadcrumb-item active text-muted text-truncate" style="max-width: 400px;" aria-current="page"><?= htmlspecialchars($artikel['judul']) ?></li>
            </ol>
        </nav>
    </div>
</div>

<!-- ================= 2. KONTEN UTAMA ARTIKEL ================= -->
<section class="py-5">
    <div class="container">
        <div class="row g-5">
            <!-- Kolom Isi Tulisan -->
            <div class="col-lg-8">
                <div class="bg-white p-4 p-md-5 rounded-4 border shadow-sm">
                    <!-- Kategori & Meta Tanggal -->
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                        <span class="badge bg-warning text-dark px-3 py-2 fw-bold">
                            <?= htmlspecialchars($artikel['nama_kategori'] ?? 'Kabar Berita') ?>
                        </span>
                        <span class="text-muted small">
                            <i class="bi bi-calendar3 me-1"></i><?= date('d M Y, H:i', strtotime($artikel['created_at'])) ?> WIB
                        </span>
                        <span class="text-muted small">•</span>
                        <span class="text-muted small">
                            <i class="bi bi-eye me-1 text-primary"></i><?= $artikel['views'] ?> kali dibaca
                        </span>
                    </div>

                    <!-- Judul Utama Artikel -->
                    <h1 class="fw-bold text-dark mb-4" style="font-size: 2.1rem; line-height: 1.35;">
                        <?= htmlspecialchars($artikel['judul']) ?>
                    </h1>

                    <!-- Info Penulis -->
                    <div class="d-flex align-items-center gap-3 pb-4 mb-4 border-bottom">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white" 
                             style="width: 45px; height: 45px; background: var(--primary-green); font-size: 1.2rem;">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <div>
                            <span class="d-block fw-bold text-dark"><?= htmlspecialchars($artikel['nama_lengkap'] ?? 'Redaksi PWA Jateng') ?></span>
                            <small class="text-muted">Pimpinan Wilayah Aisyiyah Jawa Tengah</small>
                        </div>
                    </div>

                    <!-- Gambar Sampul Utama -->
                    <?php if ($ada_gambar) : ?>
                        <div class="mb-4 rounded-4 overflow-hidden shadow-sm" style="max-height: 440px;">
                            <img src="<?= $path_gambar ?>" alt="<?= htmlspecialchars($artikel['judul']) ?>" class="w-100 h-100" style="object-fit: cover;">
                        </div>
                    <?php endif; ?>

                    <!-- Isi Lengkap Tulisan -->
                    <div class="article-content text-secondary lh-lg mb-5" style="font-size: 1.1rem; line-height: 1.9;">
                        <?= nl2br(htmlspecialchars($artikel['isi'])) ?>
                    </div>

                    <!-- Tombol Kembali & Berbagi -->
                    <div class="pt-4 border-top d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <a href="artikel.php" class="btn btn-outline-secondary btn-sm px-3 py-2 d-inline-flex align-items-center gap-2">
                            <i class="bi bi-arrow-left"></i>
                            <span>Kembali ke Daftar Artikel</span>
                        </a>

                        <div class="d-flex align-items-center gap-2">
                            <span class="small text-muted fw-bold">Bagikan:</span>
                            <a href="https://wa.me/?text=<?= urlencode($artikel['judul'] . ' - ' . 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>" target="_blank" class="btn btn-sm btn-success rounded-circle p-2" title="Bagikan ke WhatsApp" style="width: 36px; height: 36px;">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>" target="_blank" class="btn btn-sm btn-primary rounded-circle p-2" title="Bagikan ke Facebook" style="width: 36px; height: 36px;">
                                <i class="bi bi-facebook"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Artikel Lainnya -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4 sticky-top" style="top: 90px;">
                    <h5 class="fw-bold text-success mb-3 pb-2 border-bottom">
                        <i class="bi bi-journal-text me-2 text-warning"></i>Artikel Terbaru Lainnya
                    </h5>

                    <div class="list-group list-group-flush">
                        <?php if (mysqli_num_rows($query_terkait) > 0) : ?>
                            <?php while ($terkait = mysqli_fetch_assoc($query_terkait)) : ?>
                                <a href="detail-artikel.php?id=<?= $terkait['id'] ?>" class="list-group-item list-group-item-action px-0 py-3 border-bottom">
                                    <h6 class="fw-bold text-dark mb-1" style="line-height: 1.4; font-size: 0.95rem;">
                                        <?= htmlspecialchars($terkait['judul']) ?>
                                    </h6>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar3 me-1"></i><?= date('d M Y', strtotime($terkait['created_at'])) ?>
                                    </small>
                                </a>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <p class="text-muted small py-2 mb-0">Belum ada tulisan lainnya.</p>
                        <?php endif; ?>
                    </div>

                    <div class="mt-4 pt-2">
                        <a href="artikel.php" class="btn btn-outline-gold btn-sm w-100 py-2 text-center">
                            Lihat Semua Artikel <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
include 'includes/footer.php';
?>
