<?php
/**
 * Website: Aisyiyah Digital Jawa Tengah
 * Panel Admin: Edit Dokumen Pustaka (admin/pustaka/edit.php)
 * Fungsi: Mengubah data dokumen, mengganti foto sampul (cover), atau mengganti berkas PDF (UPDATE)
 */

include '../../config/database.php';

$page_title   = "Edit Dokumen Pustaka - Admin Aisyiyah Digital";
$page_heading = "Edit Dokumen Pustaka";
$active_menu  = "pustaka";
$root_path    = "../../";

$pesan_error = "";

// 1. Mengambil ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 2. Mengambil Data Dokumen dari Database dengan Prepared Statement
$stmt_get = mysqli_prepare($conn, "SELECT * FROM pustaka WHERE id = ?");
mysqli_stmt_bind_param($stmt_get, "i", $id);
mysqli_stmt_execute($stmt_get);
$result = mysqli_stmt_get_result($stmt_get);
$doc    = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt_get);

if (!$doc) {
    header("Location: index.php");
    exit;
}

// Mengambil daftar kategori pustaka
$query_kategori = mysqli_query($conn, "SELECT id, nama_kategori FROM kategori WHERE tipe = 'pustaka' OR tipe IS NULL ORDER BY nama_kategori ASC");

