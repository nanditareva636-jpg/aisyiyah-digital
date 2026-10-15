<?php
/**
 * Website: Aisyiyah Digital Jawa Tengah
 * Halaman: Login Admin (admin/login.php)
 * Fungsi: Autentikasi pengelola menggunakan PHP Session & password_verify()
 */

session_start();

// 1. Jika sudah dalam kondisi login, langsung alihkan ke dashboard.php
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
    exit;
}

include '../config/database.php';

$pesan_error = "";

// 2. Memproses form saat tombol "Masuk" ditekan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validasi input tidak boleh kosong
    if (empty($username) || empty($password)) {
        $pesan_error = "Silakan masukkan username dan password!";
    } else {
        // 3. Mengambil data admin dari database menggunakan Prepared Statement
        $sql  = "SELECT id, username, password, nama_lengkap FROM admins WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $admin  = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        // 4. Memeriksa keberadaan admin dan memverifikasi kecocokan password dengan password_verify()
        if ($admin && password_verify($password, $admin['password'])) {
            // Login Berhasil: Simpan data admin ke dalam PHP Session
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id']        = $admin['id'];
            $_SESSION['admin_username']  = $admin['username'];
            $_SESSION['admin_nama']      = $admin['nama_lengkap'];

            // Alihkan (redirect) ke dashboard.php
            header("Location: dashboard.php");
            exit;
        } else {
            // Login Gagal: Password tidak cocok atau username tidak terdaftar
            $pesan_error = "Username atau password yang Anda masukkan salah!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Aisyiyah Jawa Tengah</title>

    <!-- Google Font & Bootstrap 5 CDN -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #082d1b 0%, #0d472a 60%, #165b37 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .card-login {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.25);
            width: 100%;
            max-width: 440px;
            overflow: hidden;
            border: 1px solid rgba(197, 160, 89, 0.3);
        }
        .login-header {
            background-color: #fcfbf7;
            padding: 2.2rem 2rem 1.5rem;
            text-align: center;
            border-bottom: 2px solid #e9e6dd;
        }
        .login-logo {
            width: 65px;
            height: 65px;
            margin: 0 auto 1rem;
            border-radius: 50%;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #c5a059;
            box-shadow: 0 4px 15px rgba(13, 71, 42, 0.2);
            padding: 5px;
        }
        .login-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .btn-login {
            background: linear-gradient(135deg, #0d472a, #166534);
            color: #fff;
            font-weight: 600;
            padding: 0.8rem;
            border-radius: 10px;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #082d1b, #0d472a);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(13, 71, 42, 0.35);
        }
    </style>
</head>
<body>

<div class="card card-login">
    <!-- Header Kartu Login -->
    <div class="login-header">
        <div class="login-logo">
            <img src="../assets/img/logo-aisyiyah.svg" alt="Logo Aisyiyah">
        </div>
        <h4 class="fw-bold mb-1" style="color: #0d472a;">Panel Admin</h4>
        <small class="text-muted fw-semibold">Aisyiyah Jawa Tengah</small>
    </div>

    <!-- Isi Formulir Login -->
    <div class="p-4 p-md-5">
        <!-- Notifikasi Pesan Error jika login gagal -->
        <?php if (!empty($pesan_error)) : ?>
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 small py-2 mb-4" role="alert">
                <i class="bi bi-exclamation-circle-fill fs-5"></i>
                <div><?= htmlspecialchars($pesan_error) ?></div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <!-- Input Username -->
            <div class="mb-3">
                <label for="username" class="form-label fw-bold text-secondary small">Username</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted border-end-0">
                        <i class="bi bi-person-fill"></i>
                    </span>
                    <input type="text" class="form-control border-start-0 ps-0" id="username" name="username" 
                           placeholder="Masukkan username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required autofocus>
                </div>
            </div>

            <!-- Input Password -->
            <div class="mb-4">
                <label for="password" class="form-label fw-bold text-secondary small">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted border-end-0">
                        <i class="bi bi-key-fill"></i>
                    </span>
                    <input type="password" class="form-control border-start-0 ps-0" id="password" name="password" 
                           placeholder="Masukkan password" required>
                </div>
            </div>

            <!-- Tombol Masuk -->
            <button type="submit" class="btn btn-login w-100 mb-3 d-flex align-items-center justify-content-center gap-2">
                <span>Masuk ke Dashboard</span>
                <i class="bi bi-arrow-right-short fs-4"></i>
            </button>
        </form>

        <!-- Tautan Kembali ke Website Publik -->
        <div class="text-center mt-4 pt-3 border-top">
            <a href="../index.php" class="text-muted small text-decoration-none d-inline-flex align-items-center gap-1">
                <i class="bi bi-arrow-left"></i> Kembali ke Beranda Website
            </a>
        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
