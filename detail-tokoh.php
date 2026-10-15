<?php
/**
 * Website: Aisyiyah Digital Jawa Tengah
 * Halaman: Detail Profil Tokoh (detail-tokoh.php)
 */

// 1. Hubungkan ke Database
include 'config/database.php';

// 2. Ambil ID tokoh dari URL (misal: detail-tokoh.php?id=1)
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 3. Query data tokoh berdasarkan ID
$query = mysqli_query($conn, "SELECT * FROM tokoh WHERE id = $id");
$tokoh = mysqli_fetch_assoc($query);

// Jika tokoh tidak ditemukan, arahkan kembali ke daftar tokoh
if (!$tokoh) {
    header("Location: tokoh.php");
    exit;
}

$page_title = $tokoh['nama'] . " - Profil Tokoh Aisyiyah";
include 'includes/header.php';
include 'includes/navbar.php';

// Format tahun hidup
$tahun_lahir = !empty($tokoh['tanggal_lahir']) ? date('Y', strtotime($tokoh['tanggal_lahir'])) : '';
$wafat = !empty($tokoh['wafat']) ? $tokoh['wafat'] : '-';
$tahun_hidup = $tahun_lahir ? "$tahun_lahir — $wafat" : $wafat;
?>

<!-- Breadcrumb Navigasi -->
<div class="bg-white border-bottom py-3">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                <li class="breadcrumb-item"><a href="tokoh.php">Tokoh</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($tokoh['nama']) ?></li>
            </ol>
        </nav>
    </div>
</div>

<div class="py-5">
    <div class="container">
        <div class="row g-5">
            <!-- Kolom Foto & Data Pokok -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden sticky-top" style="top: 90px;">
                    <div class="tokoh-img-wrap" style="height: 320px;">
                        <?php if (!empty($tokoh['foto']) && file_exists('assets/uploads/tokoh/' . $tokoh['foto'])) : ?>
                            <img src="assets/uploads/tokoh/<?= htmlspecialchars($tokoh['foto']) ?>" alt="<?= htmlspecialchars($tokoh['nama']) ?>">
                        <?php else : ?>
                            <div class="text-center text-success">
                                <i class="bi bi-person-bounding-box" style="font-size: 7rem; color: var(--primary-green-light); opacity: 0.85;"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-body p-4 bg-white">
                        <h4 class="fw-bold mb-1 text-success"><?= htmlspecialchars($tokoh['nama']) ?></h4>
                        <span class="badge bg-warning text-dark mb-3"><?= htmlspecialchars($tokoh['peran_jabatan'] ?? 'Tokoh Aisyiyah') ?></span>

                        <ul class="list-unstyled small text-secondary border-top pt-3 mb-4">
                            <li class="mb-2 d-flex justify-content-between">
                                <span class="text-muted"><i class="bi bi-geo-alt me-1"></i> Tempat Lahir:</span>
                                <strong class="text-dark"><?= htmlspecialchars($tokoh['tempat_lahir'] ?? '-') ?></strong>
                            </li>
                            <li class="mb-2 d-flex justify-content-between">
                                <span class="text-muted"><i class="bi bi-calendar-event me-1"></i> Periode Hidup:</span>
                                <strong class="text-dark"><?= htmlspecialchars($tahun_hidup) ?></strong>
                            </li>
                        </ul>

                        <a href="tokoh.php" class="btn btn-outline-secondary btn-sm w-100 py-2 d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-arrow-left"></i>
                            <span>Kembali ke Daftar Tokoh</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Kolom Riwayat Hidup & Jasa Perjuangan -->
            <div class="col-lg-8">
                <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border">
                    <h3 class="fw-bold text-success mb-3">Biografi & Riwayat Hidup</h3>
                    <div class="text-secondary lh-lg mb-5">
                        <?= nl2br(htmlspecialchars($tokoh['biografi'])) ?>
                    </div>

                    <?php if (!empty($tokoh['jasa_perjuangan'])) : ?>
                        <h4 class="fw-bold text-success mb-3 border-top pt-4">
                            <i class="bi bi-award-fill text-warning me-2"></i>Jasa & Peran Perjuangan
                        </h4>
                        <div class="text-secondary lh-lg">
                            <?= nl2br(htmlspecialchars($tokoh['jasa_perjuangan'])) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
