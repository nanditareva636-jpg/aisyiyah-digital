<?php
/**
 * Website: Aisyiyah Digital Jawa Tengah
 * Panel Admin: Hapus Tokoh (admin/tokoh/hapus.php)
 * Fungsi: Menghapus data tokoh dari database dan menghapus file foto fisiknya (DELETE)
 */

session_start();
include '../../config/database.php';

// 1. Mengambil ID Tokoh dari URL (misal: hapus.php?id=3)
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // 2. Mengambil data tokoh terlebih dahulu untuk mengetahui nama file foto
    $stmt_select = mysqli_prepare($conn, "SELECT nama, foto FROM tokoh WHERE id = ?");
    mysqli_stmt_bind_param($stmt_select, "i", $id);
    mysqli_stmt_execute($stmt_select);
    $result = mysqli_stmt_get_result($stmt_select);
    $tokoh  = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt_select);

    if ($tokoh) {
        // 3. Menghapus file foto dari folder server jika ada
        if (!empty($tokoh['foto']) && file_exists('../../assets/uploads/tokoh/' . $tokoh['foto'])) {
            unlink('../../assets/uploads/tokoh/' . $tokoh['foto']);
        }

        // 4. Menghapus rekaman baris dari database menggunakan Prepared Statement DELETE
        $stmt_delete = mysqli_prepare($conn, "DELETE FROM tokoh WHERE id = ?");
        mysqli_stmt_bind_param($stmt_delete, "i", $id);

        if (mysqli_stmt_execute($stmt_delete)) {
            $_SESSION['sukses'] = "Data tokoh \"" . $tokoh['nama'] . "\" berhasil dihapus secara permanen.";
        } else {
            $_SESSION['sukses'] = "Gagal menghapus data dari database: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt_delete);
    }
}

// 5. Mengarahkan kembali ke halaman daftar tokoh
header("Location: index.php");
exit;
