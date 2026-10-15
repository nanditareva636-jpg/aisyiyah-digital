<?php
// Deteksi nama file saat ini untuk menentukan menu yang aktif
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!-- Navbar Navigasi Utama -->
<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container">
        <!-- Logo & Nama Website -->
        <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
            <div class="navbar-brand-logo bg-white p-1">
                <img src="assets/img/logo-aisyiyah.svg" alt="Logo Aisyiyah" style="width: 100%; height: 100%; object-fit: contain;">
            </div>
            <div>
                <span class="d-block fw-bold text-dark lh-1" style="font-size: 1.25rem; letter-spacing: -0.02em;">Aisyiyah</span>
                <small class="text-muted fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.05em; color: var(--gold) !important;">JAWA TENGAH</small>
            </div>
        </a>

        <!-- Tombol Tampilan Mobile (Hamburger) -->
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu Navigasi -->
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-1">
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'index.php') ? 'active' : '' ?>" href="index.php">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'tentang.php') ? 'active' : '' ?>" href="tentang.php">Tentang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'sejarah.php' || $current_page == 'timeline.php') ? 'active' : '' ?>" href="sejarah.php">Sejarah</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'tokoh.php' || $current_page == 'detail-tokoh.php') ? 'active' : '' ?>" href="tokoh.php">Tokoh</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'pustaka.php') ? 'active' : '' ?>" href="pustaka.php">Pustaka</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'kegiatan.php') ? 'active' : '' ?>" href="kegiatan.php">Kegiatan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'galeri.php') ? 'active' : '' ?>" href="galeri.php">Galeri</a>
                </li>
            </ul>

            <!-- Tombol Masuk Admin -->
            <div class="d-flex align-items-center mt-3 mt-lg-0">
                <a href="admin/login.php" class="btn btn-outline-gold btn-sm d-flex align-items-center gap-2 px-3 py-2">
                    <i class="bi bi-shield-lock-fill"></i>
                    <span>Admin</span>
                </a>
            </div>
        </div>
    </div>
</nav>
