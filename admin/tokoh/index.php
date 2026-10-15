<?php
/**
 * Website: Aisyiyah Digital Jawa Tengah
 * Panel Admin: Kelola Tokoh (admin/tokoh/index.php)
 * Fungsi: Menampilkan seluruh daftar tokoh (READ)
 */

include '../../config/database.php';

$page_title   = "Kelola Tokoh - Admin Aisyiyah Digital";
$page_heading = "Daftar Tokoh Pejuang Aisyiyah";
$active_menu  = "tokoh";
$root_path    = "../../";

include '../includes/header.php';

// Mengambil seluruh data tokoh dari database, diurutkan dari yang terbaru
$query = mysqli_query($conn, "SELECT * FROM tokoh ORDER BY id DESC");
?>

<!-- Kartu Utama -->
<div class="card card-admin p-4">
    <!-- Header Tabel & Tombol Tambah -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h5 class="fw-bold mb-1 text-success">Data Tokoh Terdaftar</h5>
            <small class="text-muted">Total Tokoh: <?= mysqli_num_rows($query) ?> orang</small>
        </div>
        <a href="tambah.php" class="btn btn-admin-green d-inline-flex align-items-center gap-2 px-3 py-2">
            <i class="bi bi-person-plus-fill"></i>
            <span>Tambah Tokoh Baru</span>
        </a>
    </div>

    <!-- Notifikasi Pesan Sukses (Flash Message) -->
    <?php if (isset($_SESSION['sukses'])) : ?>
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div><?= htmlspecialchars($_SESSION['sukses']) ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['sukses']); ?>
    <?php endif; ?>

    <!-- Tabel Data Tokoh -->
    <div class="table-responsive">
        <table class="table table-hover align-middle border">
            <thead class="table-light">
                <tr>
                    <th style="width: 50px;" class="text-center">No</th>
                    <th style="width: 90px;" class="text-center">Foto</th>
                    <th>Nama & Gelar</th>
                    <th>Peran / Jabatan</th>
                    <th>Periode Wafat</th>
                    <th style="width: 160px;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($query) > 0) : ?>
                    <?php 
                    $no = 1;
                    while ($tokoh = mysqli_fetch_assoc($query)) : 
                    ?>
                        <tr>
                            <!-- Nomor Urut -->
                            <td class="text-center fw-bold text-muted"><?= $no++ ?></td>

                            <!-- Foto Tokoh -->
                            <td class="text-center">
                                <?php if (!empty($tokoh['foto']) && file_exists('../../assets/uploads/tokoh/' . $tokoh['foto'])) : ?>
                                    <img src="../../assets/uploads/tokoh/<?= htmlspecialchars($tokoh['foto']) ?>" 
                                         alt="<?= htmlspecialchars($tokoh['nama']) ?>" 
                                         class="rounded-circle border" 
                                         style="width: 48px; height: 48px; object-fit: cover;">
                                <?php else : ?>
                                    <div class="rounded-circle bg-light border d-inline-flex align-items-center justify-content-center text-secondary" 
                                         style="width: 48px; height: 48px;">
                                        <i class="bi bi-person-fill fs-4"></i>
                                    </div>
                                <?php endif; ?>
                            </td>

                            <!-- Nama Tokoh -->
                            <td>
                                <strong class="text-dark d-block"><?= htmlspecialchars($tokoh['nama']) ?></strong>
                                <small class="text-muted">
                                    <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($tokoh['tempat_lahir'] ?? '-') ?>
                                </small>
                            </td>

                            <!-- Peran / Jabatan -->
                            <td>
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">
                                    <?= htmlspecialchars($tokoh['peran_jabatan'] ?? 'Tokoh Aisyiyah') ?>
                                </span>
                            </td>

                            <!-- Wafat -->
                            <td class="text-muted small">
                                <?= htmlspecialchars($tokoh['wafat'] ?? '-') ?>
                            </td>

                            <!-- Tombol Aksi (Edit & Hapus) -->
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- Tombol Edit -->
                                    <a href="edit.php?id=<?= $tokoh['id'] ?>" class="btn btn-outline-primary" title="Ubah Data">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <!-- Tombol Hapus -->
                                    <a href="hapus.php?id=<?= $tokoh['id'] ?>" 
                                       class="btn btn-outline-danger" 
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus data tokoh \'<?= addslashes($tokoh['nama']) ?>\'?')" 
                                       title="Hapus Data">
                                        <i class="bi bi-trash3-fill"></i> Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-1"></i>
                            Belum ada data tokoh. Silakan klik tombol "Tambah Tokoh Baru".
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
