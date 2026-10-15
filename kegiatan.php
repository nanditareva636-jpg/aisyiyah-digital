<?php
/**
 * Website: Aisyiyah Digital Jawa Tengah
 * Halaman: Agenda & Kegiatan (kegiatan.php)
 * Fungsi: Menampilkan agenda acara, program dakwah, dan musyawarah terpadu dari database MySQL
 */

include 'config/database.php';

$page_title = "Agenda & Kegiatan - Aisyiyah Digital Jawa Tengah";
include 'includes/header.php';
include 'includes/navbar.php';

// 1. Menangkap Filter Status dan Pencarian
$status_filter = isset($_GET['status']) ? trim($_GET['status']) : 'semua';
$cari          = isset($_GET['cari']) ? trim($_GET['cari']) : '';

// 2. Menyusun Kondisi SQL
$where_clauses = [];

if (!empty($cari)) {
    $cari_safe = mysqli_real_escape_string($conn, $cari);
    $where_clauses[] = "(nama_kegiatan LIKE '%$cari_safe%' OR lokasi LIKE '%$cari_safe%' OR penyelenggara LIKE '%$cari_safe%' OR deskripsi LIKE '%$cari_safe%')";
}

if ($status_filter !== 'semua' && in_array($status_filter, ['akan_datang', 'berlangsung', 'selesai'])) {
    $status_safe = mysqli_real_escape_string($conn, $status_filter);
    $where_clauses[] = "status = '$status_safe'";
}

$where_sql = !empty($where_clauses) ? "WHERE " . implode(" AND ", $where_clauses) : "";

// Urutan: yang berlangsung dan akan datang ditaruh di awal, lalu berdasarkan tanggal mulai
$sql = "SELECT * FROM kegiatan 
        $where_sql 
        ORDER BY 
            CASE 
                WHEN status = 'berlangsung' THEN 1 
                WHEN status = 'akan_datang' THEN 2 
                ELSE 3 
            END ASC, 
            tanggal_mulai DESC";
$query_kegiatan = mysqli_query($conn, $sql);

// Fungsi pembantu untuk format tanggal Indonesia
function format_tgl_indo($tgl) {
    if (empty($tgl)) return '';
    $bulan_indo = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    $pecah = explode('-', $tgl);
    if (count($pecah) !== 3) return $tgl;
    $tahun = $pecah[0];
    $bulan = (int)$pecah[1];
    $hari  = (int)$pecah[2];
    return $hari . ' ' . ($bulan_indo[$bulan] ?? '') . ' ' . $tahun;
}
?>

<!-- ================= 1. BANNER HEADER ================= -->
<section class="py-5 position-relative overflow-hidden" 
         style="background: linear-gradient(135deg, var(--primary-green-dark) 0%, var(--primary-green) 100%); color: #fff;">
    <div class="container position-relative py-3 text-center">
        <span class="badge bg-warning text-dark px-3 py-2 fw-bold mb-2">Aktivitas & Dakwah</span>
        <h1 class="text-white fw-bold mb-3 display-5">Agenda & Kegiatan Aisyiyah Jawa Tengah</h1>
        <p class="text-white-50 mx-auto mb-0" style="max-width: 720px; font-size: 1.1rem; line-height: 1.8;">
            Informasi terkini jadwal musyawarah wilayah, seminar pemberdayaan perempuan, perkaderan Baitul Arqam, dan aksi sosial kemanusiaan di seluruh karesidenan Jawa Tengah.
        </p>
    </div>
</section>

<!-- ================= 2. BREADCRUMB ================= -->
<div class="bg-white border-bottom py-3">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="index.php" class="text-success text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item active text-muted" aria-current="page">Kegiatan</li>
            </ol>
        </nav>
    </div>
</div>

