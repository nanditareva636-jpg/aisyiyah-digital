<?php
/**
 * Website: Aisyiyah Digital Jawa Tengah
 * Panel Admin: Edit Artikel (admin/artikel/edit.php)
 * Fungsi: Mengubah isi tulisan artikel, kategori, status, dan mengganti gambar sampul (UPDATE)
 */

include '../../config/database.php';

$page_title   = "Edit Artikel - Admin Aisyiyah Digital";
$page_heading = "Edit Artikel Publikasi";
$active_menu  = "artikel";
$root_path    = "../../";

$pesan_error = "";

// 1. Mengambil ID Artikel dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 2. Mengambil Data Artikel dengan Prepared Statement
$stmt_get = mysqli_prepare($conn, "SELECT * FROM artikel WHERE id = ?");
mysqli_stmt_bind_param($stmt_get, "i", $id);
mysqli_stmt_execute($stmt_get);
$result  = mysqli_stmt_get_result($stmt_get);
$artikel = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt_get);

if (!$artikel) {
    header("Location: index.php");
    exit;
}

// Mengambil daftar kategori bertipe artikel
$query_kategori = mysqli_query($conn, "SELECT id, nama_kategori FROM kategori WHERE tipe = 'artikel' OR tipe IS NULL ORDER BY nama_kategori ASC");

// 3. Memproses Form Saat Tombol Submit Ditekan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul       = trim($_POST['judul'] ?? '');
    $kategori_id = !empty($_POST['kategori_id']) ? intval($_POST['kategori_id']) : null;
    $status      = in_array($_POST['status'] ?? '', ['published', 'draft']) ? $_POST['status'] : 'published';
    $isi         = trim($_POST['isi'] ?? '');

    $nama_file_gambar = $artikel['gambar'];

    if (empty($judul) || empty($isi)) {
        $pesan_error = "Judul tulisan dan isi artikel tidak boleh dikosongkan!";
    } else {
        // 4. Memeriksa Apakah Pengguna Mengunggah Gambar Baru
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
                $nama_file_gambar_baru = 'artikel_' . time() . '_' . uniqid() . '.' . $img_ext;
                $tujuan_upload         = '../../assets/uploads/artikel/' . $nama_file_gambar_baru;

                if (move_uploaded_file($img_tmp, $tujuan_upload)) {
                    // Hapus gambar lama dari server jika ada
                    if (!empty($artikel['gambar']) && file_exists('../../assets/uploads/artikel/' . $artikel['gambar'])) {
                        unlink('../../assets/uploads/artikel/' . $artikel['gambar']);
                    }
                    $nama_file_gambar = $nama_file_gambar_baru;
                }
            }
        }

        // 5. Simpan Perubahan ke Database
        if (empty($pesan_error)) {
            $sql = "UPDATE artikel SET 
                        judul = ?, 
                        kategori_id = ?, 
                        isi = ?, 
                        gambar = ?, 
                        status = ? 
                    WHERE id = ?";
            $stmt_update = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt_update, "sisssi", $judul, $kategori_id, $isi, $nama_file_gambar, $status, $id);

            if (mysqli_stmt_execute($stmt_update)) {
                $_SESSION['sukses'] = "Artikel \"$judul\" berhasil diperbarui!";
                header("Location: index.php");
                exit;
            } else {
                $pesan_error = "Gagal memperbarui data artikel: " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt_update);
        }
    }
}

include '../includes/header.php';
$path_img_lama = '../../assets/uploads/artikel/' . $artikel['gambar'];
$ada_img_lama  = !empty($artikel['gambar']) && file_exists($path_img_lama);
?>

<!-- Kartu Formulir Edit Artikel -->
<div class="card card-admin p-4">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h5 class="fw-bold mb-0 text-success">
            <i class="bi bi-pencil-square me-2"></i>Perbarui Artikel: <?= htmlspecialchars($artikel['judul']) ?>
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

    <form action="edit.php?id=<?= $id ?>" method="POST" enctype="multipart/form-data">
        <div class="row g-3">
            <!-- Judul Artikel -->
            <div class="col-md-8">
                <label for="judul" class="form-label fw-bold">Judul Artikel / Berita <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="judul" name="judul" 
                       value="<?= htmlspecialchars($_POST['judul'] ?? $artikel['judul']) ?>" required>
            </div>

            <!-- Kategori -->
            <div class="col-md-4">
                <label for="kategori_id" class="form-label fw-bold">Kategori Tulisan</label>
                <select class="form-select" id="kategori_id" name="kategori_id">
                    <option value="">-- Pilih Kategori --</option>
                    <?php 
                    $selected_kat = $_POST['kategori_id'] ?? $artikel['kategori_id'];
                    while ($kat = mysqli_fetch_assoc($query_kategori)) : 
                    ?>
                        <option value="<?= $kat['id'] ?>" <?= ($selected_kat == $kat['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($kat['nama_kategori']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Ganti Gambar Sampul -->
            <div class="col-md-6">
                <label for="gambar" class="form-label fw-bold">Ganti Gambar Sampul (Opsional)</label>
                <input class="form-control mb-2" type="file" id="gambar" name="gambar" accept="image/*" onchange="previewImageEditArtikel(event)">
                <small class="text-muted d-block">Biarkan kosong jika tidak ingin mengganti gambar.</small>

                <!-- Tampilan Gambar Lama -->
                <div class="p-2 border rounded-3 bg-light mt-2 d-inline-flex align-items-center gap-3">
                    <?php if ($ada_img_lama) : ?>
                        <img src="<?= $path_img_lama ?>" alt="Gambar Lama" style="height: 60px; border-radius: 4px; object-fit: cover;">
                        <small class="text-muted">Gambar aktif saat ini</small>
                    <?php else : ?>
                        <span class="badge bg-secondary">Belum ada gambar sampul</span>
                    <?php endif; ?>
                </div>

                <div id="previewEditContainerArtikel" style="display: none;" class="mt-2">
                    <small class="text-success d-block fw-bold mb-1">Gambar Pengganti yang Dipilih:</small>
                    <img id="imagePreviewEditArtikel" src="#" alt="Gambar Pengganti" style="height: 60px; border-radius: 4px; border: 2px solid var(--admin-gold);">
                </div>
            </div>

            <!-- Status Publikasi -->
            <div class="col-md-6">
                <label for="status" class="form-label fw-bold">Status Publikasi</label>
                <?php $current_status = $_POST['status'] ?? $artikel['status']; ?>
                <select class="form-select" id="status" name="status">
                    <option value="published" <?= ($current_status === 'published') ? 'selected' : '' ?>>
                        Langsung Tayangkan (Published)
                    </option>
                    <option value="draft" <?= ($current_status === 'draft') ? 'selected' : '' ?>>
                        Simpan sebagai Draf (Draft)
                    </option>
                </select>
            </div>

            <!-- Isi Artikel -->
            <div class="col-12">
                <label for="isi" class="form-label fw-bold">Isi Lengkap Artikel <span class="text-danger">*</span></label>
                <textarea class="form-control" id="isi" name="isi" rows="12" required><?= htmlspecialchars($_POST['isi'] ?? $artikel['isi']) ?></textarea>
            </div>

            <!-- Tombol Simpan -->
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

<!-- Skrip Pratinjau Gambar Edit -->
<script>
function previewImageEditArtikel(event) {
    const reader = new FileReader();
    reader.onload = function() {
        const preview = document.getElementById('imagePreviewEditArtikel');
        const container = document.getElementById('previewEditContainerArtikel');
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
