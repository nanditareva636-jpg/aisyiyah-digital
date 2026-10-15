<?php
/**
 * Website: Aisyiyah Digital Jawa Tengah
 * Halaman: Galeri Foto Dokumentasi (galeri.php)
 * Fungsi: Menampilkan rekam visual dokumentasi kegiatan dan aksi nyata dari database MySQL
 */

include 'config/database.php';

$page_title = "Galeri Dokumentasi - Aisyiyah Digital Jawa Tengah";
include 'includes/header.php';
include 'includes/navbar.php';

// 1. Menangkap Parameter Pencarian
$cari = isset($_GET['cari']) ? trim($_GET['cari']) : '';

// 2. Menyusun Kueri SQL
$where_clauses = [];
if (!empty($cari)) {
    $cari_safe = mysqli_real_escape_string($conn, $cari);
    $where_clauses[] = "(g.judul LIKE '%$cari_safe%' OR g.deskripsi LIKE '%$cari_safe%' OR k.nama_kegiatan LIKE '%$cari_safe%')";
}

$where_sql = !empty($where_clauses) ? "WHERE " . implode(" AND ", $where_clauses) : "";

$sql = "SELECT g.*, k.nama_kegiatan 
        FROM galeri g 
        LEFT JOIN kegiatan k ON g.kegiatan_id = k.id 
        $where_sql 
        ORDER BY g.id DESC";
$query_galeri = mysqli_query($conn, $sql);

// Menghitung total dokumentasi
$total_foto = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM galeri"))['total'] ?? 0;

// Format tanggal Indonesia
function format_tgl_galeri($tgl) {
    if (empty($tgl)) return '';
    $bulan_indo = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    $pecah = explode('-', $tgl);
    if (count($pecah) !== 3) return $tgl;
    return (int)$pecah[2] . ' ' . ($bulan_indo[(int)$pecah[1]] ?? '') . ' ' . $pecah[0];
}
?>

<!-- ================= 1. BANNER HEADER ================= -->
<section class="py-5 position-relative overflow-hidden" 
         style="background: linear-gradient(135deg, var(--primary-green-dark) 0%, var(--primary-green) 100%); color: #fff;">
    <div class="container position-relative py-3 text-center">
        <span class="badge bg-warning text-dark px-3 py-2 fw-bold mb-2">Dokumentasi Visual</span>
        <h1 class="text-white fw-bold mb-3 display-5">Galeri Aktivitas Aisyiyah Jawa Tengah</h1>
        <p class="text-white-50 mx-auto mb-0" style="max-width: 720px; font-size: 1.1rem; line-height: 1.8;">
            Rekam jejak visual dinamika dakwah, bakti sosial kemanusiaan, pendidikan generasi, dan pemberdayaan perempuan berkemajuan di 35 kabupaten dan kota se-Jawa Tengah.
        </p>
    </div>
</section>

<!-- ================= 2. BREADCRUMB ================= -->
<div class="bg-white border-bottom py-3">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="index.php" class="text-success text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item active text-muted" aria-current="page">Galeri</li>
            </ol>
        </nav>
    </div>
</div>

<!-- ================= 3. BAR PENCARIAN & STATISTIK ================= -->
<section class="py-4" style="background-color: #f7f5ed; border-bottom: 1px solid var(--border-color);">
    <div class="container">
        <div class="row g-3 align-items-center justify-content-between">
            <!-- Form Pencarian -->
            <div class="col-lg-6">
                <form action="galeri.php" method="GET" class="d-flex gap-2">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" name="cari" 
                               placeholder="Cari dokumentasi foto, acara, atau kegiatan..." 
                               value="<?= htmlspecialchars($cari) ?>">
                        <button type="submit" class="btn btn-green px-4">Cari</button>
                    </div>
                    <?php if (!empty($cari)) : ?>
                        <a href="galeri.php" class="btn btn-outline-secondary d-flex align-items-center" title="Hapus Pencarian">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Counter Koleksi -->
            <div class="col-lg-5 text-lg-end">
                <span class="badge bg-white text-dark border p-2 px-3 fw-semibold shadow-sm">
                    <i class="bi bi-camera-fill text-success me-1"></i>
                    Menampilkan: <strong><?= mysqli_num_rows($query_galeri) ?></strong> dari total <?= $total_foto ?> Dokumentasi Foto
                </span>
            </div>
        </div>
    </div>
