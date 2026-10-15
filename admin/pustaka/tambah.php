<?php
/**
 * Website: Aisyiyah Digital Jawa Tengah
 * Panel Admin: Tambah Dokumen Pustaka (admin/pustaka/tambah.php)
 * Fungsi: Formulir untuk menambah dokumen, mengunggah foto sampul & berkas PDF (CREATE)
 */

include '../../config/database.php';

$page_title   = "Tambah Dokumen Pustaka Baru - Admin Aisyiyah Digital";
$page_heading = "Tambah Dokumen Pustaka";
$active_menu  = "pustaka";
$root_path    = "../../";

$pesan_error = "";

// Mengambil daftar kategori khusus tipe pustaka atau umum
$query_kategori = mysqli_query($conn, "SELECT id, nama_kategori FROM kategori WHERE tipe = 'pustaka' OR tipe IS NULL ORDER BY nama_kategori ASC");

// 1. Memproses Form Saat Tombol Submit Ditekan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul        = trim($_POST['judul'] ?? '');
    $kategori_id  = !empty($_POST['kategori_id']) ? intval($_POST['kategori_id']) : null;
    $penulis      = trim($_POST['penulis'] ?? '');
    $tahun_terbit = trim($_POST['tahun_terbit'] ?? '');
    $deskripsi    = trim($_POST['deskripsi'] ?? '');

    if (empty($judul)) {
        $pesan_error = "Judul buku / dokumen pustaka wajib diisi!";
    } else {
        $nama_file_cover   = null;
        $nama_file_dokumen = 'dokumen_default.pdf'; // nilai bawaan jika belum ada file fisik

        // 2. Memproses Unggahan Foto Sampul (Cover)
        if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
            $cover_tmp  = $_FILES['cover']['tmp_name'];
            $cover_name = $_FILES['cover']['name'];
            $cover_ext  = strtolower(pathinfo($cover_name, PATHINFO_EXTENSION));

            $ekstensi_gambar = ['jpg', 'jpeg', 'png', 'webp'];

            if (in_array($cover_ext, $ekstensi_gambar)) {
                $nama_file_cover = 'cover_' . time() . '_' . uniqid() . '.' . $cover_ext;
                $tujuan_cover    = '../../assets/uploads/pustaka/' . $nama_file_cover;
                move_uploaded_file($cover_tmp, $tujuan_cover);
            } else {
                $pesan_error = "Format foto sampul tidak valid! Gunakan JPG, JPEG, PNG, atau WEBP.";
            }
        }

        // 3. Memproses Unggahan Berkas Dokumen (PDF/DOC)
        if (empty($pesan_error) && isset($_FILES['file_dokumen']) && $_FILES['file_dokumen']['error'] === UPLOAD_ERR_OK) {
            $doc_tmp  = $_FILES['file_dokumen']['tmp_name'];
            $doc_name = $_FILES['file_dokumen']['name'];
            $doc_ext  = strtolower(pathinfo($doc_name, PATHINFO_EXTENSION));

            $ekstensi_doc = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'zip'];

            if (in_array($doc_ext, $ekstensi_doc)) {
                $nama_file_dokumen = 'pustaka_' . time() . '_' . uniqid() . '.' . $doc_ext;
                $tujuan_doc        = '../../assets/uploads/pustaka/' . $nama_file_dokumen;
                move_uploaded_file($doc_tmp, $tujuan_doc);
            } else {
                $pesan_error = "Format berkas dokumen tidak didukung! Gunakan format PDF, DOC, DOCX, atau ZIP.";
            }
        }

        // 4. Menyimpan ke Database dengan Prepared Statement
        if (empty($pesan_error)) {
            $sql = "INSERT INTO pustaka (judul, kategori_id, penulis, tahun_terbit, deskripsi, cover, file_dokumen) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sisssss", $judul, $kategori_id, $penulis, $tahun_terbit, $deskripsi, $nama_file_cover, $nama_file_dokumen);

            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['sukses'] = "Dokumen pustaka \"$judul\" berhasil ditambahkan!";
                header("Location: index.php");
                exit;
            } else {
                $pesan_error = "Gagal menyimpan data ke database: " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
        }
    }
}

include '../includes/header.php';
?>

