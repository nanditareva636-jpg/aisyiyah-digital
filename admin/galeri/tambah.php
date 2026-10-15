<?php
/**
 * Website: Aisyiyah Digital Jawa Tengah
 * Panel Admin: Unggah Foto Galeri (admin/galeri/tambah.php)
 * Fungsi: Formulir untuk mengunggah berkas foto dokumentasi baru (CREATE)
 */

include '../../config/database.php';

$page_title   = "Unggah Foto Galeri Baru - Admin Aisyiyah Digital";
$page_heading = "Unggah Foto Galeri";
$active_menu  = "galeri";
$root_path    = "../../";

$pesan_error = "";

// Mengambil daftar kegiatan untuk opsi pilihan agenda terkait
$query_kegiatan = mysqli_query($conn, "SELECT id, nama_kegiatan, lokasi, tanggal_mulai FROM kegiatan ORDER BY tanggal_mulai DESC");

// 1. Memproses Form Saat Tombol Submit Ditekan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul        = trim($_POST['judul'] ?? '');
    $deskripsi    = trim($_POST['deskripsi'] ?? '');
    $tanggal_foto = !empty($_POST['tanggal_foto']) ? $_POST['tanggal_foto'] : date('Y-m-d');
    $kegiatan_id  = !empty($_POST['kegiatan_id']) ? intval($_POST['kegiatan_id']) : null;

    // Validasi Judul
    if (empty($judul)) {
        $pesan_error = "Judul dokumentasi foto wajib diisi!";
    } elseif (!isset($_FILES['foto']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
        $pesan_error = "Silakan pilih berkas file foto yang ingin diunggah!";
    } else {
        // 2. Memproses Berkas Foto yang Diunggah
        $file_tmp  = $_FILES['foto']['tmp_name'];
        $file_name = $_FILES['foto']['name'];
        $file_size = $_FILES['foto']['size'];
        $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $ekstensi_valid = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($file_ext, $ekstensi_valid)) {
            $pesan_error = "Format file tidak valid! Harap gunakan file gambar JPG, JPEG, PNG, atau WEBP.";
        } elseif ($file_size > 5 * 1024 * 1024) { // Maksimal 5MB
            $pesan_error = "Ukuran file terlalu besar! Maksimal ukuran file adalah 5 MB.";
        } else {
            // Membuat nama file acak unik agar tidak bentrok
            $nama_file_foto = 'galeri_' . time() . '_' . uniqid() . '.' . $file_ext;
            $tujuan_upload  = '../../assets/uploads/galeri/' . $nama_file_foto;

            // Memindahkan file ke folder tujuan
            if (move_uploaded_file($file_tmp, $tujuan_upload)) {
                // 3. Menyimpan Data ke Database Menggunakan Prepared Statement
                $sql = "INSERT INTO galeri (judul, deskripsi, foto, tanggal_foto, kegiatan_id) 
                        VALUES (?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "ssssi", $judul, $deskripsi, $nama_file_foto, $tanggal_foto, $kegiatan_id);

                if (mysqli_stmt_execute($stmt)) {
                    $_SESSION['sukses'] = "Foto dokumentasi \"$judul\" berhasil diunggah!";
                    header("Location: index.php");
                    exit;
                } else {
                    $pesan_error = "Gagal menyimpan data ke database: " . mysqli_error($conn);
                }
                mysqli_stmt_close($stmt);
            } else {
                $pesan_error = "Gagal memindahkan file foto ke folder server. Pastikan izin folder uploads aktif.";
            }
        }
    }
}

include '../includes/header.php';
?>

