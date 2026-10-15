<?php
/**
 * Website: Aisyiyah Digital Jawa Tengah
 * Panel Admin: Kelola Pustaka (admin/pustaka/index.php)
 * Fungsi: Menampilkan katalog arsip dokumen, buku panduan, dan foto sampul (READ)
 */

include '../../config/database.php';

$page_title   = "Kelola Pustaka - Admin Aisyiyah Digital";
$page_heading = "Manajemen Pustaka Digital & Arsip Dokumen";
$active_menu  = "pustaka";
$root_path    = "../../";

include '../includes/header.php';

// Mengambil seluruh data pustaka beserta nama kategorinya
$query = mysqli_query($conn, "SELECT p.*, k.nama_kategori 
                              FROM pustaka p 
                              LEFT JOIN kategori k ON p.kategori_id = k.id 
                              ORDER BY p.id DESC");
?>

<!-- Kartu Utama -->
<div class="card card-admin p-4">
    <!-- Header Tabel & Tombol Tambah -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h5 class="fw-bold mb-1 text-success">
                <i class="bi bi-book-half me-2"></i>Daftar Arsip & Buku Digital
            </h5>
            <small class="text-muted">Total Koleksi Pustaka: <strong><?= mysqli_num_rows($query) ?></strong> dokumen</small>
        </div>
        <a href="tambah.php" class="btn btn-admin-green d-inline-flex align-items-center gap-2 px-3 py-2">
            <i class="bi bi-plus-circle-fill"></i>
            <span>Tambah Dokumen Baru</span>
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

    <!-- Tabel Data Pustaka -->
    <div class="table-responsive">
        <table class="table table-hover align-middle border">
            <thead class="table-light">
                <tr>
                    <th style="width: 50px;" class="text-center">No</th>
                    <th style="width: 80px;" class="text-center">Sampul</th>
                    <th>Judul Buku & Penulis</th>
                    <th>Kategori</th>
                    <th>Tahun</th>
                    <th>Berkas Unduhan</th>
                    <th style="width: 160px;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($query) > 0) : ?>
                    <?php 
                    $no = 1;
                    while ($doc = mysqli_fetch_assoc($query)) : 
                        $path_cover = '../../assets/uploads/pustaka/' . $doc['cover'];
                        $ada_cover  = !empty($doc['cover']) && file_exists($path_cover);

                        $path_file  = '../../assets/uploads/pustaka/' . $doc['file_dokumen'];
                        $ada_file   = !empty($doc['file_dokumen']) && file_exists($path_file);
                    ?>
                        <tr>
                            <!-- Nomor Urut -->
                            <td class="text-center fw-bold text-muted"><?= $no++ ?></td>

                            <!-- Foto Sampul (Cover) -->
                            <td class="text-center">
                                <?php if ($ada_cover) : ?>
                                    <a href="<?= $path_cover ?>" target="_blank" title="Lihat Sampul">
                                        <img src="<?= $path_cover ?>" 
                                             alt="<?= htmlspecialchars($doc['judul']) ?>" 
                                             class="rounded border shadow-sm" 
                                             style="width: 50px; height: 68px; object-fit: cover;">
                                    </a>
                                <?php else : ?>
                                    <div class="rounded bg-light border d-inline-flex flex-column align-items-center justify-content-center text-muted" 
                                         style="width: 50px; height: 68px;" title="Belum ada foto sampul">
                                        <i class="bi bi-journal-text fs-4 text-warning"></i>
                                    </div>
                                <?php endif; ?>
                            </td>

                            <!-- Judul & Penulis -->
                            <td>
                                <strong class="text-dark d-block mb-1" style="font-size: 0.95rem;">
                                    <?= htmlspecialchars($doc['judul']) ?>
                                </strong>
                                <small class="text-muted d-block">
                                    <i class="bi bi-person me-1"></i><?= htmlspecialchars($doc['penulis'] ?? 'PWA Jateng') ?>
                                </small>
                            </td>

                            <!-- Kategori -->
                            <td>
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 small">
                                    <?= htmlspecialchars($doc['nama_kategori'] ?? 'Pustaka Umum') ?>
                                </span>
                            </td>

                            <!-- Tahun Terbit -->
                            <td class="text-muted small">
                                <?= htmlspecialchars($doc['tahun_terbit'] ?? '-') ?>
                            </td>

                            <!-- Status Berkas Dokumen -->
                            <td>
                                <?php if ($ada_file) : ?>
                                    <a href="<?= $path_file ?>" target="_blank" download class="badge bg-danger text-white text-decoration-none px-2 py-1 d-inline-flex align-items-center gap-1 mb-1">
                                        <i class="bi bi-file-earmark-pdf-fill"></i>
                                        <span>PDF Unduh</span>
                                    </a>
                                <?php else : ?>
                                    <span class="badge bg-secondary px-2 py-1 d-inline-block mb-1">
                                        <i class="bi bi-file-earmark"></i> Naskah Arsip
                                    </span>
                                <?php endif; ?>
                                <small class="text-muted d-block" style="font-size: 0.75rem;">
                                    <i class="bi bi-download me-1 text-success"></i><?= $doc['download_count'] ?> kali diunduh
                                </small>
                            </td>

                            <!-- Tombol Aksi (Edit & Hapus) -->
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="edit.php?id=<?= $doc['id'] ?>" class="btn btn-outline-primary" title="Ubah Data">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <a href="hapus.php?id=<?= $doc['id'] ?>" 
                                       class="btn btn-outline-danger" 
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus arsip \'<?= addslashes($doc['judul']) ?>\'?')" 
                                       title="Hapus Data">
                                        <i class="bi bi-trash3-fill"></i> Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-book fs-1 d-block mb-2 text-secondary opacity-50"></i>
                            <h6 class="fw-bold text-secondary">Belum Ada Dokumen Pustaka</h6>
                            <p class="small text-muted mb-3">Arsip buku panduan dan naskah digital akan tampil di sini setelah Anda menambahkannya.</p>
                            <a href="tambah.php" class="btn btn-admin-green btn-sm">
                                <i class="bi bi-plus-circle-fill me-1"></i> Tambah Dokumen Sekarang
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
