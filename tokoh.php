<?php
/**
 * Website: Aisyiyah Digital Jawa Tengah
 * Halaman: Direktori Tokoh (tokoh.php)
 */

// 1. Menghubungkan ke Database
include 'config/database.php';

// Menyiapkan Judul Halaman dan Komponen Header & Navbar
$page_title = "Direktori Tokoh Pejuang - Aisyiyah Digital Jawa Tengah";
include 'includes/header.php';
include 'includes/navbar.php';

// 2. Mengambil Seluruh Data Tokoh dari Tabel 'tokoh'
$query = mysqli_query($conn, "SELECT * FROM tokoh ORDER BY id ASC");
?>

<!-- Banner Header Halaman -->
<div class="py-5" style="background: linear-gradient(135deg, var(--primary-green-dark) 0%, var(--primary-green) 100%); color: #fff;">
    <div class="container text-center">
        <span class="badge bg-warning text-dark px-3 py-2 fw-bold mb-2">Khazanah Teladan</span>
        <h1 class="text-white fw-bold mb-2">Tokoh & Figur Pejuang Aisyiyah</h1>
        <p class="text-white-50 mx-auto mb-0" style="max-width: 650px;">
            Mengenal kiprah, keteladanan, dan jejak perjuangan para perintis dakwah perempuan berkemajuan di Indonesia dan Jawa Tengah.
        </p>
    </div>
</div>

<!-- Daftar Card Tokoh dari Database -->
<div class="py-5">
    <div class="container">
        <div class="row g-4 justify-content-center">
            <?php
            // 3. Memeriksa Apakah Ada Data Tokoh
            if (mysqli_num_rows($query) > 0) {
                // 4. Melakukan Perulangan untuk Setiap Baris Data Tokoh
                while ($tokoh = mysqli_fetch_assoc($query)) {
                    
                    // Menyiapkan format tahun (Tahun Lahir - Wafat)
                    $tahun_lahir = !empty($tokoh['tanggal_lahir']) ? date('Y', strtotime($tokoh['tanggal_lahir'])) : '';
                    $wafat = !empty($tokoh['wafat']) ? $tokoh['wafat'] : '-';
                    $tahun_hidup = $tahun_lahir ? "$tahun_lahir — $wafat" : $wafat;
            ?>
                    <!-- Kolom Kartu Tokoh -->
                    <div class="col-lg-4 col-md-6">
                        <div class="card-tokoh">
                            <!-- Bagian Foto Tokoh -->
                            <div class="tokoh-img-wrap">
                                <?php if (!empty($tokoh['foto']) && file_exists('assets/uploads/tokoh/' . $tokoh['foto'])) : ?>
                                    <img src="assets/uploads/tokoh/<?= htmlspecialchars($tokoh['foto']) ?>" alt="<?= htmlspecialchars($tokoh['nama']) ?>">
                                <?php else : ?>
                                    <!-- Placeholder jika foto belum diunggah -->
                                    <div class="text-center text-success">
                                        <i class="bi bi-person-bounding-box" style="font-size: 5rem; color: var(--primary-green-light); opacity: 0.85;"></i>
                                    </div>
                                <?php endif; ?>

                                <!-- Badge Peran Singkat di Foto -->
                                <?php if (!empty($tokoh['peran_jabatan'])) : ?>
                                    <span class="tokoh-badge">
                                        <i class="bi bi-award-fill me-1 text-warning"></i>
                                        <?= htmlspecialchars(mb_strimwidth($tokoh['peran_jabatan'], 0, 32, "...")) ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <!-- Bagian Teks Card -->
                            <div class="p-4 d-flex flex-column flex-grow-1">
                                <!-- Nama Tokoh -->
                                <h5 class="fw-bold mb-1 text-success">
                                    <?= htmlspecialchars($tokoh['nama']) ?>
                                </h5>

                                <!-- Tahun Hidup / Periode -->
                                <p class="text-muted small mb-2">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    <?= htmlspecialchars($tahun_hidup) ?>
                                </p>

                                <!-- Peran / Jabatan Lengkap -->
                                <p class="fw-semibold text-dark small mb-3">
                                    <?= htmlspecialchars($tokoh['peran_jabatan'] ?? 'Tokoh Aisyiyah') ?>
                                </p>

                                <!-- Cuplikan Biografi Singkat -->
                                <p class="small text-secondary mb-4 flex-grow-1" style="line-height: 1.6;">
                                    <?= htmlspecialchars(mb_strimwidth(strip_tags($tokoh['biografi']), 0, 120, '...')) ?>
                                </p>

                                <!-- Tombol Lihat Profil -->
                                <a href="detail-tokoh.php?id=<?= $tokoh['id'] ?>" class="btn btn-outline-gold btn-sm w-100 py-2 d-flex align-items-center justify-content-center gap-2">
                                    <span>Lihat Profil</span>
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
            <?php
                } // Akhir perulangan while
            } else {
                // Tampilan jika tabel tokoh masih kosong
            ?>
                <div class="col-12 text-center py-5">
                    <div class="p-5 bg-white rounded-4 border">
                        <i class="bi bi-people text-muted" style="font-size: 3.5rem;"></i>
                        <h4 class="mt-3 text-secondary">Belum Ada Data Tokoh</h4>
                        <p class="text-muted small">Data tokoh akan tampil di sini setelah ditambahkan melalui panel admin.</p>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>

<?php
// Memanggil Footer
include 'includes/footer.php';
?>
