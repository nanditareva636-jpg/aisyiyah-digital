<?php
/**
 * Website: Aisyiyah Digital Jawa Tengah
 * Panel Admin: Dashboard Utama (admin/dashboard.php)
 * Menampilkan statistik: Jumlah Tokoh, Artikel, Pustaka, dan Galeri
 */

session_start();

// Pemeriksaan Keamanan: Jika belum login, dialihkan ke login.php
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

include '../config/database.php';

$page_title   = "Dashboard Pengelola - Aisyiyah Digital Jawa Tengah";
$page_heading = "Ringkasan & Statistik Portal";
$active_menu  = "dashboard";
$root_path    = "../";

include 'includes/header.php';

// 1. Mengambil Data Statistik dari Database MySQL
$q_tokoh   = mysqli_query($conn, "SELECT COUNT(*) AS total FROM tokoh");
$count_tokoh = mysqli_fetch_assoc($q_tokoh)['total'] ?? 0;

$q_artikel = mysqli_query($conn, "SELECT COUNT(*) AS total FROM artikel");
$count_artikel = mysqli_fetch_assoc($q_artikel)['total'] ?? 0;

$q_pustaka = mysqli_query($conn, "SELECT COUNT(*) AS total FROM pustaka");
$count_pustaka = mysqli_fetch_assoc($q_pustaka)['total'] ?? 0;

$q_galeri  = mysqli_query($conn, "SELECT COUNT(*) AS total FROM galeri");
$count_galeri = mysqli_fetch_assoc($q_galeri)['total'] ?? 0;

// Mengambil beberapa tokoh terbaru untuk cuplikan cepat
$q_recent_tokoh = mysqli_query($conn, "SELECT id, nama, peran_jabatan, foto FROM tokoh ORDER BY id DESC LIMIT 3");

// Mengambil beberapa artikel terbaru
$q_recent_artikel = mysqli_query($conn, "SELECT id, judul, created_at, status FROM artikel ORDER BY id DESC LIMIT 3");
?>

<!-- ================= BANNER SAMBUTAN ================= -->
<div class="card card-admin p-4 mb-4 border-0" 
     style="background: linear-gradient(135deg, var(--admin-green-dark) 0%, var(--admin-green) 100%); color: #fff;">
    <div class="row align-items-center g-3">
        <div class="col-lg-8">
            <span class="badge bg-warning text-dark px-3 py-1 fw-bold mb-2">Panel Administrasi Resmi</span>
            <h3 class="fw-bold mb-2 text-white">
                Selamat Datang, <?= htmlspecialchars($_SESSION['admin_nama'] ?? 'Administrator') ?>!
            </h3>
            <p class="text-white-50 mb-0 small" style="line-height: 1.7;">
                Kelola khazanah sejarah, biografi tokoh, artikel kabar berita, dokumen pustaka digital, dan dokumentasi galeri kegiatan Aisyiyah & Muhammadiyah Jawa Tengah secara terpusat.
            </p>
        </div>
        <div class="col-lg-4 text-lg-end">
            <a href="../index.php" target="_blank" class="btn btn-admin-gold d-inline-flex align-items-center gap-2">
                <i class="bi bi-globe2"></i>
                <span>Kunjungi Website Publik</span>
            </a>
        </div>
    </div>
</div>

<!-- ================= 4 KARTU STATISTIK UTAMA ================= -->
<div class="row g-4 mb-4">
    <!-- 1. Statistik Jumlah Tokoh -->
    <div class="col-xl-3 col-sm-6">
        <div class="card-stat h-100 border-start border-4 border-success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em;">Jumlah Tokoh</span>
                    <h2 class="fw-bold mb-0 mt-1" style="color: var(--admin-green);"><?= $count_tokoh ?></h2>
                    <small class="text-muted">Figur Terdata</small>
                </div>
                <div class="stat-icon-wrap" style="background-color: #e8f5e9; color: #1b5e20;">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center">
                <a href="tokoh/index.php" class="small fw-bold text-success text-decoration-none">
                    Kelola Tokoh <i class="bi bi-arrow-right"></i>
                </a>
                <a href="tokoh/tambah.php" class="btn btn-sm btn-outline-success rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;" title="Tambah Tokoh">
                    <i class="bi bi-plus"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Statistik Jumlah Artikel -->
    <div class="col-xl-3 col-sm-6">
        <div class="card-stat h-100 border-start border-4 border-primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em;">Jumlah Artikel</span>
                    <h2 class="fw-bold mb-0 mt-1 text-primary"><?= $count_artikel ?></h2>
                    <small class="text-muted">Berita & Opini</small>
                </div>
                <div class="stat-icon-wrap" style="background-color: #e3f2fd; color: #0d47a1;">
                    <i class="bi bi-journal-richtext"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center">
                <a href="artikel/index.php" class="small fw-bold text-primary text-decoration-none">
                    Kelola Artikel <i class="bi bi-arrow-right"></i>
                </a>
                <a href="artikel/tambah.php" class="btn btn-sm btn-outline-primary rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;" title="Tulis Artikel">
                    <i class="bi bi-plus"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- 3. Statistik Jumlah Pustaka -->
    <div class="col-xl-3 col-sm-6">
        <div class="card-stat h-100 border-start border-4 border-warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em;">Jumlah Pustaka</span>
                    <h2 class="fw-bold mb-0 mt-1 text-warning" style="color: #b8860b !important;"><?= $count_pustaka ?></h2>
                    <small class="text-muted">Buku & Dokumen PDF</small>
                </div>
                <div class="stat-icon-wrap" style="background-color: #fff8e1; color: #b8860b;">
                    <i class="bi bi-book-half"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center">
                <a href="pustaka/index.php" class="small fw-bold text-warning text-decoration-none" style="color: #b8860b !important;">
                    Kelola Pustaka <i class="bi bi-arrow-right"></i>
                </a>
                <a href="pustaka/tambah.php" class="btn btn-sm btn-outline-warning rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; color: #b8860b; border-color: #b8860b;" title="Tambah Dokumen">
                    <i class="bi bi-plus"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- 4. Statistik Jumlah Galeri -->
    <div class="col-xl-3 col-sm-6">
        <div class="card-stat h-100 border-start border-4 border-info">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em;">Jumlah Galeri</span>
                    <h2 class="fw-bold mb-0 mt-1 text-info"><?= $count_galeri ?></h2>
                    <small class="text-muted">Foto Kegiatan</small>
                </div>
                <div class="stat-icon-wrap" style="background-color: #e0f7fa; color: #006064;">
                    <i class="bi bi-images"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center">
                <a href="galeri/index.php" class="small fw-bold text-info text-decoration-none">
                    Kelola Galeri <i class="bi bi-arrow-right"></i>
                </a>
                <a href="galeri/tambah.php" class="btn btn-sm btn-outline-info rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;" title="Unggah Foto">
                    <i class="bi bi-plus"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- ================= AREA INFORMASI & PINTASAN KONTEN ================= -->
