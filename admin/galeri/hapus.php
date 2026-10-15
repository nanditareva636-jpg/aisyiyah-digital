<?php
/**
 * Website: Aisyiyah Digital Jawa Tengah
 * Panel Admin: Hapus Foto Galeri (admin/galeri/hapus.php)
 * Fungsi: Menghapus data foto dari database dan membersihkan berkas gambar dari server (DELETE)
 */

session_start();
include '../../config/database.php';

// 1. Mengambil ID Foto dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // 2. Mengambil data foto terlebih dahulu untuk mengetahui nama file
    $stmt_select = mysqli_prepare($conn, "SELECT judul, foto FROM galeri WHERE id = ?");
    mysqli_stmt_bind_param($stmt_select, "i", $id);
    mysqli_stmt_execute($stmt_select);
    $result = mysqli_stmt_get_result($stmt_select);
    $foto   = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt_select);

    if ($foto) {
        // 3. Menghapus berkas fisik foto dari folder server jika ada
        $path_file = '../../assets/uploads/galeri/' . $foto['foto'];
        if (!empty($foto['foto']) && file_exists($path_file)) {
            unlink($path_file);
        }

        // 4. Menghapus rekaman baris dari database menggunakan Prepared Statement DELETE
        $stmt_delete = mysqli_prepare($conn, "DELETE FROM galeri WHERE id = ?");
        mysqli_stmt_bind_param($stmt_delete, "i", $id);

        if (mysqli_stmt_execute($stmt_delete)) {
            $_SESSION['sukses'] = "Foto dokumentasi \"" . $foto['judul'] . "\" berhasil dihapus.";
        } else {
            $_SESSION['sukses'] = "Gagal menghapus data dari database: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt_delete);
    }
}

// 5. Kembali ke Halaman Galeri Admin
header("Location: index.php");
exit;
