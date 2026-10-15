<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Menentukan root path agar pemanggilan link tidak salah
if (!isset($root_path)) {
    $root_path = "../../";
}

// Pemeriksaan Keamanan: Jika belum login, paksa kembali ke halaman login.php
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: " . $root_path . "admin/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title ?? 'Panel Admin - Aisyiyah Digital') ?></title>
    
    <!-- Google Font: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3 & Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --admin-green: #0d472a;       /* Hijau Tua Utama */
            --admin-green-dark: #082d1b;  /* Hijau Gelap */
            --admin-green-light: #166534; /* Hijau Segar */
            --admin-gold: #c5a059;        /* Gold Elegan */
            --admin-gold-light: #f7eed7;  /* Gold Halus */
            --admin-cream: #fbf9f4;       /* Cream Background Konsisten */
            --admin-border: #e9e4d6;      /* Garis Halus */
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background-color: var(--admin-cream);
            color: #232d27;
            overflow-x: hidden;
        }

        /* Sidebar Styling */
        .admin-sidebar {
            width: 270px;
            min-height: 100vh;
            background: linear-gradient(180deg, var(--admin-green-dark) 0%, var(--admin-green) 100%);
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            box-shadow: 4px 0 20px rgba(0,0,0,0.08);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .admin-sidebar .nav-link {
            color: #d1ded7;
            font-weight: 500;
            font-size: 0.93rem;
            padding: 0.72rem 1.1rem;
            border-radius: 10px;
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.85rem;
            transition: all 0.25s ease;
        }

        .admin-sidebar .nav-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.08);
            transform: translateX(3px);
        }

        .admin-sidebar .nav-link.active {
            color: #fff;
            background: rgba(197, 160, 89, 0.22);
            border-left: 4px solid var(--admin-gold);
            font-weight: 700;
        }

        .admin-sidebar .nav-link i {
            font-size: 1.15rem;
            color: var(--admin-gold);
        }

        .admin-sidebar .nav-link.active i {
            color: #fff;
        }

        /* Main Area */
        .admin-main {
            margin-left: 270px;
            padding: 2rem 2.5rem;
            min-height: 100vh;
            background-color: var(--admin-cream);
        }

        /* Topbar */
        .admin-topbar {
            background: #ffffff;
            border-radius: 16px;
            padding: 1rem 1.6rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            border: 1px solid var(--admin-border);
        }

        /* Cards */
        .card-admin {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid var(--admin-border);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.025);
            transition: all 0.3s ease;
        }

        .card-stat {
            background: #ffffff;
            border-radius: 16px;
            padding: 1.6rem 1.4rem;
            border: 1px solid var(--admin-border);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.025);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .card-stat:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 25px rgba(13, 71, 42, 0.08);
            border-color: var(--admin-gold);
        }

        .stat-icon-wrap {
            width: 54px;
            height: 54px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        /* Custom Button */
        .btn-admin-green {
            background-color: var(--admin-green);
            color: #fff;
            font-weight: 600;
            border-radius: 10px;
            border: none;
            padding: 0.6rem 1.2rem;
            transition: all 0.25s ease;
        }

        .btn-admin-green:hover {
            background-color: var(--admin-green-light);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(13, 71, 42, 0.25);
        }

        .btn-admin-gold {
            background-color: var(--admin-gold);
            color: #fff;
            font-weight: 600;
            border-radius: 10px;
            border: none;
            padding: 0.6rem 1.2rem;
            transition: all 0.25s ease;
        }

        .btn-admin-gold:hover {
            background-color: #b38b42;
            color: #fff;
            transform: translateY(-2px);
        }

        /* Responsive HP */
        @media (max-width: 991px) {
            .admin-sidebar {
                position: static;
                width: 100%;
                min-height: auto;
            }
            .admin-main {
                margin-left: 0;
                padding: 1.2rem;
            }
        }
    </style>
</head>
<body>

<div class="d-flex flex-column flex-lg-row">
    <!-- ================= SIDEBAR ADMIN ================= -->
    <div class="admin-sidebar p-3">
        <div>
            <!-- Logo & Brand Header Sidebar -->
            <div class="d-flex align-items-center gap-2 pb-3 mb-3 border-bottom border-white border-opacity-10 px-2">
                <div class="rounded-circle d-flex align-items-center justify-content-center text-white" 
                     style="width: 44px; height: 44px; background: linear-gradient(135deg, #166534, #0d472a); border: 2px solid var(--admin-gold); font-size: 1.3rem;">
                    <i class="bi bi-moon-stars-fill"></i>
                </div>
                <div>
                    <h6 class="mb-0 text-white fw-bold lh-1" style="font-size: 1.05rem;">Aisyiyah Digital</h6>
                    <small class="text-warning fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.05em;">ADMIN JAWA TENGAH</small>
                </div>
            </div>

            <!-- Menu Navigasi Sesuai Permintaan -->
            <ul class="nav flex-column mb-auto">
                <!-- 1. Dashboard -->
                <li class="nav-item">
                    <a href="<?= $root_path ?>admin/dashboard.php" class="nav-link <?= ($active_menu ?? '') === 'dashboard' ? 'active' : '' ?>">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- 2. Tokoh -->
                <li class="nav-item">
                    <a href="<?= $root_path ?>admin/tokoh/index.php" class="nav-link <?= ($active_menu ?? '') === 'tokoh' ? 'active' : '' ?>">
                        <i class="bi bi-people-fill"></i>
                        <span>Tokoh</span>
                    </a>
                </li>

                <!-- 3. Sejarah -->
                <li class="nav-item">
                    <a href="<?= $root_path ?>admin/sejarah/index.php" class="nav-link <?= ($active_menu ?? '') === 'sejarah' ? 'active' : '' ?>">
                        <i class="bi bi-hourglass-split"></i>
                        <span>Sejarah</span>
                    </a>
                </li>

                <!-- 4. Artikel -->
                <li class="nav-item">
                    <a href="<?= $root_path ?>admin/artikel/index.php" class="nav-link <?= ($active_menu ?? '') === 'artikel' ? 'active' : '' ?>">
                        <i class="bi bi-journal-richtext"></i>
                        <span>Artikel</span>
                    </a>
                </li>

                <!-- 5. Pustaka -->
                <li class="nav-item">
                    <a href="<?= $root_path ?>admin/pustaka/index.php" class="nav-link <?= ($active_menu ?? '') === 'pustaka' ? 'active' : '' ?>">
                        <i class="bi bi-book-half"></i>
                        <span>Pustaka</span>
                    </a>
                </li>

                <!-- 6. Kegiatan -->
                <li class="nav-item">
                    <a href="<?= $root_path ?>admin/kegiatan/index.php" class="nav-link <?= ($active_menu ?? '') === 'kegiatan' ? 'active' : '' ?>">
                        <i class="bi bi-calendar-check-fill"></i>
                        <span>Kegiatan</span>
                    </a>
                </li>

                <!-- 7. Galeri -->
                <li class="nav-item">
                    <a href="<?= $root_path ?>admin/galeri/index.php" class="nav-link <?= ($active_menu ?? '') === 'galeri' ? 'active' : '' ?>">
                        <i class="bi bi-images"></i>
                        <span>Galeri</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Bagian Bawah: Lihat Web & Logout -->
        <div class="pt-3 border-top border-white border-opacity-10 mt-3">
            <a href="<?= $root_path ?>index.php" target="_blank" class="nav-link text-white-50">
                <i class="bi bi-box-arrow-up-right text-warning"></i>
                <span>Lihat Website</span>
            </a>
            <!-- 8. Logout -->
            <a href="<?= $root_path ?>admin/logout.php" class="nav-link text-danger mt-1">
                <i class="bi bi-box-arrow-right text-danger"></i>
                <span class="fw-bold">Logout</span>
            </a>
        </div>
    </div>

    <!-- ================= AREA KONTEN UTAMA ================= -->
    <div class="admin-main flex-grow-1">
        <!-- Topbar Admin -->
        <div class="admin-topbar d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
            <div>
                <span class="text-muted small">Panel Kendali Pengelola</span>
                <h5 class="mb-0 fw-bold" style="color: var(--admin-green);"><?= htmlspecialchars($page_heading ?? 'Dashboard') ?></h5>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="text-end d-none d-sm-block">
                    <span class="d-block fw-bold small text-dark"><?= htmlspecialchars($_SESSION['admin_nama'] ?? 'Administrator') ?></span>
                    <small class="text-success fw-semibold"><i class="bi bi-circle-fill" style="font-size: 0.55rem;"></i> Online</small>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center text-white" 
                     style="width: 42px; height: 42px; background: var(--admin-green); border: 2px solid var(--admin-gold);">
                    <i class="bi bi-person-fill fs-5"></i>
                </div>
            </div>
        </div>