</section>

<!-- ================= 4. GRID FOTO GALERI ================= -->
<section class="py-5">
    <div class="container">
        <!-- Notifikasi Pencarian jika aktif -->
        <?php if (!empty($cari)) : ?>
            <div class="alert alert-light border d-flex justify-content-between align-items-center mb-4 py-2">
                <span>Hasil pencarian untuk: <strong class="text-success">"<?= htmlspecialchars($cari) ?>"</strong></span>
                <a href="galeri.php" class="small text-danger text-decoration-none">Tampilkan Semua Foto</a>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <?php if (mysqli_num_rows($query_galeri) > 0) : ?>
                <?php while ($foto = mysqli_fetch_assoc($query_galeri)) : ?>
                    <?php
                    $path_foto = 'assets/uploads/galeri/' . $foto['foto'];
                    $ada_file  = !empty($foto['foto']) && file_exists($path_foto);
                    ?>
                    <!-- Kolom Kartu Galeri -->
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 border rounded-4 shadow-sm bg-white overflow-hidden d-flex flex-column"
                             style="transition: transform 0.3s ease, box-shadow 0.3s ease;">
                            
                            <!-- Area Visual Foto -->
                            <div class="position-relative overflow-hidden" style="height: 230px; background: linear-gradient(135deg, #0d472a 0%, #1a5e38 100%);">
                                <?php if ($ada_file) : ?>
                                    <img src="<?= $path_foto ?>" 
                                         alt="<?= htmlspecialchars($foto['judul']) ?>" 
                                         class="w-100 h-100" style="object-fit: cover; transition: transform 0.4s ease;">
                                <?php else : ?>
                                    <!-- Placeholder Ikonik Islami jika belum ada file fisik -->
                                    <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-center p-3 text-white">
                                        <div class="rounded-circle p-3 mb-2" style="background: rgba(197, 160, 89, 0.25); border: 2px solid var(--gold);">
                                            <i class="bi bi-images fs-2 text-warning"></i>
                                        </div>
                                        <span class="small fw-bold text-white-50 text-uppercase" style="letter-spacing: 0.08em; font-size: 0.72rem;">Dokumentasi Aisyiyah</span>
                                    </div>
                                <?php endif; ?>

                                <!-- Badge Tanggal Foto -->
                                <?php if (!empty($foto['tanggal_foto'])) : ?>
                                    <span class="badge position-absolute top-0 start-0 m-3 px-3 py-2 fw-semibold" 
                                          style="background-color: rgba(13, 71, 42, 0.85); backdrop-filter: blur(5px); border: 1px solid var(--gold); color: #fff; font-size: 0.75rem;">
                                        <i class="bi bi-calendar-event me-1 text-warning"></i>
                                        <?= format_tgl_galeri($foto['tanggal_foto']) ?>
                                    </span>
                                <?php endif; ?>

                                <!-- Tombol Perbesar / Lightbox -->
                                <button type="button" class="btn btn-sm btn-light rounded-circle position-absolute bottom-0 end-0 m-3 d-flex align-items-center justify-content-center shadow" 
                                        style="width: 38px; height: 38px;"
                                        data-bs-toggle="modal" data-bs-target="#modalFoto<?= $foto['id'] ?>"
                                        title="Perbesar Foto">
                                    <i class="bi bi-arrows-fullscreen text-success"></i>
                                </button>
                            </div>

                            <!-- Area Konten Teks -->
                            <div class="p-4 d-flex flex-column flex-grow-1">
                                <!-- Hubungan dengan Agenda Acara jika ada -->
                                <?php if (!empty($foto['nama_kegiatan'])) : ?>
                                    <span class="badge bg-warning bg-opacity-25 text-dark border border-warning border-opacity-50 align-self-start mb-2 small">
                                        <i class="bi bi-bookmark-fill me-1 text-warning"></i>
                                        <?= htmlspecialchars(mb_strimwidth($foto['nama_kegiatan'], 0, 35, "...")) ?>
                                    </span>
                                <?php endif; ?>

                                <!-- Judul Foto -->
                                <h5 class="fw-bold mb-2 text-dark" style="font-size: 1.15rem; line-height: 1.4;">
                                    <?= htmlspecialchars($foto['judul']) ?>
                                </h5>

                                <!-- Keterangan / Deskripsi Foto -->
                                <p class="small text-secondary mb-3 flex-grow-1" style="line-height: 1.6;">
                                    <?= htmlspecialchars(mb_strimwidth($foto['deskripsi'] ?? 'Dokumentasi resmi aktivitas dakwah dan pemberdayaan perempuan.', 0, 110, '...')) ?>
                                </p>

                                <!-- Tombol Lihat Selengkapnya -->
                                <div class="pt-3 border-top mt-auto">
                                    <button type="button" class="btn btn-outline-gold btn-sm w-100 py-2 d-flex align-items-center justify-content-center gap-2"
                                            data-bs-toggle="modal" data-bs-target="#modalFoto<?= $foto['id'] ?>">
                                        <i class="bi bi-eye-fill"></i>
                                        <span>Lihat Rincian Foto</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Popup Detail Foto & Deskripsi Penuh -->
                    <div class="modal fade" id="modalFoto<?= $foto['id'] ?>" tabindex="-1" aria-labelledby="labelFoto<?= $foto['id'] ?>" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content rounded-4 border-0 overflow-hidden shadow">
                                <div class="modal-header text-white" style="background: linear-gradient(135deg, var(--primary-green-dark), var(--primary-green));">
                                    <h5 class="modal-title fw-bold text-white mb-0" id="labelFoto<?= $foto['id'] ?>">
                                        <?= htmlspecialchars($foto['judul']) ?>
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-0">
                                    <?php if ($ada_file) : ?>
                                        <img src="<?= $path_foto ?>" alt="<?= htmlspecialchars($foto['judul']) ?>" class="w-100" style="max-height: 480px; object-fit: cover;">
                                    <?php else : ?>
                                        <div class="p-5 text-center text-white" style="background: linear-gradient(135deg, #0d472a, #165b37);">
                                            <i class="bi bi-images fs-1 text-warning mb-2 d-block"></i>
                                            <h5>Dokumentasi Arsip Foto Aisyiyah</h5>
                                        </div>
                                    <?php endif; ?>

                                    <div class="p-4 p-md-5 bg-white">
                                        <div class="d-flex flex-wrap gap-3 mb-3 pb-3 border-bottom text-muted small">
                                            <?php if (!empty($foto['tanggal_foto'])) : ?>
                                                <div>
                                                    <i class="bi bi-calendar3 text-success me-1"></i> Tanggal: 
                                                    <strong class="text-dark"><?= format_tgl_galeri($foto['tanggal_foto']) ?></strong>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (!empty($foto['nama_kegiatan'])) : ?>
                                                <div>
                                                    <i class="bi bi-calendar-check text-warning me-1"></i> Acara Terkait: 
                                                    <strong class="text-dark"><?= htmlspecialchars($foto['nama_kegiatan']) ?></strong>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <h6 class="fw-bold text-success mb-2">Keterangan Dokumentasi:</h6>
                                        <p class="text-secondary leading-relaxed mb-0">
                                            <?= nl2br(htmlspecialchars($foto['deskripsi'] ?? 'Tidak ada keterangan tambahan.')) ?>
                                        </p>
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
                <!-- Tampilan Jika Foto Tidak Ditemukan -->
                <div class="col-12 text-center py-5">
                    <div class="p-5 bg-white rounded-4 border shadow-sm mx-auto" style="max-width: 600px;">
                        <i class="bi bi-image text-muted" style="font-size: 4rem;"></i>
                        <h4 class="mt-3 text-secondary fw-bold">Tidak Ada Foto Ditemukan</h4>
                        <p class="text-muted small mb-4">
                            Belum ada dokumentasi visual pada kata kunci pencarian yang Anda masukkan.
                        </p>
                        <a href="galeri.php" class="btn btn-outline-gold px-4 py-2">
                            <i class="bi bi-arrow-clockwise me-1"></i> Tampilkan Semua Koleksi
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
