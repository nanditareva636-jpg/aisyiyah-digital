<?php
/**
 * Website: Aisyiyah Digital Jawa Tengah
 * Halaman: Sejarah Perjuangan (sejarah.php)
 * Fungsi: Menampilkan narasi sejarah, babak periode perjuangan, dan lini masa dinamis dari MySQL
 */

include 'config/database.php';

$page_title = "Sejarah Perjuangan - Aisyiyah Digital Jawa Tengah";
include 'includes/header.php';
include 'includes/navbar.php';

// Mengambil seluruh data lini masa sejarah dari database
$query_sejarah = mysqli_query($conn, "SELECT * FROM sejarah ORDER BY urutan ASC");
?>

<!-- ================= 1. BANNER HEADER ================= -->
<section class="py-5 position-relative overflow-hidden" 
         style="background: linear-gradient(135deg, var(--primary-green-dark) 0%, var(--primary-green) 100%); color: #fff;">
    <div class="container position-relative py-3 text-center">
        <span class="badge bg-warning text-dark px-3 py-2 fw-bold mb-2">Dokumentasi Sejarah</span>
        <h1 class="text-white fw-bold mb-3 display-5">Sejarah Gerakan Aisyiyah di Jawa Tengah</h1>
        <p class="text-white-50 mx-auto mb-0" style="max-width: 720px; font-size: 1.1rem; line-height: 1.8;">
            Menelusuri jejak langkah dakwah pencerahan, emansipasi perempuan muslimah, dan pengabdian sosial Aisyiyah & Muhammadiyah di tanah Jawa Tengah dari masa perintisan hingga era keemasan.
        </p>
    </div>
</section>

<!-- ================= 2. BREADCRUMB ================= -->
<div class="bg-white border-bottom py-3">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="index.php" class="text-success text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item active text-muted" aria-current="page">Sejarah</li>
            </ol>
        </nav>
    </div>
</div>

