<?php
/**
 * Website: Aisyiyah Digital Jawa Tengah
 * Panel Admin: Kelola Sejarah & Timeline
 */

include '../../config/database.php';

$page_title   = "Kelola Sejarah - Admin Aisyiyah Digital";
$page_heading = "Kelola Sejarah & Timeline";
$active_menu  = "sejarah";
$root_path    = "../../";

include '../includes/header.php';

$query = mysqli_query($conn, "SELECT * FROM sejarah ORDER BY urutan ASC");
?>

<div class="card card-admin p-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h5 class="fw-bold mb-1 text-success">Daftar Peristiwa & Timeline Sejarah</h5>
            <small class="text-muted">Total: <?= mysqli_num_rows($query) ?> peristiwa</small>
        </div>
        <a href="../../timeline.php" target="_blank" class="btn btn-admin-green btn-sm d-inline-flex align-items-center gap-2">
            <i class="bi bi-eye"></i> Lihat Halaman Timeline
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle border">
            <thead class="table-light">
                <tr>
                    <th style="width: 70px;" class="text-center">Urutan</th>
                    <th style="width: 110px;">Tahun</th>
                    <th>Judul Peristiwa</th>
                    <th>Deskripsi Singkat</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($s = mysqli_fetch_assoc($query)) : ?>
                    <tr>
                        <td class="text-center fw-bold text-muted"><?= $s['urutan'] ?></td>
                        <td><span class="badge bg-success"><?= htmlspecialchars($s['tahun']) ?></span></td>
                        <td class="fw-bold text-dark"><?= htmlspecialchars($s['judul']) ?></td>
                        <td class="small text-secondary"><?= htmlspecialchars($s['deskripsi_singkat']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
include '../includes/footer.php';
?>
