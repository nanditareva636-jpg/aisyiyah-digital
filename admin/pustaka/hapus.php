<?php
/**
 * Website: Aisyiyah Digital Jawa Tengah
 * Panel Admin: Hapus Dokumen Pustaka (admin/pustaka/hapus.php)
 * Fungsi: Menghapus data dokumen dari database dan membersihkan berkas cover & PDF dari server (DELETE)
 */

session_start();
include '../../config/database.php';

// 1. Mengambil ID Dokumen dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // 2. Mengambil data untuk mengetahui nama file cover dan dokumen fisik
    $stmt_select = mysqli_prepare($conn, "SELECT judul, cover, file_dokumen FROM pustaka WHERE id = ?");
    mysqli_stmt_bind_param($stmt_select, "i", $id);
    mysqli_stmt_execute($stmt_select);
    $result = mysqli_stmt_get_result($stmt_select);
    $doc    = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt_select);

    if ($doc) {
        // 3. Menghapus berkas foto sampul dari server jika ada
        $path_cover = '../../assets/uploads/pustaka/' . $doc['cover'];
        if (!empty($doc['cover']) && file_exists($path_cover)) {
            unlink($path_cover);
        }

        // 4. Menghapus berkas dokumen PDF dari server jika ada
        $path_doc = '../../assets/uploads/pustaka/' . $doc['file_dokumen'];
        if (!empty($doc['file_dokumen']) && file_exists($path_doc)) {
            unlink($path_doc);
        }

        // 5. Menghapus rekaman baris dari database menggunakan Prepared Statement DELETE
        $stmt_delete = mysqli_prepare($conn, "DELETE FROM pustaka WHERE id = ?");
        mysqli_stmt_bind_param($stmt_delete, "i", $id);

        if (mysqli_stmt_execute($stmt_delete)) {
            $_SESSION['sukses'] = "Dokumen pustaka \"" . $doc['judul'] . "\" berhasil dihapus.";
        } else {
            $_SESSION['sukses'] = "Gagal menghapus data dari database: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt_delete);
    }
}

// 6. Kembali ke Halaman Pustaka Admin
header("Location: index.php");
exit;