<!-- ================= 3. NARASI PENGANTAR SEJARAH ================= -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="section-subtitle text-start">Akar Perjuangan</span>
                <h2 class="mb-3" style="color: var(--primary-green); font-size: 2.2rem;">
                    Dari Kauman Menembus Pelosok Jawa Tengah
                </h2>
                <p class="text-secondary leading-relaxed mb-3">
                    Kelahiran Aisyiyah berakar dari perkumpulan pengajian gadis-gadis dan kaum ibu bernama <strong>Sapa Tresno</strong> pada tahun 1914 di Kauman Yogyakarta. Diprakarsai oleh Nyai Ahmad Dahlan bersama santriwati-santriwati awal, perkumpulan ini mendobrak tradisi feodal yang membatasi akses perempuan terhadap pendidikan dan ruang publik.
                </p>
                <p class="text-secondary leading-relaxed mb-4">
                    Setelah resmi dideklarasikan pada <strong>27 Rajab 1335 H (1917 M)</strong>, gelombang pemikiran perempuan berkemajuan merambah dengan cepat ke wilayah Jawa Tengah. Karesidenan Surakarta, Pekalongan, Kudus, Banyumas, hingga Semarang menjadi lumbung tumbuhnya cabang-cabang perintis yang mempelopori berdirinya sekolah putri, pengajian buruh batik, dan pertolongan kesehatan kaum dhuafa.
                </p>
                <div class="d-flex align-items-center gap-3 p-3 rounded-3 bg-white border border-success border-opacity-25 shadow-sm">
                    <div class="rounded-circle p-3 bg-success bg-opacity-10 text-success">
                        <i class="bi bi-clock-history fs-3"></i>
                    </div>
                    <div>
                        <strong class="d-block text-dark">Satu Abad Lebih Mengabdi</strong>
                        <small class="text-muted">Sejak 1923, Aisyiyah Jawa Tengah konsisten membina jutaan keluarga muslimah berkemajuan.</small>
                    </div>
                </div>
            </div>

            <!-- Kutipan Inspiratif & Poin Kunci -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5" style="background: linear-gradient(135deg, #fdfbf7, #f7f3e8); border: 1px solid var(--border-color) !important;">
                    <div class="mb-3 text-warning">
                        <i class="bi bi-quote fs-1"></i>
                    </div>
                    <blockquote class="blockquote mb-4">
                        <p class="fs-5 fw-semibold text-dark mb-3" style="line-height: 1.7;">
                            "Urusan dapur tidak boleh menjadi penghalang bagi perempuan untuk menuntut ilmu setinggi-tingginya dan beramal shalih bagi kemaslahatan umat."
                        </p>
                        <footer class="blockquote-footer text-success fw-bold">
                            Nyai Hj. Ahmad Dahlan (Siti Walidah) <cite title="Pendiri Aisyiyah">(1872 - 1946)</cite>
                        </footer>
                    </blockquote>

                    <div class="border-top pt-3 d-flex justify-content-between text-center">
                        <div>
                            <h4 class="fw-bold mb-0 text-success">1923</h4>
                            <small class="text-muted" style="font-size: 0.75rem;">Masuk ke Jateng</small>
                        </div>
                        <div class="border-start ps-3">
                            <h4 class="fw-bold mb-0 text-warning">1928</h4>
                            <small class="text-muted" style="font-size: 0.75rem;">Kongres Perempuan I</small>
                        </div>
                        <div class="border-start ps-3">
                            <h4 class="fw-bold mb-0 text-dark">1929</h4>
                            <small class="text-muted" style="font-size: 0.75rem;">Kongres Surakarta</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================= 4. TIGA BABAK PERIODE SEJARAH ================= -->
<section class="py-5" style="background-color: #f7f5ed;">
    <div class="container">
        <div class="section-title-wrap">
            <span class="section-subtitle">Periodisasi Gerakan</span>
            <h2 class="section-title">Tiga Babak Emas Perjuangan di Jawa Tengah</h2>
        </div>

        <div class="row g-4">
            <!-- Babak 1 -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 p-4 rounded-4 shadow-sm bg-white position-relative overflow-hidden">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-3 p-3 mb-3 text-white" 
                         style="background-color: var(--primary-green); width: 50px; height: 50px;">
                        <span class="fw-bold fs-5">I</span>
                    </div>
                    <span class="badge bg-warning text-dark align-self-start mb-2 fw-bold">Tahun 1914 — 1928</span>
                    <h5 class="fw-bold text-dark mb-2">Era Perintisan & Kesadaran Perempuan</h5>
                    <p class="small text-secondary mb-0 leading-relaxed">
                        Fokus pada pemberantasan buta aksara huruf latin dan Al-Qur'an, pembentukan pengajian buruh pabrik batik, penerbitan majalah <em>Suara Aisyiyah</em> (1926), dan pengiriman orator muda dalam Kongres Perempuan Indonesia Pertama tahun 1928.
                    </p>
                </div>
            </div>

            <!-- Babak 2 -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 p-4 rounded-4 shadow-sm bg-white position-relative overflow-hidden">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-3 p-3 mb-3 text-white" 
                         style="background-color: #c5a059; width: 50px; height: 50px;">
                        <span class="fw-bold fs-5">II</span>
                    </div>
                    <span class="badge bg-success text-white align-self-start mb-2 fw-bold">Tahun 1929 — 1965</span>
                    <h5 class="fw-bold text-dark mb-2">Era Kemerdekaan & Amal Usaha Massal</h5>
                    <p class="small text-secondary mb-0 leading-relaxed">
                        Penyelenggaraan Kongres Akbar Muhammadiyah-Aisyiyah di Surakarta (1929), pembukaan dapur umum dan palang merah di masa perang kemerdekaan, serta pendirian puluhan Taman Kanak-Kanak Bustanul Athfal (TK ABA) di seantero Jawa Tengah.
                    </p>
                </div>
            </div>

            <!-- Babak 3 -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 p-4 rounded-4 shadow-sm bg-white position-relative overflow-hidden">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-3 p-3 mb-3 text-white" 
                         style="background-color: var(--primary-green-dark); width: 50px; height: 50px;">
                        <span class="fw-bold fs-5">III</span>
                    </div>
                    <span class="badge bg-primary text-white align-self-start mb-2 fw-bold">Tahun 1966 — Sekarang</span>
                    <h5 class="fw-bold text-dark mb-2">Era Modern, Abad Kedua & Digitalisasi</h5>
                    <p class="small text-secondary mb-0 leading-relaxed">
                        Institusionalisasi PWA Jawa Tengah dengan 35 pimpinan daerah, pendirian RS PKU dan klinik bersalin modern, penanganan stunting terpadu, pemberdayaan ekonomi keluarga BUEKA, serta transformasi portal digital khazanah sejarah.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================= 5. LINI MASA SEJARAH LENGKAP (DARI DATABASE) ================= -->
<section class="py-5" id="timeline">
    <div class="container">
        <div class="section-title-wrap">
            <span class="section-subtitle">Garis Waktu Peristiwa</span>
            <h2 class="section-title">Lini Masa Tonggak Bersejarah</h2>
            <p class="text-muted small mt-2">Daftar peristiwa penting dihimpun secara dinamis dari catatan sejarah resmi Persyarikatan.</p>
        </div>

        <div class="timeline-track">
            <?php if (mysqli_num_rows($query_sejarah) > 0) : ?>
                <?php 
                $i = 0;
                while ($item = mysqli_fetch_assoc($query_sejarah)) : 
                    $is_even = ($i % 2 === 1);
                    $i++;
                ?>
                    <!-- Timeline Item -->
                    <div class="row g-0 timeline-item align-items-center">
                        <?php if (!$is_even) : ?>
                            <!-- Baris Ganjil: Teks di Kiri, Konten di Kanan -->
                            <div class="col-md-5 text-md-end pe-md-4">
                                <span class="timeline-year"><?= htmlspecialchars($item['tahun']) ?></span>
                                <h5 class="fw-bold mb-1 text-success"><?= htmlspecialchars($item['judul']) ?></h5>
                                <p class="text-muted small mb-0"><?= htmlspecialchars($item['deskripsi_singkat']) ?></p>
                            </div>
                            <div class="col-md-2 d-none d-md-block text-center position-relative">
                                <div class="timeline-dot"></div>
                            </div>
                            <div class="col-md-5 ps-md-4">
                                <div class="timeline-content">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="bi bi-bookmark-star-fill text-warning"></i>
                                        <strong class="text-dark small">Catatan Kronologis</strong>
                                    </div>
                                    <p class="small text-secondary mb-0" style="line-height: 1.7;">
                                        <?= nl2br(htmlspecialchars($item['konten_lengkap'] ?? $item['deskripsi_singkat'])) ?>
                                    </p>
                                </div>
                            </div>
                        <?php else : ?>
                            <!-- Baris Genap: Konten di Kiri, Teks di Kanan -->
                            <div class="col-md-5 text-md-end pe-md-4 order-md-1 order-2">
                                <div class="timeline-content text-start">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="bi bi-bookmark-star-fill text-warning"></i>
                                        <strong class="text-dark small">Catatan Kronologis</strong>
                                    </div>
                                    <p class="small text-secondary mb-0" style="line-height: 1.7;">
                                        <?= nl2br(htmlspecialchars($item['konten_lengkap'] ?? $item['deskripsi_singkat'])) ?>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-2 d-none d-md-block text-center position-relative order-md-2">
                                <div class="timeline-dot"></div>
                            </div>
                            <div class="col-md-5 ps-md-4 order-md-3 order-1 mb-2 mb-md-0">
                                <span class="timeline-year"><?= htmlspecialchars($item['tahun']) ?></span>
                                <h5 class="fw-bold mb-1 text-success"><?= htmlspecialchars($item['judul']) ?></h5>
                                <p class="text-muted small mb-0"><?= htmlspecialchars($item['deskripsi_singkat']) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else : ?>
                <div class="text-center py-5">
                    <p class="text-muted">Data garis waktu sejarah belum tersedia di database.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ================= 6. TAUTAN KE TOKOH & PUSTAKA ================= -->
<section class="py-5" style="background-color: #f7f5ed;">
    <div class="container">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-6">
                <div class="p-4 p-md-5 rounded-4 bg-white border shadow-sm h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="rounded-circle p-3 bg-success bg-opacity-10 text-success d-inline-block mb-3">
                            <i class="bi bi-people-fill fs-3"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-2">Tokoh Pejuang di Balik Sejarah</h4>
                        <p class="text-muted small leading-relaxed mb-4">
                            Sejarah panjang Aisyiyah di Jawa Tengah tak lepas dari ketulusan dan ketangguhan para pejuang perempuan seperti Nyai Ahmad Dahlan, Siti Hayinah, Siti Munjiyah, dan para sesepuh daerah.
                        </p>
                    </div>
                    <a href="tokoh.php" class="btn btn-outline-gold d-inline-flex align-items-center gap-2 align-self-start">
                        <span>Lihat Direktori Tokoh</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="p-4 p-md-5 rounded-4 bg-white border shadow-sm h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="rounded-circle p-3 bg-warning bg-opacity-10 text-warning d-inline-block mb-3">
                            <i class="bi bi-journal-bookmark-fill fs-3"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-2">Arsip & Dokumen Pustaka Sejarah</h4>
                        <p class="text-muted small leading-relaxed mb-4">
                            Ingin membaca naskah pidato, buku panduan kader lawas, serta dokumen hasil keputusan muktamar dan musywil secara langsung? Akses koleksi digital kami.
                        </p>
                    </div>
                    <a href="pustaka.php" class="btn btn-green d-inline-flex align-items-center gap-2 align-self-start">
                        <span>Buka Pustaka Digital</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
include 'includes/footer.php';
?>