<!-- Kartu Formulir Unggah Foto -->
<div class="card card-admin p-4">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h5 class="fw-bold mb-0 text-success">
            <i class="bi bi-cloud-arrow-up-fill me-2"></i>Formulir Unggah Foto Kegiatan
        </h5>
        <a href="index.php" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i> Kembali ke Galeri
        </a>
    </div>

    <!-- Alert Notifikasi Error -->
    <?php if (!empty($pesan_error)) : ?>
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <div><?= htmlspecialchars($pesan_error) ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Form Unggah Foto -->
    <form action="tambah.php" method="POST" enctype="multipart/form-data">
        <div class="row g-3">
            <!-- Judul Dokumentasi -->
            <div class="col-md-8">
                <label for="judul" class="form-label fw-bold">Judul Dokumentasi Foto <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="judul" name="judul" 
                       placeholder="Contoh: Pemeriksaan Kesehatan Gratis Lansia di Balai Desa" 
                       value="<?= htmlspecialchars($_POST['judul'] ?? '') ?>" required autofocus>
            </div>

            <!-- Tanggal Pengambilan Foto -->
            <div class="col-md-4">
                <label for="tanggal_foto" class="form-label fw-bold">Tanggal Kegiatan</label>
                <input type="date" class="form-control" id="tanggal_foto" name="tanggal_foto" 
                       value="<?= htmlspecialchars($_POST['tanggal_foto'] ?? date('Y-m-d')) ?>">
            </div>

            <!-- Hubungkan dengan Agenda Kegiatan -->
            <div class="col-md-6">
                <label for="kegiatan_id" class="form-label fw-bold">Terkait dengan Agenda Acara (Opsional)</label>
                <select class="form-select" id="kegiatan_id" name="kegiatan_id">
                    <option value="">-- Bukan Agenda Khusus / Dokumentasi Umum --</option>
                    <?php while ($kg = mysqli_fetch_assoc($query_kegiatan)) : ?>
                        <option value="<?= $kg['id'] ?>" <?= (isset($_POST['kegiatan_id']) && $_POST['kegiatan_id'] == $kg['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($kg['nama_kegiatan']) ?> (<?= htmlspecialchars($kg['lokasi']) ?>)
                        </option>
                    <?php endwhile; ?>
                </select>
                <small class="text-muted">Pilih jika foto ini merupakan bagian dari agenda kegiatan yang terdaftar.</small>
            </div>

            <!-- File Foto -->
            <div class="col-md-6">
                <label for="foto" class="form-label fw-bold">Pilih Berkas Foto <span class="text-danger">*</span></label>
                <input class="form-control" type="file" id="foto" name="foto" accept="image/*" required onchange="previewImage(event)">
                <small class="text-muted d-block">Format: JPG, JPEG, PNG, atau WEBP. Maksimal 5 MB.</small>
            </div>

            <!-- Area Live Preview Gambar yang Dipilih -->
            <div class="col-12" id="previewContainer" style="display: none;">
                <label class="form-label fw-bold text-muted small">Pratinjau Foto yang Dipilih:</label>
                <div class="p-2 border rounded-3 bg-light d-inline-block">
                    <img id="imagePreview" src="#" alt="Pratinjau Foto" style="max-height: 180px; max-width: 100%; object-fit: contain; border-radius: 8px;">
                </div>
            </div>

            <!-- Deskripsi / Keterangan Foto -->
            <div class="col-12">
                <label for="deskripsi" class="form-label fw-bold">Deskripsi / Keterangan Dokumentasi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" 
                          placeholder="Jelaskan mengenai suasana kegiatan, siapa yang hadir, lokasi, atau pesan penting dari foto tersebut..."><?= htmlspecialchars($_POST['deskripsi'] ?? '') ?></textarea>
            </div>

            <!-- Tombol Aksi Simpan -->
            <div class="col-12 pt-3 border-top d-flex gap-2">
                <button type="submit" class="btn btn-admin-green px-4 py-2 d-flex align-items-center gap-2">
                    <i class="bi bi-cloud-arrow-up-fill"></i>
                    <span>Unggah & Publikasikan</span>
                </button>
                <a href="index.php" class="btn btn-outline-secondary px-3 py-2">Batal</a>
            </div>
        </div>
    </form>
</div>

<!-- Skrip Sederhana Pratinjau Foto -->
<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function() {
        const preview = document.getElementById('imagePreview');
        const container = document.getElementById('previewContainer');
        preview.src = reader.result;
        container.style.display = 'block';
    }
    if (event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
    }
}
</script>

<?php
include '../includes/footer.php';
?>
