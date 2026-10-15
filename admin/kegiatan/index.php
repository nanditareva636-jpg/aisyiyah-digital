<?php
/**
 * Website: Aisyiyah Digital Jawa Tengah
 * Panel Admin: Kelola Kegiatan
 */

include '../../config/database.php';

$page_title   = "Kelola Kegiatan - Admin Aisyiyah Digital";
$page_heading = "Kelola Agenda Kegiatan";
$active_menu  = "kegiatan";
$root_path    = "../../";

include '../includes/header.php';

$query = mysqli_query($conn, "SELECT * FROM kegiatan ORDER BY id DESC");
?>

<div class="card card-admin p-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h5 class="fw-bold mb-1 text-success">Daftar Agenda Kegiatan</h5>
            <small class="text-muted">Total: <?= mysqli_num_rows($query) ?> agenda</small>
        </div>
        <a href="../../kegiatan.php" target="_blank" class="btn btn-admin-green btn-sm d-inline-flex align-items-center gap-2">
            <i class="bi bi-eye"></i> Lihat Halaman Kegiatan Publik
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle border">
            <thead class="table-light">
                <tr>
                    <th style="width: 50px;" class="text-center">No</th>
                    <th>Nama Kegiatan</th>
                    <th>Tanggal Pelaksanaan</th>
                    <th>Lokasi</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                while ($k = mysqli_fetch_assoc($query)) : 
                ?>
                    <tr>
                        <td class="text-center fw-bold text-muted"><?= $no++ ?></td>
                        <td class="fw-bold text-dark"><?= htmlspecialchars($k['nama_kegiatan']) ?></td>
                        <td class="small text-secondary"><i class="bi bi-calendar3 me-1"></i><?= date('d M Y', strtotime($k['tanggal_mulai'])) ?></td>
                        <td class="small text-secondary"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($k['lokasi']) ?></td>
                        <td>
                            <span class="badge bg-warning text-dark text-capitalize"><?= str_replace('_', ' ', $k['status']) ?></span>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
include '../includes/footer.php';
?>
