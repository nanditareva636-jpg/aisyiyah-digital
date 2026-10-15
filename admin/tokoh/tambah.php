<?php
/**
 * Website: Aisyiyah Digital Jawa Tengah
 * Panel Admin: Tambah Tokoh (admin/tokoh/tambah.php)
 * Fungsi: Menampilkan formulir dan menyimpan data tokoh baru (CREATE)
 */

include '../../config/database.php';

$page_title   = "Tambah Tokoh Baru - Admin Aisyiyah Digital";
$page_heading = "Tambah Tokoh Baru";
$active_menu  = "tokoh";
$root_path    = "../../";

$pesan_error = "";

// 1. Memeriksa apakah form telah dikirim melalui metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mengambil dan membersihkan input dari form
    $nama            = trim($_POST['nama'] ?? '');
    $peran_jabatan   = trim($_POST['peran_jabatan'] ?? '');
    $tempat_lahir    = trim($_POST['tempat_lahir'] ?? '');
    $tanggal_lahir   = !empty($_POST['tanggal_lahir']) ? $_POST['tanggal_lahir'] : null;
    $wafat           = trim($_POST['wafat'] ?? '-');
    $biografi        = trim($_POST['biografi'] ?? '');
    $jasa_perjuangan = trim($_POST['jasa_perjuangan'] ?? '');
    
    // Validasi sederhana: Nama dan Biografi wajib diisi
    if (empty($nama) || empty($biografi)) {
        $pesan_error = "Nama tokoh dan biografi wajib diisi!";
    } else {
        $nama_file_foto = null;

        // 2. Memproses Upload File Foto jika ada
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $file_tmp  = $_FILES['foto']['tmp_name'];
            $file_name = $_FILES['foto']['name'];
            $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            // Ekstensi gambar yang diizinkan
            $ekstensi_valid = ['jpg', 'jpeg', 'png', 'webp'];

            if (in_array($file_ext, $ekstensi_valid)) {
                // Membuat nama file acak agar tidak bentrok
                $nama_file_foto = time() . '_' . uniqid() . '.' . $file_ext;
                $tujuan_upload  = '../../assets/uploads/tokoh/' . $nama_file_foto;

                // Memindahkan file dari memori sementara ke folder assets
                move_uploaded_file($file_tmp, $tujuan_upload);
            } else {
                $pesan_error = "Format file foto tidak valid! Gunakan JPG, JPEG, PNG, atau WEBP.";
            }
        }

        // Jika tidak ada error, simpan data ke database menggunakan Prepared Statement
        if (empty($pesan_error)) {
            // 3. Menyiapkan Query INSERT dengan Tanda Tanya (?)
            $sql = "INSERT INTO tokoh (nama, peran_jabatan, tempat_lahir, tanggal_lahir, wafat, biografi, jasa_perjuangan, foto) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            // 4. Mempersiapkan Statement
            $stmt = mysqli_prepare($conn, $sql);

            // 5. Mengikat Variabel ke Parameter (?) -> s = string
            mysqli_stmt_bind_param($stmt, "ssssssss", 
                $nama, 
                $peran_jabatan, 
                $tempat_lahir, 
                $tanggal_lahir, 
                $wafat, 
                $biografi, 
                $jasa_perjuangan, 
                $nama_file_foto
            );

            // 6. Mengeksekusi Query ke MySQL
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['sukses'] = "Tokoh baru \"$nama\" berhasil disimpan!";
                header("Location: index.php");
                exit;
            } else {
                $pesan_error = "Gagal menyimpan data ke database: " . mysqli_error($conn);
            }

            // Menutup statement
            mysqli_stmt_close($stmt);
        }
    }
}

include '../includes/header.php';
?>

