<?php
/**
 * Website: Aisyiyah Digital Jawa Tengah
 * Panel Admin: Edit Tokoh (admin/tokoh/edit.php)
 * Fungsi: Mengubah data tokoh yang sudah tersimpan (UPDATE)
 */

include '../../config/database.php';

$page_title   = "Edit Data Tokoh - Admin Aisyiyah Digital";
$page_heading = "Edit Data Tokoh";
$active_menu  = "tokoh";
$root_path    = "../../";

$pesan_error = "";

// 1. Mengambil ID Tokoh dari URL (misal: edit.php?id=1)
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 2. Mengambil Data Tokoh yang akan diedit menggunakan Prepared Statement
$stmt_get = mysqli_prepare($conn, "SELECT * FROM tokoh WHERE id = ?");
mysqli_stmt_bind_param($stmt_get, "i", $id);
mysqli_stmt_execute($stmt_get);
$result = mysqli_stmt_get_result($stmt_get);
$tokoh = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt_get);

// Jika ID tidak ditemukan di database, kembalikan ke index.php
if (!$tokoh) {
    header("Location: index.php");
    exit;
}

// 3. Memproses Formulir Saat Tombol "Simpan Perubahan" Ditekan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama            = trim($_POST['nama'] ?? '');
    $peran_jabatan   = trim($_POST['peran_jabatan'] ?? '');
    $tempat_lahir    = trim($_POST['tempat_lahir'] ?? '');
    $tanggal_lahir   = !empty($_POST['tanggal_lahir']) ? $_POST['tanggal_lahir'] : null;
    $wafat           = trim($_POST['wafat'] ?? '-');
    $biografi        = trim($_POST['biografi'] ?? '');
    $jasa_perjuangan = trim($_POST['jasa_perjuangan'] ?? '');
    
    // Foto tetap menggunakan foto lama secara bawaan
    $nama_file_foto  = $tokoh['foto'];

    if (empty($nama) || empty($biografi)) {
        $pesan_error = "Nama tokoh dan biografi tidak boleh dikosongkan!";
    } else {
        // 4. Memeriksa Apakah Pengguna Mengunggah Foto Baru
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $file_tmp  = $_FILES['foto']['tmp_name'];
            $file_name = $_FILES['foto']['name'];
            $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $ekstensi_valid = ['jpg', 'jpeg', 'png', 'webp'];

            if (in_array($file_ext, $ekstensi_valid)) {
                // Buat nama baru untuk foto yang diunggah
                $nama_file_foto_baru = time() . '_' . uniqid() . '.' . $file_ext;
                $tujuan_upload       = '../../assets/uploads/tokoh/' . $nama_file_foto_baru;

                if (move_uploaded_file($file_tmp, $tujuan_upload)) {
                    // Hapus foto lama dari server jika ada fisiknya
                    if (!empty($tokoh['foto']) && file_exists('../../assets/uploads/tokoh/' . $tokoh['foto'])) {
                        unlink('../../assets/uploads/tokoh/' . $tokoh['foto']);
                    }
                    // Ganti nama file yang akan disimpan ke database
                    $nama_file_foto = $nama_file_foto_baru;
                }
            } else {
                $pesan_error = "Format file foto baru tidak valid! Gunakan JPG, JPEG, PNG, atau WEBP.";
            }
        }

        // 5. Simpan Perubahan ke Database menggunakan Prepared Statement UPDATE
        if (empty($pesan_error)) {
            $sql = "UPDATE tokoh SET 
                        nama = ?, 
                        peran_jabatan = ?, 
                        tempat_lahir = ?, 
                        tanggal_lahir = ?, 
                        wafat = ?, 
                        biografi = ?, 
                        jasa_perjuangan = ?, 
                        foto = ? 
                    WHERE id = ?";

            $stmt_update = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt_update, "ssssssssi", 
                $nama, 
                $peran_jabatan, 
                $tempat_lahir, 
                $tanggal_lahir, 
                $wafat, 
                $biografi, 
                $jasa_perjuangan, 
                $nama_file_foto,
                $id
            );

            if (mysqli_stmt_execute($stmt_update)) {
                $_SESSION['sukses'] = "Data tokoh \"$nama\" berhasil diperbarui!";
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
?>

<!-- Kartu Formulir Edit Tokoh -->
<div class="card card-admin p-4">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h5 class="fw-bold mb-0 text-success">
            <i class="bi bi-pencil-square me-2"></i>Perbarui Data: <?= htmlspecialchars($tokoh['nama']) ?>
        </h5>
        <a href="index.php" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>

    <!-- Alert Notifikasi Error jika ada -->
    <?php if (!empty($pesan_error)) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?= htmlspecialchars($pesan_error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form action="edit.php?id=<?= $id ?>" method="POST" enctype="multipart/form-data">
        <div class="row g-3">
            <!-- Nama Lengkap Tokoh -->
            <div class="col-md-6">
                <label for="nama" class="form-label fw-bold">Nama Tokoh <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nama" name="nama" 
                       value="<?= htmlspecialchars($_POST['nama'] ?? $tokoh['nama']) ?>" required>
            </div>

            <!-- Peran / Jabatan -->
            <div class="col-md-6">
                <label for="peran_jabatan" class="form-label fw-bold">Peran / Jabatan / Gelar</label>
                <input type="text" class="form-control" id="peran_jabatan" name="peran_jabatan" 
                       value="<?= htmlspecialchars($_POST['peran_jabatan'] ?? $tokoh['peran_jabatan']) ?>">
            </div>

            <!-- Tempat Lahir -->
            <div class="col-md-4">
                <label for="tempat_lahir" class="form-label fw-bold">Tempat Lahir</label>
                <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" 
                       value="<?= htmlspecialchars($_POST['tempat_lahir'] ?? $tokoh['tempat_lahir']) ?>">
            </div>

            <!-- Tanggal Lahir -->
            <div class="col-md-4">
                <label for="tanggal_lahir" class="form-label fw-bold">Tanggal Lahir</label>
                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" 
                       value="<?= htmlspecialchars($_POST['tanggal_lahir'] ?? $tokoh['tanggal_lahir']) ?>">
            </div>

            <!-- Keterangan Wafat -->
            <div class="col-md-4">
                <label for="wafat" class="form-label fw-bold">Keterangan Wafat / Periode</label>
                <input type="text" class="form-control" id="wafat" name="wafat" 
                       value="<?= htmlspecialchars($_POST['wafat'] ?? $tokoh['wafat']) ?>">
            </div>

            <!-- Biografi Lengkap -->
            <div class="col-12">
                <label for="biografi" class="form-label fw-bold">Biografi / Riwayat Hidup <span class="text-danger">*</span></label>
                <textarea class="form-control" id="biografi" name="biografi" rows="5" required><?= htmlspecialchars($_POST['biografi'] ?? $tokoh['biografi']) ?></textarea>
            </div>

            <!-- Jasa & Kontribusi -->
            <div class="col-12">
                <label for="jasa_perjuangan" class="form-label fw-bold">Jasa & Kontribusi Perjuangan</label>
                <textarea class="form-control" id="jasa_perjuangan" name="jasa_perjuangan" rows="4"><?= htmlspecialchars($_POST['jasa_perjuangan'] ?? $tokoh['jasa_perjuangan']) ?></textarea>
            </div>

            <!-- Upload Foto Baru & Tampilan Foto Lama -->
            <div class="col-md-6">
                <label for="foto" class="form-label fw-bold">Ganti Foto Tokoh</label>
                <input class="form-control mb-2" type="file" id="foto" name="foto" accept="image/*">
                <small class="text-muted d-block mb-3">Biarkan kosong jika tidak ingin mengubah foto yang sudah ada.</small>

                <!-- Menampilkan thumbnail foto saat ini jika ada -->
                <?php if (!empty($tokoh['foto']) && file_exists('../../assets/uploads/tokoh/' . $tokoh['foto'])) : ?>
                    <div class="p-2 border rounded d-inline-flex align-items-center gap-2 bg-light">
                        <img src="../../assets/uploads/tokoh/<?= htmlspecialchars($tokoh['foto']) ?>" 
                             alt="Foto Saat Ini" style="width: 50px; height: 50px; object-fit: cover;" class="rounded">
                        <small class="text-muted">Foto aktif saat ini</small>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Tombol Simpan Perubahan -->
            <div class="col-12 pt-3 border-top d-flex gap-2">
                <button type="submit" class="btn btn-admin-green px-4 py-2 d-flex align-items-center gap-2">
                    <i class="bi bi-check-lg"></i> Simpan Perubahan
                </button>
                <a href="index.php" class="btn btn-outline-secondary px-3 py-2">Batal</a>
            </div>
        </div>
    </form>
</div>

<?php
include '../includes/footer.php';
?>
