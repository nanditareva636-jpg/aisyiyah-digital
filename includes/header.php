<?php
// Inisialisasi judul halaman jika belum didefinisikan
if (!isset($page_title)) {
    $page_title = "Aisyiyah Digital Jawa Tengah - Menjaga Sejarah, Mencerahkan Bangsa";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>
    
    <!-- Meta Tag SEO & Deskripsi Singkat -->
    <meta name="description" content="Portal informasi, sejarah, pustaka digital, dan dokumentasi gerakan Aisyiyah dan Muhammadiyah Jawa Tengah.">
    <meta name="keywords" content="Aisyiyah, Jawa Tengah, Muhammadiyah, Pustaka Digital, Sejarah Aisyiyah, Perempuan Berkemajuan">
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
