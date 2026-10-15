<?php
/**
 * Website: Aisyiyah Digital Jawa Tengah
 * Panel Admin: Hapus Artikel (admin/artikel/hapus.php)
 * Fungsi: Menghapus data artikel dari database dan membersihkan berkas gambar sampul dari server (DELETE)
 */

session_start();
include '../../config/database.php';

// 1. Mengambil ID Artikel dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // 2. Mengambil data untuk mengetahui nama file gambar
    $stmt_select = mysqli_prepare($conn, "SELECT judul, gambar FROM artikel WHERE id = ?");
    mysqli_stmt_bind_param($stmt_select, "i", $id);
    mysqli_stmt_execute($stmt_select);
    $result  = mysqli_stmt_get_result($stmt_select);
    $artikel = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt_select);

    if ($artikel) {
        // 3. Menghapus berkas gambar sampul dari folder server jika ada
        $path_img = '../../assets/uploads/artikel/' . $artikel['gambar'];
        if (!empty($artikel['gambar']) && file_exists($path_img)) {
            unlink($path_img);
        }

        // 4. Menghapus rekaman baris dari database menggunakan Prepared Statement DELETE
        $stmt_delete = mysqli_prepare($conn, "DELETE FROM artikel WHERE id = ?");
        mysqli_stmt_bind_param($stmt_delete, "i", $id);

        if (mysqli_stmt_execute($stmt_delete)) {
            $_SESSION['sukses'] = "Artikel \"" . $artikel['judul'] . "\" berhasil dihapus.";
        } else {
            $_SESSION['sukses'] = "Gagal menghapus artikel dari database: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt_delete);
    }
}

// 5. Kembali ke Halaman Artikel Admin
header("Location: index.php");
exit;
