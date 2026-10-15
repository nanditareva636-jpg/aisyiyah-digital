<?php
/**
 * Website: Aisyiyah Digital Jawa Tengah
 * Halaman: Tentang Aisyiyah (tentang.php)
 * Fungsi: Menampilkan profil, visi misi, sejarah singkat, pilar gerakan, dan struktur PWA Jateng
 */

$page_title = "Tentang Aisyiyah - Aisyiyah Digital Jawa Tengah";
include 'includes/header.php';
include 'includes/navbar.php';
?>

<!-- ================= 1. BANNER HEADER ================= -->
<section class="py-5 position-relative overflow-hidden" 
         style="background: linear-gradient(135deg, var(--primary-green-dark) 0%, var(--primary-green) 100%); color: #fff;">
    <div class="container position-relative py-3 text-center">
        <span class="badge bg-warning text-dark px-3 py-2 fw-bold mb-2">Profil Gerakan</span>
        <h1 class="text-white fw-bold mb-3 display-5">Mengenal Aisyiyah Jawa Tengah</h1>
        <p class="text-white-50 mx-auto mb-0" style="max-width: 700px; font-size: 1.1rem; line-height: 1.8;">
            Organisasi otonom bagi perempuan Persyarikatan Muhammadiyah yang bergerak aktif memajukan peradaban melalui dakwah pendidikan, kesehatan, sosial, dan ekonomi di seluruh pelosok Jawa Tengah.
        </p>
    </div>
</section>

<!-- ================= 2. BREADCRUMB ================= -->
<div class="bg-white border-bottom py-3">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="index.php" class="text-success text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item active text-muted" aria-current="page">Tentang Kami</li>
            </ol>
        </nav>
    </div>
</div>

