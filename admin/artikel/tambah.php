<?php
/**
 * Website: Aisyiyah Digital Jawa Tengah
 * Panel Admin: Tulis Artikel Baru (admin/artikel/tambah.php)
 * Fungsi: Formulir untuk menulis dan menerbitkan artikel berita/opini baru (CREATE)
 */

include '../../config/database.php';

$page_title   = "Tulis Artikel Baru - Admin Aisyiyah Digital";
$page_heading = "Tulis Artikel Baru";
$active_menu  = "artikel";
$root_path    = "../../";

$pesan_error = "";

// Mengambil daftar kategori bertipe artikel
$query_kategori = mysqli_query($conn, "SELECT id, nama_kategori FROM kategori WHERE tipe = 'artikel' OR tipe IS NULL ORDER BY nama_kategori ASC");

// Fungsi pembantu membuat slug URL yang ramah SEO dan unik
function generate_slug($teks) {
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $teks)));
    return $slug . '-' . time();
}

// 1. Memproses Form Saat Tombol Submit Ditekan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul       = trim($_POST['judul'] ?? '');
    $kategori_id = !empty($_POST['kategori_id']) ? intval($_POST['kategori_id']) : null;
    $status      = in_array($_POST['status'] ?? '', ['published', 'draft']) ? $_POST['status'] : 'published';
    $isi         = trim($_POST['isi'] ?? '');
    $admin_id    = $_SESSION['admin_id'] ?? 1;

    if (empty($judul) || empty($isi)) {
        $pesan_error = "Judul tulisan dan isi artikel wajib diisi!";
    } else {
        $nama_file_gambar = null;
        $slug = generate_slug($judul);

        // 2. Memproses Unggahan Gambar Sampul jika ada
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            $img_tmp  = $_FILES['gambar']['tmp_name'];
            $img_name = $_FILES['gambar']['name'];
            $img_size = $_FILES['gambar']['size'];
            $img_ext  = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));

            $ekstensi_valid = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($img_ext, $ekstensi_valid)) {
                $pesan_error = "Format berkas gambar tidak valid! Gunakan JPG, JPEG, PNG, atau WEBP.";
            } elseif ($img_size > 5 * 1024 * 1024) {
                $pesan_error = "Ukuran gambar terlalu besar! Maksimal 5 MB.";
            } else {
                $nama_file_gambar = 'artikel_' . time() . '_' . uniqid() . '.' . $img_ext;
                $tujuan_upload    = '../../assets/uploads/artikel/' . $nama_file_gambar;
                move_uploaded_file($img_tmp, $tujuan_upload);
            }
        }

        // 3. Menyimpan ke Database Menggunakan Prepared Statement
        if (empty($pesan_error)) {
            $sql = "INSERT INTO artikel (kategori_id, admin_id, judul, slug, isi, gambar, status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "iisssss", $kategori_id, $admin_id, $judul, $slug, $isi, $nama_file_gambar, $status);

            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['sukses'] = "Artikel \"$judul\" berhasil disimpan!";
                header("Location: index.php");
                exit;
            } else {
                $pesan_error = "Gagal menyimpan artikel ke database: " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
        }
    }
}

include '../includes/header.php';
?>

<!-- Kartu Formulir Tulis Artikel -->
<div class="card card-admin p-4">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h5 class="fw-bold mb-0 text-success">
            <i class="bi bi-pencil-square me-2"></i>Formulir Artikel & Berita Baru
        </h5>
        <a href="index.php" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i> Kembali ke Artikel
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

    <!-- Form Input Artikel -->
    <form action="tambah.php" method="POST" enctype="multipart/form-data">
        <div class="row g-3">
            <!-- Judul Artikel -->
            <div class="col-md-8">
                <label for="judul" class="form-label fw-bold">Judul Artikel / Berita <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="judul" name="judul" 
                       placeholder="Contoh: Musyawarah PWA Jateng Teguhkan Dakwah Berkemajuan" 
                       value="<?= htmlspecialchars($_POST['judul'] ?? '') ?>" required autofocus>
            </div>

            <!-- Kategori Artikel -->
            <div class="col-md-4">
                <label for="kategori_id" class="form-label fw-bold">Kategori Tulisan</label>
                <select class="form-select" id="kategori_id" name="kategori_id">
                    <option value="">-- Pilih Kategori --</option>
                    <?php while ($kat = mysqli_fetch_assoc($query_kategori)) : ?>
                        <option value="<?= $kat['id'] ?>" <?= (isset($_POST['kategori_id']) && $_POST['kategori_id'] == $kat['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($kat['nama_kategori']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Upload Gambar Sampul Berita -->
            <div class="col-md-6">
                <label for="gambar" class="form-label fw-bold">Gambar Utama / Sampul Berita</label>
                <input class="form-control" type="file" id="gambar" name="gambar" accept="image/*" onchange="previewImageArtikel(event)">
                <small class="text-muted d-block">Format: JPG, JPEG, PNG, WEBP. Maksimal 5 MB.</small>
            </div>

            <!-- Status Publikasi -->
            <div class="col-md-6">
                <label for="status" class="form-label fw-bold">Status Publikasi</label>
                <select class="form-select" id="status" name="status">
                    <option value="published" <?= (isset($_POST['status']) && $_POST['status'] === 'published') ? 'selected' : '' ?>>
                        Langsung Tayangkan (Published)
                    </option>
                    <option value="draft" <?= (isset($_POST['status']) && $_POST['status'] === 'draft') ? 'selected' : '' ?>>
                        Simpan sebagai Draf (Draft)
                    </option>
                </select>
            </div>

            <!-- Pratinjau Gambar yang Dipilih -->
            <div class="col-12" id="previewContainerArtikel" style="display: none;">
                <label class="form-label fw-bold text-muted small">Pratinjau Sampul Berita:</label>
                <div class="p-2 border rounded-3 bg-light d-inline-block">
                    <img id="imagePreviewArtikel" src="#" alt="Pratinjau Sampul" style="max-height: 180px; max-width: 100%; object-fit: contain; border-radius: 8px;">
                </div>
            </div>

            <!-- Isi Lengkap Artikel -->
            <div class="col-12">
                <label for="isi" class="form-label fw-bold">Isi Lengkap Artikel <span class="text-danger">*</span></label>
                <textarea class="form-control" id="isi" name="isi" rows="12" 
                          placeholder="Tuliskan isi berita, kabar dakwah, opini, atau liputan kegiatan selengkapnya di sini..." required><?= htmlspecialchars($_POST['isi'] ?? '') ?></textarea>
                <small class="text-muted">Mendukung format paragraf biasa. Pisahkan antar paragraf dengan tombol enter.</small>
            </div>

            <!-- Tombol Simpan -->
            <div class="col-12 pt-3 border-top d-flex gap-2">
                <button type="submit" class="btn btn-admin-green px-4 py-2 d-flex align-items-center gap-2">
                    <i class="bi bi-send-fill"></i>
                    <span>Simpan & Terbitkan</span>
                </button>
                <a href="index.php" class="btn btn-outline-secondary px-3 py-2">Batal</a>
            </div>
        </div>
    </form>
</div>

<!-- Skrip Pratinjau Gambar -->
<script>
function previewImageArtikel(event) {
    const reader = new FileReader();
    reader.onload = function() {
        const preview = document.getElementById('imagePreviewArtikel');
        const container = document.getElementById('previewContainerArtikel');
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
