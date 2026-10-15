<?php
/**
 * Website: Aisyiyah Digital Jawa Tengah
 * Panel Admin: Edit Foto Galeri (admin/galeri/edit.php)
 * Fungsi: Mengubah keterangan foto atau mengganti berkas gambar (UPDATE)
 */

include '../../config/database.php';

$page_title   = "Edit Foto Galeri - Admin Aisyiyah Digital";
$page_heading = "Edit Foto Galeri";
$active_menu  = "galeri";
$root_path    = "../../";

$pesan_error = "";

// 1. Mengambil ID Foto dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 2. Mengambil Data Foto yang Akan Diedit dengan Prepared Statement
$stmt_get = mysqli_prepare($conn, "SELECT * FROM galeri WHERE id = ?");
mysqli_stmt_bind_param($stmt_get, "i", $id);
mysqli_stmt_execute($stmt_get);
$result = mysqli_stmt_get_result($stmt_get);
$foto   = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt_get);

if (!$foto) {
    header("Location: index.php");
    exit;
}

// Mengambil daftar kegiatan untuk opsi pilihan agenda
$query_kegiatan = mysqli_query($conn, "SELECT id, nama_kegiatan, lokasi, tanggal_mulai FROM kegiatan ORDER BY tanggal_mulai DESC");

// 3. Memproses Formulir Saat Tombol Submit Ditekan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul        = trim($_POST['judul'] ?? '');
    $deskripsi    = trim($_POST['deskripsi'] ?? '');
    $tanggal_foto = !empty($_POST['tanggal_foto']) ? $_POST['tanggal_foto'] : date('Y-m-d');
    $kegiatan_id  = !empty($_POST['kegiatan_id']) ? intval($_POST['kegiatan_id']) : null;

    // Nama file foto bawaan (tetap menggunakan foto lama jika tidak diganti)
    $nama_file_foto = $foto['foto'];

    if (empty($judul)) {
        $pesan_error = "Judul dokumentasi foto tidak boleh dikosongkan!";
    } else {
        // 4. Memeriksa Apakah Pengguna Mengunggah Foto Baru
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $file_tmp  = $_FILES['foto']['tmp_name'];
            $file_name = $_FILES['foto']['name'];
            $file_size = $_FILES['foto']['size'];
            $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $ekstensi_valid = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($file_ext, $ekstensi_valid)) {
                $pesan_error = "Format file tidak valid! Harap gunakan gambar JPG, JPEG, PNG, atau WEBP.";
            } elseif ($file_size > 5 * 1024 * 1024) {
                $pesan_error = "Ukuran file terlalu besar! Maksimal ukuran file adalah 5 MB.";
            } else {
                // Buat nama baru untuk foto baru
                $nama_file_foto_baru = 'galeri_' . time() . '_' . uniqid() . '.' . $file_ext;
                $tujuan_upload       = '../../assets/uploads/galeri/' . $nama_file_foto_baru;

                if (move_uploaded_file($file_tmp, $tujuan_upload)) {
                    // Hapus foto lama jika ada fisiknya di folder
                    if (!empty($foto['foto']) && file_exists('../../assets/uploads/galeri/' . $foto['foto'])) {
                        unlink('../../assets/uploads/galeri/' . $foto['foto']);
                    }
                    $nama_file_foto = $nama_file_foto_baru;
                } else {
                    $pesan_error = "Gagal memindahkan file foto baru ke folder server.";
                }
            }
        }

        // 5. Simpan Perubahan ke MySQL jika tidak ada error
        if (empty($pesan_error)) {
            $sql = "UPDATE galeri SET 
                        judul = ?, 
                        deskripsi = ?, 
                        foto = ?, 
                        tanggal_foto = ?, 
                        kegiatan_id = ? 
                    WHERE id = ?";
            $stmt_update = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt_update, "ssssii", $judul, $deskripsi, $nama_file_foto, $tanggal_foto, $kegiatan_id, $id);

            if (mysqli_stmt_execute($stmt_update)) {
                $_SESSION['sukses'] = "Data foto \"$judul\" berhasil diperbarui!";
                header("Location: index.php");
                exit;
            } else {
                $pesan_error = "Gagal memperbarui data: " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt_update);
        }
    }
}

include '../includes/header.php';
$path_foto_lama = '../../assets/uploads/galeri/' . $foto['foto'];
$ada_foto_lama  = !empty($foto['foto']) && file_exists($path_foto_lama);
?>

