<?php
/**
 * Website: Aisyiyah Digital Jawa Tengah
 * Panel Admin: Kelola Galeri (admin/galeri/index.php)
 * Fungsi: Menampilkan daftar foto dokumentasi (READ) dengan tombol aksi tambah, edit, dan hapus
 */

include '../../config/database.php';

$page_title   = "Kelola Galeri - Admin Aisyiyah Digital";
$page_heading = "Manajemen Galeri Dokumentasi Foto";
$active_menu  = "galeri";
$root_path    = "../../";

include '../includes/header.php';

// Mengambil seluruh data foto dari database diurutkan dari yang paling baru
$query = mysqli_query($conn, "SELECT g.*, k.nama_kegiatan 
                              FROM galeri g 
                              LEFT JOIN kegiatan k ON g.kegiatan_id = k.id 
                              ORDER BY g.id DESC");
?>

<!-- Kartu Utama -->
<div class="card card-admin p-4">
    <!-- Header Tabel & Tombol Tambah -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h5 class="fw-bold mb-1 text-success">
                <i class="bi bi-images me-2"></i>Daftar Koleksi Foto Dokumentasi
            </h5>
            <small class="text-muted">Total Foto Terunggah: <strong><?= mysqli_num_rows($query) ?></strong> item</small>
        </div>
        <a href="tambah.php" class="btn btn-admin-green d-inline-flex align-items-center gap-2 px-3 py-2">
            <i class="bi bi-cloud-arrow-up-fill"></i>
            <span>Unggah Foto Baru</span>
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

    <!-- Tabel Data Galeri -->
    <div class="table-responsive">
        <table class="table table-hover align-middle border">
            <thead class="table-light">
                <tr>
                    <th style="width: 50px;" class="text-center">No</th>
                    <th style="width: 100px;" class="text-center">Foto</th>
                    <th>Judul Dokumentasi</th>
                    <th>Agenda Acara Terkait</th>
                    <th>Tanggal Foto</th>
                    <th style="width: 160px;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($query) > 0) : ?>
                    <?php 
                    $no = 1;
                    while ($foto = mysqli_fetch_assoc($query)) : 
                        $path_file = '../../assets/uploads/galeri/' . $foto['foto'];
                        $ada_file  = !empty($foto['foto']) && file_exists($path_file);
                    ?>
                        <tr>
                            <!-- Nomor Urut -->
                            <td class="text-center fw-bold text-muted"><?= $no++ ?></td>

                            <!-- Thumbnail Foto -->
                            <td class="text-center">
                                <?php if ($ada_file) : ?>
                                    <a href="<?= $path_file ?>" target="_blank" title="Lihat ukuran asli">
                                        <img src="<?= $path_file ?>" 
                                             alt="<?= htmlspecialchars($foto['judul']) ?>" 
                                             class="rounded-3 border shadow-sm" 
                                             style="width: 64px; height: 50px; object-fit: cover;">
                                    </a>
                                <?php else : ?>
                                    <div class="rounded-3 bg-light border d-inline-flex align-items-center justify-content-center text-muted" 
                                         style="width: 64px; height: 50px;" title="File foto belum ada di server">
                                        <i class="bi bi-image fs-4"></i>
                                    </div>
                                <?php endif; ?>
                            </td>

                            <!-- Judul & Keterangan Foto -->
                            <td>
                                <strong class="text-dark d-block mb-1"><?= htmlspecialchars($foto['judul']) ?></strong>
                                <small class="text-muted d-block" style="line-height: 1.4;">
                                    <?= htmlspecialchars(mb_strimwidth($foto['deskripsi'] ?? 'Tidak ada keterangan.', 0, 80, "...")) ?>
                                </small>
                            </td>

                            <!-- Agenda Terkait -->
                            <td>
                                <?php if (!empty($foto['nama_kegiatan'])) : ?>
                                    <span class="badge bg-warning bg-opacity-25 text-dark border border-warning border-opacity-50 px-2 py-1 small">
                                        <i class="bi bi-calendar-event me-1 text-warning"></i>
                                        <?= htmlspecialchars(mb_strimwidth($foto['nama_kegiatan'], 0, 30, "...")) ?>
                                    </span>
                                <?php else : ?>
                                    <span class="badge bg-light text-muted border px-2 py-1 small">
                                        Umum / Mandiri
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Tanggal Foto -->
                            <td class="text-secondary small">
                                <?php if (!empty($foto['tanggal_foto'])) : ?>
                                    <i class="bi bi-calendar3 me-1"></i>
                                    <?= date('d M Y', strtotime($foto['tanggal_foto'])) ?>
                                <?php else : ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>

                            <!-- Tombol Aksi (Edit & Hapus) -->
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="edit.php?id=<?= $foto['id'] ?>" class="btn btn-outline-primary" title="Edit Data Foto">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <a href="hapus.php?id=<?= $foto['id'] ?>" 
                                       class="btn btn-outline-danger" 
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus foto \'<?= addslashes($foto['judul']) ?>\'?')" 
                                       title="Hapus Foto">
                                        <i class="bi bi-trash3-fill"></i> Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-images fs-1 d-block mb-2 text-secondary opacity-50"></i>
                            <h6 class="fw-bold text-secondary">Belum Ada Foto Dokumentasi</h6>
                            <p class="small text-muted mb-3">Koleksi foto kegiatan akan tampil di sini setelah Anda mengunggahnya.</p>
                            <a href="tambah.php" class="btn btn-admin-green btn-sm">
                                <i class="bi bi-cloud-arrow-up-fill me-1"></i> Unggah Foto Sekarang
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