<!-- Kartu Formulir Tambah Pustaka -->
<div class="card card-admin p-4">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h5 class="fw-bold mb-0 text-success">
            <i class="bi bi-journal-plus me-2"></i>Formulir Tambah Dokumen & Foto Sampul
        </h5>
        <a href="index.php" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i> Kembali ke Pustaka
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

    <!-- Form Input Pustaka -->
    <form action="tambah.php" method="POST" enctype="multipart/form-data">
        <div class="row g-3">
            <!-- Judul Buku / Dokumen -->
            <div class="col-md-8">
                <label for="judul" class="form-label fw-bold">Judul Buku / Naskah Dokumen <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="judul" name="judul" 
                       placeholder="Contoh: Buku Panduan Perkaderan Aisyiyah Abad Kedua" 
                       value="<?= htmlspecialchars($_POST['judul'] ?? '') ?>" required autofocus>
            </div>

            <!-- Kategori Pustaka -->
            <div class="col-md-4">
                <label for="kategori_id" class="form-label fw-bold">Kategori Dokumen</label>
                <select class="form-select" id="kategori_id" name="kategori_id">
                    <option value="">-- Pilih Kategori --</option>
                    <?php while ($kat = mysqli_fetch_assoc($query_kategori)) : ?>
                        <option value="<?= $kat['id'] ?>" <?= (isset($_POST['kategori_id']) && $_POST['kategori_id'] == $kat['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($kat['nama_kategori']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Penulis / Penyusun -->
            <div class="col-md-6">
                <label for="penulis" class="form-label fw-bold">Penulis / Majelis Penyusun</label>
                <input type="text" class="form-control" id="penulis" name="penulis" 
                       placeholder="Contoh: PWA Jawa Tengah / Majelis Tarjih" 
                       value="<?= htmlspecialchars($_POST['penulis'] ?? '') ?>">
            </div>

            <!-- Tahun Terbit -->
            <div class="col-md-6">
                <label for="tahun_terbit" class="form-label fw-bold">Tahun Terbit / Penyusunan</label>
                <input type="text" class="form-control" id="tahun_terbit" name="tahun_terbit" 
                       placeholder="Contoh: 2026 atau 1928" 
                       value="<?= htmlspecialchars($_POST['tahun_terbit'] ?? date('Y')) ?>">
            </div>

            <!-- Upload Foto Sampul (Cover) -->
            <div class="col-md-6">
                <label for="cover" class="form-label fw-bold">Foto Sampul Buku (Cover)</label>
                <input class="form-control" type="file" id="cover" name="cover" accept="image/*" onchange="previewCover(event)">
                <small class="text-muted d-block">Pilih gambar JPG, PNG, atau WEBP. Maksimal 3 MB.</small>
            </div>

            <!-- Upload Berkas Dokumen (PDF) -->
            <div class="col-md-6">
                <label for="file_dokumen" class="form-label fw-bold">Berkas Naskah / Dokumen (PDF)</label>
                <input class="form-control" type="file" id="file_dokumen" name="file_dokumen" accept=".pdf,.doc,.docx,.zip">
                <small class="text-muted d-block">Format file: PDF, DOCX, atau ZIP untuk diunduh pengunjung.</small>
            </div>

            <!-- Pratinjau Foto Sampul yang Dipilih -->
            <div class="col-12" id="previewCoverContainer" style="display: none;">
                <label class="form-label fw-bold text-muted small">Pratinjau Foto Sampul:</label>
                <div class="p-2 border rounded-3 bg-light d-inline-block">
                    <img id="coverPreview" src="#" alt="Pratinjau Sampul" style="max-height: 160px; object-fit: contain; border-radius: 6px;">
                </div>
            </div>

            <!-- Deskripsi Singkat Dokumen -->
            <div class="col-12">
                <label for="deskripsi" class="form-label fw-bold">Deskripsi / Ringkasan Isi Buku</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" 
                          placeholder="Jelaskan ringkasan materi, pedoman dasar, atau latar belakang penerbitan dokumen ini..."><?= htmlspecialchars($_POST['deskripsi'] ?? '') ?></textarea>
            </div>

            <!-- Tombol Simpan -->
            <div class="col-12 pt-3 border-top d-flex gap-2">
                <button type="submit" class="btn btn-admin-green px-4 py-2 d-flex align-items-center gap-2">
                    <i class="bi bi-save-fill"></i>
                    <span>Simpan Dokumen Pustaka</span>
                </button>
                <a href="index.php" class="btn btn-outline-secondary px-3 py-2">Batal</a>
            </div>
        </div>
    </form>
</div>

<!-- Skrip Pratinjau Foto Sampul -->
<script>
function previewCover(event) {
    const reader = new FileReader();
    reader.onload = function() {
        const preview = document.getElementById('coverPreview');
        const container = document.getElementById('previewCoverContainer');
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