<!-- Kartu Formulir Edit Foto -->
<div class="card card-admin p-4">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h5 class="fw-bold mb-0 text-success">
            <i class="bi bi-pencil-square me-2"></i>Edit Foto: <?= htmlspecialchars($foto['judul']) ?>
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

    <form action="edit.php?id=<?= $id ?>" method="POST" enctype="multipart/form-data">
        <div class="row g-3">
            <!-- Judul Dokumentasi -->
            <div class="col-md-8">
                <label for="judul" class="form-label fw-bold">Judul Dokumentasi Foto <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="judul" name="judul" 
                       value="<?= htmlspecialchars($_POST['judul'] ?? $foto['judul']) ?>" required>
            </div>

            <!-- Tanggal Pengambilan Foto -->
            <div class="col-md-4">
                <label for="tanggal_foto" class="form-label fw-bold">Tanggal Kegiatan</label>
                <input type="date" class="form-control" id="tanggal_foto" name="tanggal_foto" 
                       value="<?= htmlspecialchars($_POST['tanggal_foto'] ?? $foto['tanggal_foto']) ?>">
            </div>

            <!-- Hubungkan dengan Agenda Kegiatan -->
            <div class="col-md-6">
                <label for="kegiatan_id" class="form-label fw-bold">Terkait dengan Agenda Acara</label>
                <select class="form-select" id="kegiatan_id" name="kegiatan_id">
                    <option value="">-- Bukan Agenda Khusus / Dokumentasi Umum --</option>
                    <?php 
                    $selected_kegiatan = $_POST['kegiatan_id'] ?? $foto['kegiatan_id'];
                    while ($kg = mysqli_fetch_assoc($query_kegiatan)) : 
                    ?>
                        <option value="<?= $kg['id'] ?>" <?= ($selected_kegiatan == $kg['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($kg['nama_kegiatan']) ?> (<?= htmlspecialchars($kg['lokasi']) ?>)
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Ganti Foto Baru -->
            <div class="col-md-6">
                <label for="foto" class="form-label fw-bold">Ganti Berkas Foto (Opsional)</label>
                <input class="form-control" type="file" id="foto" name="foto" accept="image/*" onchange="previewImageEdit(event)">
                <small class="text-muted d-block">Biarkan kosong jika tidak ingin mengganti file gambar.</small>
            </div>

            <!-- Foto Saat Ini & Pratinjau Baru -->
            <div class="col-12">
                <div class="p-3 border rounded-3 bg-light d-flex flex-wrap align-items-center gap-4">
                    <div>
                        <small class="text-muted d-block fw-bold mb-1">Foto Aktif Saat Ini:</small>
                        <?php if ($ada_foto_lama) : ?>
                            <img src="<?= $path_foto_lama ?>" alt="Foto Lama" 
                                 style="max-height: 120px; border-radius: 8px; border: 1px solid #ccc;">
                        <?php else : ?>
                            <span class="badge bg-secondary">File tidak ditemukan di server</span>
                        <?php endif; ?>
                    </div>

                    <div id="previewEditContainer" style="display: none;">
                        <small class="text-success d-block fw-bold mb-1">Foto Pengganti yang Dipilih:</small>
                        <img id="imagePreviewEdit" src="#" alt="Foto Pengganti" 
                             style="max-height: 120px; border-radius: 8px; border: 2px solid var(--admin-gold);">
                    </div>
                </div>
            </div>

            <!-- Deskripsi Foto -->
            <div class="col-12">
                <label for="deskripsi" class="form-label fw-bold">Deskripsi / Keterangan Dokumentasi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4"><?= htmlspecialchars($_POST['deskripsi'] ?? $foto['deskripsi']) ?></textarea>
            </div>

            <!-- Tombol Aksi Simpan -->
            <div class="col-12 pt-3 border-top d-flex gap-2">
                <button type="submit" class="btn btn-admin-green px-4 py-2 d-flex align-items-center gap-2">
                    <i class="bi bi-check-lg"></i>
                    <span>Simpan Perubahan</span>
                </button>
                <a href="index.php" class="btn btn-outline-secondary px-3 py-2">Batal</a>
            </div>
        </div>
    </form>
</div>

<!-- Skrip Pratinjau Foto Edit -->
<script>
function previewImageEdit(event) {
    const reader = new FileReader();
    reader.onload = function() {
        const preview = document.getElementById('imagePreviewEdit');
        const container = document.getElementById('previewEditContainer');
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