<!-- ================= 3. FILTER TAB & PENCARIAN ================= -->
<section class="py-4" style="background-color: #f7f5ed; border-bottom: 1px solid var(--border-color);">
    <div class="container">
        <div class="row g-3 align-items-center justify-content-between">
            <!-- Filter Kategori Status (Pills) -->
            <div class="col-lg-7">
                <div class="d-flex flex-wrap gap-2">
                    <a href="kegiatan.php?status=semua<?= !empty($cari) ? '&cari=' . urlencode($cari) : '' ?>" 
                       class="btn btn-sm <?= ($status_filter === 'semua') ? 'btn-green' : 'btn-outline-secondary bg-white' ?> rounded-pill px-3">
                        Semua Agenda
                    </a>
                    <a href="kegiatan.php?status=akan_datang<?= !empty($cari) ? '&cari=' . urlencode($cari) : '' ?>" 
                       class="btn btn-sm <?= ($status_filter === 'akan_datang') ? 'btn-green' : 'btn-outline-secondary bg-white' ?> rounded-pill px-3">
                        <i class="bi bi-calendar-event me-1 text-warning"></i> Akan Datang
                    </a>
                    <a href="kegiatan.php?status=berlangsung<?= !empty($cari) ? '&cari=' . urlencode($cari) : '' ?>" 
                       class="btn btn-sm <?= ($status_filter === 'berlangsung') ? 'btn-green' : 'btn-outline-secondary bg-white' ?> rounded-pill px-3">
                        <i class="bi bi-record-circle-fill me-1 text-danger"></i> Sedang Berlangsung
                    </a>
                    <a href="kegiatan.php?status=selesai<?= !empty($cari) ? '&cari=' . urlencode($cari) : '' ?>" 
                       class="btn btn-sm <?= ($status_filter === 'selesai') ? 'btn-green' : 'btn-outline-secondary bg-white' ?> rounded-pill px-3">
                        <i class="bi bi-check2-all me-1 text-secondary"></i> Selesai
                    </a>
                </div>
            </div>

            <!-- Form Pencarian Kata Kunci -->
            <div class="col-lg-5">
                <form action="kegiatan.php" method="GET" class="d-flex gap-2">
                    <?php if ($status_filter !== 'semua') : ?>
                        <input type="hidden" name="status" value="<?= htmlspecialchars($status_filter) ?>">
                    <?php endif; ?>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" name="cari" 
                               placeholder="Cari acara, lokasi, atau majelis..." 
                               value="<?= htmlspecialchars($cari) ?>">
                        <button type="submit" class="btn btn-green px-4">Cari</button>
                    </div>
                    <?php if (!empty($cari) || $status_filter !== 'semua') : ?>
                        <a href="kegiatan.php" class="btn btn-outline-secondary d-flex align-items-center" title="Reset Filter">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- ================= 4. DAFTAR KARTU AGENDA KEGIATAN ================= -->