// 3. Memproses Form Saat Tombol Submit Ditekan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul        = trim($_POST['judul'] ?? '');
    $kategori_id  = !empty($_POST['kategori_id']) ? intval($_POST['kategori_id']) : null;
    $penulis      = trim($_POST['penulis'] ?? '');
    $tahun_terbit = trim($_POST['tahun_terbit'] ?? '');
    $deskripsi    = trim($_POST['deskripsi'] ?? '');

    $nama_file_cover   = $doc['cover'];
    $nama_file_dokumen = $doc['file_dokumen'];

    if (empty($judul)) {
        $pesan_error = "Judul buku / dokumen tidak boleh dikosongkan!";
    } else {
        // 4. Memeriksa Apakah Pengguna Mengunggah Foto Sampul Baru
        if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
            $cover_tmp  = $_FILES['cover']['tmp_name'];
            $cover_name = $_FILES['cover']['name'];
            $cover_ext  = strtolower(pathinfo($cover_name, PATHINFO_EXTENSION));

            $ekstensi_gambar = ['jpg', 'jpeg', 'png', 'webp'];

            if (in_array($cover_ext, $ekstensi_gambar)) {
                $nama_file_cover_baru = 'cover_' . time() . '_' . uniqid() . '.' . $cover_ext;
                $tujuan_cover         = '../../assets/uploads/pustaka/' . $nama_file_cover_baru;

                if (move_uploaded_file($cover_tmp, $tujuan_cover)) {
                    // Hapus foto sampul lama jika ada fisiknya di folder
                    if (!empty($doc['cover']) && file_exists('../../assets/uploads/pustaka/' . $doc['cover'])) {
                        unlink('../../assets/uploads/pustaka/' . $doc['cover']);
                    }
                    $nama_file_cover = $nama_file_cover_baru;
                }
            } else {
                $pesan_error = "Format foto sampul baru tidak valid! Gunakan JPG, JPEG, PNG, atau WEBP.";
            }
        }

        // 5. Memeriksa Apakah Pengguna Mengunggah Berkas Dokumen PDF Baru
        if (empty($pesan_error) && isset($_FILES['file_dokumen']) && $_FILES['file_dokumen']['error'] === UPLOAD_ERR_OK) {
            $doc_tmp  = $_FILES['file_dokumen']['tmp_name'];
            $doc_name = $_FILES['file_dokumen']['name'];
            $doc_ext  = strtolower(pathinfo($doc_name, PATHINFO_EXTENSION));

            $ekstensi_doc = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'zip'];

            if (in_array($doc_ext, $ekstensi_doc)) {
                $nama_file_dokumen_baru = 'pustaka_' . time() . '_' . uniqid() . '.' . $doc_ext;
                $tujuan_doc             = '../../assets/uploads/pustaka/' . $nama_file_dokumen_baru;

                if (move_uploaded_file($doc_tmp, $tujuan_doc)) {
                    // Hapus berkas dokumen lama jika ada fisiknya di folder
                    if (!empty($doc['file_dokumen']) && file_exists('../../assets/uploads/pustaka/' . $doc['file_dokumen'])) {
                        unlink('../../assets/uploads/pustaka/' . $doc['file_dokumen']);
                    }
                    $nama_file_dokumen = $nama_file_dokumen_baru;
                }
            } else {
                $pesan_error = "Format berkas dokumen baru tidak didukung!";
            }
        }

        // 6. Simpan Perubahan ke Database
        if (empty($pesan_error)) {
            $sql = "UPDATE pustaka SET 
                        judul = ?, 
                        kategori_id = ?, 
                        penulis = ?, 
                        tahun_terbit = ?, 
                        deskripsi = ?, 
                        cover = ?, 
                        file_dokumen = ? 
                    WHERE id = ?";
            $stmt_update = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt_update, "sisssssi", $judul, $kategori_id, $penulis, $tahun_terbit, $deskripsi, $nama_file_cover, $nama_file_dokumen, $id);

            if (mysqli_stmt_execute($stmt_update)) {
                $_SESSION['sukses'] = "Data dokumen \"$judul\" berhasil diperbarui!";
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
$path_cover_lama = '../../assets/uploads/pustaka/' . $doc['cover'];
$ada_cover_lama  = !empty($doc['cover']) && file_exists($path_cover_lama);
?>

<!-- Kartu Formulir Edit Pustaka -->
<div class="card card-admin p-4">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h5 class="fw-bold mb-0 text-success">
            <i class="bi bi-pencil-square me-2"></i>Perbarui Dokumen: <?= htmlspecialchars($doc['judul']) ?>
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

    <form action="edit.php?id=<?= $id ?>" method="POST" enctype="multipart/form-data">
        <div class="row g-3">
            <!-- Judul Dokumen -->
            <div class="col-md-8">
                <label for="judul" class="form-label fw-bold">Judul Buku / Naskah Dokumen <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="judul" name="judul" 
                       value="<?= htmlspecialchars($_POST['judul'] ?? $doc['judul']) ?>" required>
            </div>

            <!-- Kategori -->
            <div class="col-md-4">
                <label for="kategori_id" class="form-label fw-bold">Kategori Dokumen</label>
                <select class="form-select" id="kategori_id" name="kategori_id">
                    <option value="">-- Pilih Kategori --</option>
                    <?php 
                    $selected_kat = $_POST['kategori_id'] ?? $doc['kategori_id'];
                    while ($kat = mysqli_fetch_assoc($query_kategori)) : 
                    ?>
                        <option value="<?= $kat['id'] ?>" <?= ($selected_kat == $kat['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($kat['nama_kategori']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Penulis -->
            <div class="col-md-6">
                <label for="penulis" class="form-label fw-bold">Penulis / Penyusun</label>
                <input type="text" class="form-control" id="penulis" name="penulis" 
                       value="<?= htmlspecialchars($_POST['penulis'] ?? $doc['penulis']) ?>">
            </div>

            <!-- Tahun Terbit -->
            <div class="col-md-6">
                <label for="tahun_terbit" class="form-label fw-bold">Tahun Terbit</label>
                <input type="text" class="form-control" id="tahun_terbit" name="tahun_terbit" 
                       value="<?= htmlspecialchars($_POST['tahun_terbit'] ?? $doc['tahun_terbit']) ?>">
            </div>

            <!-- Ganti Foto Sampul & Berkas Dokumen -->
            <div class="col-md-6">
                <label for="cover" class="form-label fw-bold">Ganti Foto Sampul Buku (Opsional)</label>
                <input class="form-control mb-2" type="file" id="cover" name="cover" accept="image/*">
                <small class="text-muted d-block">Biarkan kosong jika tetap menggunakan sampul lama.</small>

                <!-- Tampilan Sampul Lama -->
                <div class="p-2 border rounded-3 bg-light mt-2 d-inline-flex align-items-center gap-3">
                    <?php if ($ada_cover_lama) : ?>
                        <img src="<?= $path_cover_lama ?>" alt="Sampul Lama" style="height: 70px; border-radius: 4px;">
                        <small class="text-muted">Sampul aktif saat ini</small>
                    <?php else : ?>
                        <span class="badge bg-secondary">Belum ada foto sampul</span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Ganti Berkas Dokumen -->
            <div class="col-md-6">
                <label for="file_dokumen" class="form-label fw-bold">Ganti Berkas Dokumen (Opsional)</label>
                <input class="form-control mb-2" type="file" id="file_dokumen" name="file_dokumen" accept=".pdf,.doc,.docx,.zip">
                <small class="text-muted d-block">Biarkan kosong jika tetap menggunakan berkas lama.</small>

                <!-- Info Berkas Lama -->
                <div class="p-2 border rounded-3 bg-light mt-2">
                    <small class="text-muted d-block">Nama berkas saat ini:</small>
                    <span class="badge bg-danger"><i class="bi bi-file-earmark-pdf me-1"></i><?= htmlspecialchars($doc['file_dokumen']) ?></span>
                </div>
            </div>

            <!-- Deskripsi Singkat -->
            <div class="col-12">
                <label for="deskripsi" class="form-label fw-bold">Deskripsi / Ringkasan Isi Buku</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4"><?= htmlspecialchars($_POST['deskripsi'] ?? $doc['deskripsi']) ?></textarea>
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

<?php
include '../includes/footer.php';
?>
