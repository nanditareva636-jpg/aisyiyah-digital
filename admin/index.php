<?php
/**
 * Website: Aisyiyah Digital Jawa Tengah
 * Panel Admin: index.php
 * Fungsi: Mengarahkan (redirect) ke dashboard.php jika sudah login, atau ke login.php jika belum
 */

session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

header("Location: dashboard.php");
exit;