<section class="py-5">
    <div class="container">
        <!-- Notifikasi Pencarian jika ada -->
        <?php if (!empty($cari)) : ?>
            <div class="alert alert-light border d-flex justify-content-between align-items-center mb-4 py-2">
                <span>Hasil pencarian kegiatan untuk: <strong class="text-success">"<?= htmlspecialchars($cari) ?>"</strong></span>
                <a href="kegiatan.php?status=<?= urlencode($status_filter) ?>" class="small text-danger text-decoration-none">Hapus Pencarian</a>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <?php if (mysqli_num_rows($query_kegiatan) > 0) : ?>
                <?php while ($row = mysqli_fetch_assoc($query_kegiatan)) : ?>
                    <?php
                    // Penguraian Tanggal untuk Kotak Kalender
                    $tgl_mulai_obj = strtotime($row['tanggal_mulai']);
                    $hari_nomor    = date('d', $tgl_mulai_obj);
                    $bulan_singkat = date('M', $tgl_mulai_obj);
                    
                    // Format Rentang Tanggal
                    $rentang_tgl = format_tgl_indo($row['tanggal_mulai']);
                    if (!empty($row['tanggal_selesai']) && $row['tanggal_selesai'] !== $row['tanggal_mulai']) {
                        $rentang_tgl .= ' — ' . format_tgl_indo($row['tanggal_selesai']);
                    }

                    // Badge Status
                    $badge_class = 'bg-secondary';
                    $status_label = 'Selesai Terlaksana';
                    if ($row['status'] === 'akan_datang') {
                        $badge_class = 'bg-warning text-dark';
                        $status_label = 'Akan Datang';
                    } elseif ($row['status'] === 'berlangsung') {
                        $badge_class = 'bg-danger text-white';
                        $status_label = 'Sedang Berlangsung';
                    }
                    ?>

                    <!-- Card Agenda Kegiatan -->
                    <div class="col-lg-6">
                        <div class="card h-100 border rounded-4 shadow-sm bg-white overflow-hidden d-flex flex-column"
                             style="transition: transform 0.3s ease, box-shadow 0.3s ease;">
                            
                            <!-- Header Bar Card: Status & Tanggal -->
                            <div class="p-4 pb-0 d-flex justify-content-between align-items-start">
                                <div class="d-flex align-items-center gap-3">
                                    <!-- Kotak Kalender Estetik -->
                                    <div class="rounded-3 text-center p-2 flex-shrink-0" 
                                         style="width: 58px; background: #fdfaf3; border: 2px solid var(--gold);">
                                        <span class="d-block text-uppercase fw-bold" style="font-size: 0.72rem; color: var(--gold);"><?= $bulan_singkat ?></span>
                                        <span class="d-block fw-bold fs-4 lh-1" style="color: var(--primary-green);"><?= $hari_nomor ?></span>
                                    </div>
                                    <div>
                                        <span class="badge <?= $badge_class ?> px-3 py-1 fw-semibold mb-1" style="font-size: 0.75rem;">
                                            <?= $status_label ?>
                                        </span>
                                        <small class="d-block text-muted">
                                            <i class="bi bi-clock me-1"></i><?= htmlspecialchars($rentang_tgl) ?>
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Konten Card -->
                            <div class="p-4 d-flex flex-column flex-grow-1">
                                <!-- Judul Kegiatan -->
                                <h5 class="fw-bold mb-2 text-dark" style="font-size: 1.25rem; line-height: 1.4;">
                                    <?= htmlspecialchars($row['nama_kegiatan']) ?>
                                </h5>

                                <!-- Lokasi Pelaksanaan -->
                                <p class="text-secondary small mb-2 d-flex align-items-center gap-1">
                                    <i class="bi bi-geo-alt-fill text-danger"></i>
                                    <span><?= htmlspecialchars($row['lokasi']) ?></span>
                                </p>

                                <!-- Penyelenggara -->
                                <p class="text-muted small mb-3 d-flex align-items-center gap-1">
                                    <i class="bi bi-building-fill text-success"></i>
                                    <span>Penyelenggara: <strong><?= htmlspecialchars($row['penyelenggara'] ?? 'PWA Jawa Tengah') ?></strong></span>
                                </p>

                                <!-- Deskripsi Singkat -->
                                <p class="small text-secondary mb-4 flex-grow-1" style="line-height: 1.6;">
                                    <?= htmlspecialchars(mb_strimwidth($row['deskripsi'], 0, 140, '...')) ?>
                                </p>

                                <!-- Tombol Aksi Detail Modal -->
                                <div class="pt-3 border-top mt-auto d-flex justify-content-between align-items-center">
                                    <button type="button" class="btn btn-outline-gold btn-sm px-3 py-2 d-flex align-items-center gap-2" 
                                            data-bs-toggle="modal" data-bs-target="#modalKegiatan<?= $row['id'] ?>">
                                        <span>Detail Agenda Lengkap</span>
                                        <i class="bi bi-arrow-right"></i>
                                    </button>

                                    <?php if ($row['status'] === 'akan_datang' || $row['status'] === 'berlangsung') : ?>
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 small">
                                            Terbuka untuk Kader
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Popup Detail Kegiatan -->
                    <div class="modal fade" id="modalKegiatan<?= $row['id'] ?>" tabindex="-1" aria-labelledby="modalLabel<?= $row['id'] ?>" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content rounded-4 border-0 overflow-hidden shadow">
                                <div class="modal-header text-white" style="background: linear-gradient(135deg, var(--primary-green-dark), var(--primary-green));">
                                    <div>
                                        <span class="badge <?= $badge_class ?> mb-1"><?= $status_label ?></span>
                                        <h5 class="modal-title fw-bold mb-0 text-white" id="modalLabel<?= $row['id'] ?>">
                                            <?= htmlspecialchars($row['nama_kegiatan']) ?>
                                        </h5>
                                    </div>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4 p-md-5 bg-white">
                                    <!-- Informasi Rincian Agenda -->
                                    <div class="row g-3 mb-4 pb-3 border-bottom">
                                        <div class="col-sm-6">
                                            <small class="text-muted d-block"><i class="bi bi-calendar3 me-1 text-success"></i> Waktu Pelaksanaan:</small>
                                            <strong class="text-dark"><?= htmlspecialchars($rentang_tgl) ?></strong>
                                        </div>
                                        <div class="col-sm-6">
                                            <small class="text-muted d-block"><i class="bi bi-geo-alt-fill me-1 text-danger"></i> Tempat / Lokasi:</small>
                                            <strong class="text-dark"><?= htmlspecialchars($row['lokasi']) ?></strong>
                                        </div>
                                        <div class="col-12">
                                            <small class="text-muted d-block"><i class="bi bi-person-badge-fill me-1 text-warning"></i> Penyelenggara Resmi:</small>
                                            <strong class="text-dark"><?= htmlspecialchars($row['penyelenggara'] ?? 'Pimpinan Wilayah Aisyiyah Jawa Tengah') ?></strong>
                                        </div>
                                    </div>

                                    <!-- Deskripsi Penuh Kegiatan -->
                                    <h6 class="fw-bold text-success mb-2">Gambaran Acara & Deskripsi</h6>
                                    <div class="text-secondary leading-relaxed mb-4">
                                        <?= nl2br(htmlspecialchars($row['deskripsi'])) ?>
                                    </div>

                                    <div class="p-3 rounded-3 bg-light border text-muted small">
                                        <i class="bi bi-info-circle-fill text-warning me-1"></i>
                                        Untuk pendaftaran atau informasi koordinasi kehadiran utusan daerah, silakan menghubungi narahubung majelis pelaksana melalui Sekretariat PWA Jawa Tengah.
                                    </div>
                                </div>
                                <div class="modal-footer bg-light border-0">
                                    <button type="button" class="btn btn-secondary btn-sm px-4" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else : ?>
                <!-- Tampilan Jika Kegiatan Tidak Ditemukan -->
                <div class="col-12 text-center py-5">
                    <div class="p-5 bg-white rounded-4 border shadow-sm mx-auto" style="max-width: 600px;">
                        <i class="bi bi-calendar-x text-muted" style="font-size: 4rem;"></i>
                        <h4 class="mt-3 text-secondary fw-bold">Tidak Ada Agenda Kegiatan</h4>
                        <p class="text-muted small mb-4">
                            Belum ada jadwal kegiatan pada status filter atau kata kunci yang Anda cari.
                        </p>
                        <a href="kegiatan.php" class="btn btn-outline-gold px-4 py-2">
                            <i class="bi bi-arrow-clockwise me-1"></i> Tampilkan Semua Agenda
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ================= 5. INFORMASI KOORDINASI AGENDA ================= -->
<section class="py-5" style="background-color: #f7f5ed;">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <div class="d-flex gap-3">
                    <div class="rounded-circle p-3 bg-success bg-opacity-10 text-success d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px;">
                        <i class="bi bi-megaphone-fill fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-1">Publikasi Kegiatan Cabang & Daerah</h5>
                        <p class="small text-muted mb-0 leading-relaxed">
                            Pimpinan Daerah (PDA) atau Cabang (PCA) di Jawa Tengah yang menyelenggarakan kegiatan dakwah dan pemberdayaan dapat mengirimkan informasi agenda melalui sekretariat wilayah untuk dipublikasikan pada portal digital ini.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="tentang.php" class="btn btn-outline-secondary btn-sm px-3 py-2">
                    <i class="bi bi-telephone-outbound-fill me-1"></i> Kontak Sekretariat PWA
                </a>
            </div>
        </div>
    </div>
</section>

<?php
include 'includes/footer.php';
?>