<!-- ================= 3. PROFIL SINGKAT & KILAS BALIK ================= -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center g-5">
            <!-- Kolom Teks Pengantar -->
            <div class="col-lg-6">
                <span class="section-subtitle text-start">Identitas & Khazanah</span>
                <h2 class="mb-3" style="color: var(--primary-green); font-size: 2.2rem;">
                    Gerakan Perempuan Islam Berkemajuan
                </h2>
                <p class="text-secondary leading-relaxed mb-3">
                    <strong>Aisyiyah</strong> didirikan di Kauman, Yogyakarta pada tanggal <strong>27 Rajab 1335 H bertepatan dengan 19 Mei 1917 M</strong> oleh pendiri Muhammadiyah, KH. Ahmad Dahlan, bersama sang istri, Nyai Ahmad Dahlan (Siti Walidah).
                </p>
                <p class="text-secondary leading-relaxed mb-4">
                    Nama <em>Aisyiyah</em> dinisbatkan kepada <strong>Siti Aisyah r.a.</strong>, istri Rasulullah SAW yang terkenal dengan kecerdasan, ketaatan, dan keteguhan perjuangannya. Semangat inilah yang menjadi jiwa gerakan perempuan muslimah untuk berpartisipasi aktif dalam pembangunan masyarakat madani yang religius dan sejahtera.
                </p>
                <div class="p-3 rounded-3 border-start border-4 border-warning bg-white shadow-sm mb-4">
                    <p class="small text-muted mb-0 fst-italic">
                        "Perempuan berkemajuan adalah insan yang memiliki alam pikiran dan kehidupan maju dalam segala aspek dengan tetap berpegang teguh pada nilai-nilai keluhuran Islam."
                    </p>
                </div>
            </div>

            <!-- Kolom Visual Box Identitas -->
            <div class="col-lg-6">
                <div class="p-4 p-md-5 rounded-4 shadow-sm border bg-white position-relative">
                    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                        <div class="navbar-brand-logo" style="width: 55px; height: 55px; font-size: 1.5rem;">
                            <i class="bi bi-moon-stars-fill"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold" style="color: var(--primary-green);">PWA Jawa Tengah</h4>
                            <small class="text-warning fw-semibold">Pimpinan Wilayah Aisyiyah</small>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3 bg-light border">
                                <small class="text-muted d-block mb-1">Tahun Berdiri Nasional</small>
                                <strong class="fs-5 text-success">1917 M (1335 H)</strong>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3 bg-light border">
                                <small class="text-muted d-block mb-1">Rintisan di Jawa Tengah</small>
                                <strong class="fs-5 text-warning">1923 M</strong>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3 bg-light border">
                                <small class="text-muted d-block mb-1">Cakupan Wilayah</small>
                                <strong class="fs-5 text-dark">35 Kab / Kota</strong>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3 bg-light border">
                                <small class="text-muted d-block mb-1">Jaringan Cabang & Ranting</small>
                                <strong class="fs-5 text-dark">4.000+ Titik</strong>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top text-center">
                        <span class="small text-muted">
                            <i class="bi bi-geo-alt-fill text-danger me-1"></i> Kantor Wilayah: Jl. Singosari Raya No. 33, Kota Semarang
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================= 4. VISI DAN MISI ================= -->
<section class="py-5" style="background-color: #f7f5ed;">
    <div class="container">
        <div class="section-title-wrap">
            <span class="section-subtitle">Arah Perjuangan</span>
            <h2 class="section-title">Visi & Misi Aisyiyah</h2>
        </div>

        <div class="row g-4">
            <!-- Visi -->
            <div class="col-lg-5">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 p-md-5 text-white" 
                     style="background: linear-gradient(135deg, var(--primary-green-dark), var(--primary-green)); border: 1px solid var(--gold);">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3 p-3 bg-warning text-dark" style="width: 55px; height: 55px;">
                        <i class="bi bi-eye-fill fs-4"></i>
                    </div>
                    <h3 class="fw-bold text-white mb-3">Visi Gerakan</h3>
                    <p class="leading-relaxed mb-4 text-white-50" style="font-size: 1.05rem;">
                        "Terwujudnya masyarakat Islam yang sebenar-benarnya melalui gerakan dakwah perempuan berkemajuan yang mandiri, unggul, berkeadaban, dan mencerahkan semesta."
                    </p>
                    <div class="border-top border-white border-opacity-10 pt-3 mt-auto">
                        <small class="text-warning fw-semibold">
                            <i class="bi bi-check2-circle me-1"></i> Landasan Muktamar Abad Kedua
                        </small>
                    </div>
                </div>
            </div>

            <!-- Misi -->
            <div class="col-lg-7">
                <div class="card h-100 border bg-white shadow-sm rounded-4 p-4 p-md-5">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3 p-3 bg-success bg-opacity-10 text-success" style="width: 55px; height: 55px;">
                        <i class="bi bi-compass-fill fs-4"></i>
                    </div>
                    <h3 class="fw-bold text-success mb-3">Misi Utama</h3>
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex gap-3 mb-3">
                            <span class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center flex-shrink-0 fw-bold" style="width: 30px; height: 30px; font-size: 0.85rem;">1</span>
                            <div>
                                <strong class="text-dark d-block">Pemurnian & Pengamalan Aqidah Islam</strong>
                                <small class="text-muted">Menanamkan keyakinan dan memperluas pemahaman ajaran Islam yang berlandaskan Al-Qur'an dan Sunnah yang shahihah.</small>
                            </div>
                        </li>
                        <li class="d-flex gap-3 mb-3">
                            <span class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center flex-shrink-0 fw-bold" style="width: 30px; height: 30px; font-size: 0.85rem;">2</span>
                            <div>
                                <strong class="text-dark d-block">Peningkatan Harkat dan Martabat Perempuan</strong>
                                <small class="text-muted">Mengembangkan potensi intelektual, spiritual, dan kepemimpinan perempuan dalam kehidupan bermasyarakat dan berbangsa.</small>
                            </div>
                        </li>
                        <li class="d-flex gap-3 mb-3">
                            <span class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center flex-shrink-0 fw-bold" style="width: 30px; height: 30px; font-size: 0.85rem;">3</span>
                            <div>
                                <strong class="text-dark d-block">Pengembangan Amal Usaha Holistik</strong>
                                <small class="text-muted">Mendirikan dan mengelola sekolah anak usia dini, balai kesehatan, panti asuhan, serta pemberdayaan ekonomi keluarga (BUEKA).</small>
                            </div>
                        </li>
                        <li class="d-flex gap-3">
                            <span class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center flex-shrink-0 fw-bold" style="width: 30px; height: 30px; font-size: 0.85rem;">4</span>
                            <div>
                                <strong class="text-dark d-block">Advokasi Sosial dan Kemanusiaan Global</strong>
                                <small class="text-muted">Memberikan perlindungan bagi kaum rentan, ibu dan anak, serta tanggap terhadap kebencanaan dan kelestarian lingkungan.</small>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================= 5. PILAR AMAL USAHA GERAKAN ================= -->
