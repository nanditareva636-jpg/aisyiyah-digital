<?php
/**
 * Website: Aisyiyah Digital Jawa Tengah
 * Halaman: Logout Admin (admin/logout.php)
 * Fungsi: Menghancurkan session admin dan mengarahkan kembali ke halaman login
 */

session_start();

// 1. Kosongkan semua data dalam session
$_SESSION = [];

// 2. Hapus cookie session jika ada
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. Hancurkan session secara keseluruhan
session_destroy();

// 4. Arahkan pengguna kembali ke halaman login
header("Location: login.php");
exit;
