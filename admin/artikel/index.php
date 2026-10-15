<?php
/**
 * Website: Aisyiyah Digital Jawa Tengah
 * Panel Admin: Kelola Artikel (admin/artikel/index.php)
 * Fungsi: Menampilkan tabel daftar artikel, berita, dan opini publikasi (READ)
 */

include '../../config/database.php';

$page_title   = "Kelola Artikel - Admin Aisyiyah Digital";
$page_heading = "Manajemen Artikel & Berita Publikasi";
$active_menu  = "artikel";
$root_path    = "../../";

include '../includes/header.php';

// Mengambil seluruh data artikel beserta nama kategori dan nama admin penulisnya
$query = mysqli_query($conn, "SELECT a.*, k.nama_kategori, adm.nama_lengkap 
                              FROM artikel a 
                              LEFT JOIN kategori k ON a.kategori_id = k.id 
                              LEFT JOIN admins adm ON a.admin_id = adm.id 
                              ORDER BY a.id DESC");
?>

<!-- Kartu Utama -->
<div class="card card-admin p-4">
    <!-- Header Tabel & Tombol Tambah -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h5 class="fw-bold mb-1 text-success">
                <i class="bi bi-journal-richtext me-2"></i>Daftar Artikel & Berita Terpublikasi
            </h5>
            <small class="text-muted">Total Tulisan: <strong><?= mysqli_num_rows($query) ?></strong> artikel</small>
        </div>
        <a href="tambah.php" class="btn btn-admin-green d-inline-flex align-items-center gap-2 px-3 py-2">
            <i class="bi bi-pencil-fill"></i>
            <span>Tulis Artikel Baru</span>
        </a>
    </div>

    <!-- Notifikasi Sukses (Flash Message) -->
    <?php if (isset($_SESSION['sukses'])) : ?>
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div><?= htmlspecialchars($_SESSION['sukses']) ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['sukses']); ?>
    <?php endif; ?>

    <!-- Tabel Data Artikel -->
    <div class="table-responsive">
        <table class="table table-hover align-middle border">
            <thead class="table-light">
                <tr>
                    <th style="width: 50px;" class="text-center">No</th>
                    <th style="width: 90px;" class="text-center">Gambar</th>
                    <th>Judul Tulisan</th>
                    <th>Kategori</th>
                    <th>Penulis</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Dibaca</th>
                    <th style="width: 160px;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($query) > 0) : ?>
                    <?php 
                    $no = 1;
                    while ($art = mysqli_fetch_assoc($query)) : 
                        $path_img = '../../assets/uploads/artikel/' . $art['gambar'];
                        $ada_img  = !empty($art['gambar']) && file_exists($path_img);
                    ?>
                        <tr>
                            <!-- Nomor Urut -->
                            <td class="text-center fw-bold text-muted"><?= $no++ ?></td>

                            <!-- Gambar Sampul -->
                            <td class="text-center">
                                <?php if ($ada_img) : ?>
                                    <a href="<?= $path_img ?>" target="_blank" title="Lihat Foto">
                                        <img src="<?= $path_img ?>" 
                                             alt="<?= htmlspecialchars($art['judul']) ?>" 
                                             class="rounded-3 border shadow-sm" 
                                             style="width: 60px; height: 45px; object-fit: cover;">
                                    </a>
                                <?php else : ?>
                                    <div class="rounded-3 bg-light border d-inline-flex align-items-center justify-content-center text-muted" 
                                         style="width: 60px; height: 45px;" title="Belum ada gambar">
                                        <i class="bi bi-newspaper fs-4 text-success"></i>
                                    </div>
                                <?php endif; ?>
                            </td>

                            <!-- Judul Artikel -->
                            <td>
                                <strong class="text-dark d-block mb-1" style="font-size: 0.95rem; line-height: 1.35;">
                                    <?= htmlspecialchars($art['judul']) ?>
                                </strong>
                                <small class="text-muted">
                                    <i class="bi bi-calendar3 me-1"></i><?= date('d M Y, H:i', strtotime($art['created_at'])) ?> WIB
                                </small>
                            </td>

                            <!-- Kategori -->
                            <td>
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 small">
                                    <?= htmlspecialchars($art['nama_kategori'] ?? 'Kabar Umum') ?>
                                </span>
                            </td>

                            <!-- Penulis -->
                            <td class="small text-secondary">
                                <?= htmlspecialchars($art['nama_lengkap'] ?? 'Admin PWA') ?>
                            </td>

                            <!-- Status -->
                            <td class="text-center">
                                <?php if ($art['status'] === 'published') : ?>
                                    <span class="badge bg-success px-2 py-1">
                                        <i class="bi bi-check-circle me-1"></i>Tayang
                                    </span>
                                <?php else : ?>
                                    <span class="badge bg-secondary px-2 py-1">
                                        <i class="bi bi-file-earmark me-1"></i>Draf
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Counter Views -->
                            <td class="text-center small text-muted">
                                <i class="bi bi-eye me-1 text-primary"></i><?= $art['views'] ?>x
                            </td>

                            <!-- Tombol Aksi (Edit & Hapus) -->
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="edit.php?id=<?= $art['id'] ?>" class="btn btn-outline-primary" title="Ubah Artikel">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <a href="hapus.php?id=<?= $art['id'] ?>" 
                                       class="btn btn-outline-danger" 
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus artikel \'<?= addslashes($art['judul']) ?>\'?')" 
                                       title="Hapus Artikel">
                                        <i class="bi bi-trash3-fill"></i> Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-journal-x fs-1 d-block mb-2 text-secondary opacity-50"></i>
                            <h6 class="fw-bold text-secondary">Belum Ada Artikel yang Diterbitkan</h6>
                            <p class="small text-muted mb-3">Tulis dan publikasikan kabar dakwah terkini Aisyiyah Jawa Tengah.</p>
                            <a href="tambah.php" class="btn btn-admin-green btn-sm">
                                <i class="bi bi-pencil-fill me-1"></i> Tulis Artikel Sekarang
                            </a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
include '../includes/footer.php';
?>