<section class="py-5">
    <div class="container">
        <div class="section-title-wrap">
            <span class="section-subtitle">Kiprah Nyata</span>
            <h2 class="section-title">Pilar Amal Usaha Aisyiyah di Jawa Tengah</h2>
        </div>

        <div class="row g-4">
            <!-- Pilar 1: Pendidikan -->
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 border p-4 rounded-4 shadow-sm bg-white hover-shadow transition">
                    <div class="rounded-3 p-3 text-white mb-3 d-inline-block" style="background-color: var(--primary-green);">
                        <i class="bi bi-mortarboard-fill fs-3"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Pendidikan Anak</h5>
                    <p class="small text-secondary mb-0">
                        Pelopor pendidikan anak usia dini terbesar di Jawa Tengah dengan lebih dari ratusan TK Aisyiyah Bustanul Athfal (TK ABA), PAUD, dan kelompok bermain.
                    </p>
                </div>
            </div>

            <!-- Pilar 2: Kesehatan -->
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 border p-4 rounded-4 shadow-sm bg-white hover-shadow transition">
                    <div class="rounded-3 p-3 text-white mb-3 d-inline-block" style="background-color: #0284c7;">
                        <i class="bi bi-heart-pulse-fill fs-3"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Kesehatan & Gizi</h5>
                    <p class="small text-secondary mb-0">
                        Layanan RS/Klinik Bersalin PKU Aisyiyah di berbagai kabupaten, edukasi posyandu balita terpadu, serta gerakan pencegahan stunting.
                    </p>
                </div>
            </div>

            <!-- Pilar 3: Sosial & Kesejahteraan -->
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 border p-4 rounded-4 shadow-sm bg-white hover-shadow transition">
                    <div class="rounded-3 p-3 text-white mb-3 d-inline-block" style="background-color: #d97706;">
                        <i class="bi bi-people-fill fs-3"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Sosial & Lansia</h5>
                    <p class="small text-secondary mb-0">
                        Panti asuhan yatim/piatu putri, santunan lansia berkemajuan, bimbingan keluarga sakinah, dan penanganan korban kekerasan perempuan.
                    </p>
                </div>
            </div>

            <!-- Pilar 4: Kemandirian Ekonomi -->
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 border p-4 rounded-4 shadow-sm bg-white hover-shadow transition">
                    <div class="rounded-3 p-3 text-white mb-3 d-inline-block" style="background-color: #15803d;">
                        <i class="bi bi-wallet2 fs-3"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Ekonomi BUEKA</h5>
                    <p class="small text-secondary mb-0">
                        Bina Usaha Ekonomi Keluarga Aisyiyah (BUEKA) menggerakkan UMKM muslimah, koperasi simpan pinjam syariah, dan bazar kemandirian pangan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================= 6. MAJELIS & LEMBAGA PWA JATENG ================= -->