<!-- Kartu Formulir Tambah Tokoh -->
<div class="card card-admin p-4">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h5 class="fw-bold mb-0 text-success">
            <i class="bi bi-person-plus-fill me-2"></i>Formulir Data Tokoh
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

    <!-- Form Input (Wajib menggunakan enctype="multipart/form-data" untuk upload berkas/foto) -->
    <form action="tambah.php" method="POST" enctype="multipart/form-data">
        <div class="row g-3">
            <!-- Nama Lengkap Tokoh -->
            <div class="col-md-6">
                <label for="nama" class="form-label fw-bold">Nama Tokoh <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nama" name="nama" 
                       placeholder="Contoh: Nyai Hj. Ahmad Dahlan (Siti Walidah)" 
                       value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>" required>
            </div>

            <!-- Peran / Gelar / Jabatan -->
            <div class="col-md-6">
                <label for="peran_jabatan" class="form-label fw-bold">Peran / Jabatan / Gelar</label>
                <input type="text" class="form-control" id="peran_jabatan" name="peran_jabatan" 
                       placeholder="Contoh: Pendiri Aisyiyah & Pahlawan Nasional" 
                       value="<?= htmlspecialchars($_POST['peran_jabatan'] ?? '') ?>">
            </div>

            <!-- Tempat Lahir -->
            <div class="col-md-4">
                <label for="tempat_lahir" class="form-label fw-bold">Tempat Lahir</label>
                <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" 
                       placeholder="Contoh: Kauman, Yogyakarta" 
                       value="<?= htmlspecialchars($_POST['tempat_lahir'] ?? '') ?>">
            </div>

            <!-- Tanggal Lahir -->
            <div class="col-md-4">
                <label for="tanggal_lahir" class="form-label fw-bold">Tanggal Lahir</label>
                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" 
                       value="<?= htmlspecialchars($_POST['tanggal_lahir'] ?? '') ?>">
            </div>

            <!-- Keterangan Wafat -->
            <div class="col-md-4">
                <label for="wafat" class="form-label fw-bold">Keterangan Wafat / Periode</label>
                <input type="text" class="form-control" id="wafat" name="wafat" 
                       placeholder="Contoh: Yogyakarta, 31 Mei 1946 (atau '-' jika masih ada)" 
                       value="<?= htmlspecialchars($_POST['wafat'] ?? '-') ?>">
            </div>

            <!-- Biografi Lengkap -->
            <div class="col-12">
                <label for="biografi" class="form-label fw-bold">Biografi / Riwayat Hidup <span class="text-danger">*</span></label>
                <textarea class="form-control" id="biografi" name="biografi" rows="5" 
                          placeholder="Tuliskan kisah perjalanan hidup, latar belakang keluarga, pendidikan, dan proses bergabungnya beliau..." required><?= htmlspecialchars($_POST['biografi'] ?? '') ?></textarea>
            </div>

            <!-- Jasa & Peran Perjuangan -->
            <div class="col-12">
                <label for="jasa_perjuangan" class="form-label fw-bold">Jasa & Kontribusi Perjuangan</label>
                <textarea class="form-control" id="jasa_perjuangan" name="jasa_perjuangan" rows="4" 
                          placeholder="Tuliskan karya nyata, amal usaha yang dirintis, atau peran penting beliau dalam gerakan Aisyiyah di Jawa Tengah..."><?= htmlspecialchars($_POST['jasa_perjuangan'] ?? '') ?></textarea>
            </div>

            <!-- Upload Foto Tokoh -->
            <div class="col-md-6">
                <label for="foto" class="form-label fw-bold">Foto Tokoh</label>
                <input class="form-control" type="file" id="foto" name="foto" accept="image/*">
                <small class="text-muted">Format: JPG, JPEG, PNG, atau WEBP. Maksimal 2MB.</small>
            </div>

            <!-- Tombol Simpan -->
            <div class="col-12 pt-3 border-top d-flex gap-2">
                <button type="submit" class="btn btn-admin-green px-4 py-2 d-flex align-items-center gap-2">
                    <i class="bi bi-save-fill"></i> Simpan Tokoh
                </button>
                <a href="index.php" class="btn btn-outline-secondary px-3 py-2">Batal</a>
            </div>
        </div>
    </form>
</div>

<?php
include '../includes/footer.php';
?>