<div class="row g-4">
    <!-- Kolom Kiri: Tokoh Terbaru Terdaftar -->
    <div class="col-lg-6">
        <div class="card card-admin p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0" style="color: var(--admin-green);">
                    <i class="bi bi-people-fill me-2 text-warning"></i>Tokoh Terbaru Terdata
                </h6>
                <a href="tokoh/index.php" class="small text-muted text-decoration-none">Lihat Semua</a>
            </div>

            <div class="list-group list-group-flush">
                <?php if (mysqli_num_rows($q_recent_tokoh) > 0) : ?>
                    <?php while ($r_tokoh = mysqli_fetch_assoc($q_recent_tokoh)) : ?>
                        <div class="list-group-item px-0 py-3 border-bottom d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <?php if (!empty($r_tokoh['foto']) && file_exists('../../assets/uploads/tokoh/' . $r_tokoh['foto'])) : ?>
                                    <img src="../../assets/uploads/tokoh/<?= htmlspecialchars($r_tokoh['foto']) ?>" 
                                         class="rounded-circle border" style="width: 44px; height: 44px; object-fit: cover;">
                                <?php else : ?>
                                    <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center text-success" 
                                         style="width: 44px; height: 44px;">
                                        <i class="bi bi-person-fill fs-5"></i>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <strong class="d-block text-dark small mb-0"><?= htmlspecialchars($r_tokoh['nama']) ?></strong>
                                    <small class="text-muted" style="font-size: 0.78rem;">
                                        <?= htmlspecialchars(mb_strimwidth($r_tokoh['peran_jabatan'] ?? 'Tokoh Aisyiyah', 0, 35, "...")) ?>
                                    </small>
                                </div>
                            </div>
                            <a href="tokoh/edit.php?id=<?= $r_tokoh['id'] ?>" class="btn btn-sm btn-outline-secondary py-1 px-2" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                        </div>
                    <?php endwhile; ?>
                <?php else : ?>
                    <p class="text-muted small py-3 mb-0">Belum ada data tokoh.</p>
                <?php endif; ?>
            </div>

            <div class="mt-3 pt-2">
                <a href="tokoh/tambah.php" class="btn btn-admin-green btn-sm w-100 py-2 d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-person-plus-fill"></i> Tambah Tokoh Baru
                </a>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Artikel & Menu Cepat -->
    <div class="col-lg-6">
        <div class="card card-admin p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0" style="color: var(--admin-green);">
                    <i class="bi bi-journal-text me-2 text-warning"></i>Publikasi Artikel Terkini
                </h6>
                <a href="artikel/index.php" class="small text-muted text-decoration-none">Lihat Semua</a>
            </div>

            <div class="list-group list-group-flush mb-4">
                <?php if (mysqli_num_rows($q_recent_artikel) > 0) : ?>
                    <?php while ($r_art = mysqli_fetch_assoc($q_recent_artikel)) : ?>
                        <div class="list-group-item px-0 py-3 border-bottom d-flex align-items-center justify-content-between">
                            <div class="pe-2">
                                <strong class="d-block text-dark small mb-1">
                                    <?= htmlspecialchars(mb_strimwidth($r_art['judul'], 0, 48, "...")) ?>
                                </strong>
                                <small class="text-muted" style="font-size: 0.75rem;">
                                    <i class="bi bi-clock me-1"></i> <?= date('d M Y', strtotime($r_art['created_at'])) ?>
                                </small>
                            </div>
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 small">
                                <?= htmlspecialchars($r_art['status']) ?>
                            </span>
                        </div>
                    <?php endwhile; ?>
                <?php else : ?>
                    <p class="text-muted small py-3 mb-0">Belum ada data artikel.</p>
                <?php endif; ?>
            </div>

            <!-- Pintasan Cepat -->
            <div class="border-top pt-3">
                <span class="text-muted small fw-bold d-block mb-2 text-uppercase" style="font-size: 0.75rem;">Aksi Cepat Menu</span>
                <div class="d-flex flex-wrap gap-2">
                    <a href="pustaka/index.php" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
                        <i class="bi bi-book-half"></i> Pustaka
                    </a>
                    <a href="galeri/index.php" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
                        <i class="bi bi-images"></i> Galeri
                    </a>
                    <a href="sejarah/index.php" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
                        <i class="bi bi-hourglass-split"></i> Sejarah
                    </a>
                    <a href="kegiatan/index.php" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
                        <i class="bi bi-calendar-event"></i> Kegiatan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