<section class="py-5" style="background-color: #f7f5ed;">
    <div class="container">
        <div class="section-title-wrap">
            <span class="section-subtitle">Struktur Kerja</span>
            <h2 class="section-title">Majelis & Lembaga PWA Jawa Tengah</h2>
        </div>

        <div class="row g-3">
            <div class="col-md-4 col-sm-6">
                <div class="p-3 bg-white border rounded-3 d-flex align-items-center gap-3">
                    <i class="bi bi-book-half text-success fs-4"></i>
                    <div>
                        <strong class="d-block text-dark small">Majelis Tabligh & Ketarjihan</strong>
                        <small class="text-muted">Dakwah & Pengajian Wanita</small>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6">
                <div class="p-3 bg-white border rounded-3 d-flex align-items-center gap-3">
                    <i class="bi bi-mortarboard text-success fs-4"></i>
                    <div>
                        <strong class="d-block text-dark small">Majelis PAUD Dasmen</strong>
                        <small class="text-muted">Pendidikan TK ABA & Sekolah</small>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6">
                <div class="p-3 bg-white border rounded-3 d-flex align-items-center gap-3">
                    <i class="bi bi-hospital text-success fs-4"></i>
                    <div>
                        <strong class="d-block text-dark small">Majelis Kesehatan</strong>
                        <small class="text-muted">Klinik & Posyandu Binaan</small>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6">
                <div class="p-3 bg-white border rounded-3 d-flex align-items-center gap-3">
                    <i class="bi bi-heart text-success fs-4"></i>
                    <div>
                        <strong class="d-block text-dark small">Majelis Kesejahteraan Sosial</strong>
                        <small class="text-muted">Panti Asuhan & Pembinaan Lansia</small>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6">
                <div class="p-3 bg-white border rounded-3 d-flex align-items-center gap-3">
                    <i class="bi bi-cash-stack text-success fs-4"></i>
                    <div>
                        <strong class="d-block text-dark small">Majelis Ekonomi & Ketenagakerjaan</strong>
                        <small class="text-muted">Koperasi & UMKM BUEKA</small>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6">
                <div class="p-3 bg-white border rounded-3 d-flex align-items-center gap-3">
                    <i class="bi bi-shield-check text-success fs-4"></i>
                    <div>
                        <strong class="d-block text-dark small">Majelis Hukum & HAM</strong>
                        <small class="text-muted">Advokasi Perlindungan Perempuan</small>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6">
                <div class="p-3 bg-white border rounded-3 d-flex align-items-center gap-3">
                    <i class="bi bi-shield-shaded text-success fs-4"></i>
                    <div>
                        <strong class="d-block text-dark small">Lembaga LLHPB</strong>
                        <small class="text-muted">Lingkungan Hidup & Bencana</small>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6">
                <div class="p-3 bg-white border rounded-3 d-flex align-items-center gap-3">
                    <i class="bi bi-journal-bookmark text-success fs-4"></i>
                    <div>
                        <strong class="d-block text-dark small">Lembaga Riset (LPPA)</strong>
                        <small class="text-muted">Penelitian & Publikasi Jurnal</small>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6">
                <div class="p-3 bg-white border rounded-3 d-flex align-items-center gap-3">
                    <i class="bi bi-palette text-success fs-4"></i>
                    <div>
                        <strong class="d-block text-dark small">Lembaga Budaya & Olahraga</strong>
                        <small class="text-muted">Kesenian Islami & Kebugaran</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================= 7. CALL TO ACTION (JELAJAHI SEJARAH & TOKOH) ================= -->
<section class="py-5">
    <div class="container">
        <div class="cta-banner">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <span class="badge bg-warning text-dark px-3 py-2 fw-bold mb-2">Khazanah Inspiratif</span>
                    <h2 class="text-white mb-2">Ingin Mengenal Lebih Dalam Perjalanan Sejarah?</h2>
                    <p class="text-white-50 mb-0">
                        Jelajahi garis waktu sejarah dari tahun ke tahun serta profil lengkap tokoh-tokoh pejuang Aisyiyah yang mendedikasikan hidupnya untuk dakwah pencerahan.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end d-flex flex-wrap justify-content-lg-end gap-2">
                    <a href="sejarah.php" class="btn btn-gold">
                        Jelajahi Sejarah <i class="bi bi-compass ms-1"></i>
                    </a>
                    <a href="tokoh.php" class="btn btn-outline-light-custom">
                        Direktori Tokoh <i class="bi bi-people ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
include 'includes/footer.php';
?>
