<?php
require 'php/auth_check.php';
if ($_SESSION['role'] !== 'yayasan') {
    die('Akses ditolak');
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Yayasan - Hardcover UKDLSM</title>
    <link rel="stylesheet" href="css/fotokopi.css">
</head>

<body>

    <header id="top">
        <div class="top-menu">
            <a href="#beranda" onclick="scrollToSection('beranda')">Beranda</a>
            <a href="#laporan" onclick="scrollToSection('laporan')">Laporan</a>
            <a href="php/logout.php" onclick="return confirm('Yakin ingin logout?')">Logout</a>
        </div>
    </header>

    <section id="beranda" class="logo-section">
        <img src="assets/logo.png" class="logo">
        <h1>LAPORAN YAYASAN<br>HARDCOVER<br>MAHASISWA UKDLSM</h1>
    </section>

    <main class="container">

        <!-- RINGKASAN -->
        <div class="summary-box">
            <h3>Ringkasan Pemesanan</h3>
            <div class="summary-grid">
                <div class="summary-item">
                    <p>Total Pesanan</p>
                    <strong id="totalPesanan">0</strong>
                </div>
                <div class="summary-item">
                    <p>Selesai</p>
                    <strong id="selesai">0</strong>
                </div>
                <div class="summary-item">
                    <p>Diproses</p>
                    <strong id="diproses">0</strong>
                </div>
                <div class="summary-item">
                    <p>Menunggu</p>
                    <strong id="menunggu">0</strong>
                </div>
            </div>
        </div>

        <!-- FILTER TANGGAL -->
        <section id="laporan" class="table-card">
            <h3>Laporan Pemesanan Hardcover</h3>
            <div class="table-header">
                <div class="search-filter">
                    <label>Dari: </label>
                    <input type="date" id="start">
                    <label style="margin-left:15px">Sampai: </label>
                    <input type="date" id="end">
                    <button onclick="loadLaporan()">Tampilkan</button>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Total Order</th>
                        <th>Selesai</th>
                        <th>Diproses</th>
                        <th>Menunggu</th>
                    </tr>
                </thead>
                <tbody id="laporanData"></tbody>
            </table>
        </section>

        <!-- DETAIL PESANAN -->
        <section class="table-card" style="margin-top: 30px;">
            <h3>Detail Pesanan</h3>
            <div class="table-header">
                <div class="search-filter">
                    <input type="text" id="searchPesanan" placeholder="Cari NIM atau Nama...">
                    <select id="filterFakultas">
                        <option value="">Semua Fakultas</option>
                        <option value="Fakultas Pertanian">Pertanian</option>
                        <option value="Fakultas Ekonomi dan Bisnis">Ekonomi & Bisnis</option>
                        <option value="Fakultas Keperawatan">Keperawatan</option>
                        <option value="Fakultas Pariwisata">Pariwisata</option>
                        <option value="Fakultas Hukum">Hukum</option>
                        <option value="Fakultas Ilmu Pendidikan">Ilmu Pendidikan</option>
                        <option value="Fakultas Teknik">Teknik</option>
                    </select>
                    <select id="filterProdi">
                        <option value="">Semua Prodi</option>
                        <option value="Agribisnis">Agribisnis</option>
                        <option value="Akuntansi">Akuntansi</option>
                        <option value="Manajemen">Manajemen</option>
                        <option value="Fisioterapi">Fisioterapi</option>
                        <option value="Ilmu Keperawatan">Ilmu Keperawatan</option>
                        <option value="Profesi Ners">Profesi Ners</option>
                        <option value="Hospitality dan Pariwisata">Hospitality dan Pariwisata</option>
                        <option value="Ilmu Hukum">Ilmu Hukum</option>
                        <option value="Pendidikan Guru Sekolah Dasar">PGSD</option>
                        <option value="Teknik Elektro">Teknik Elektro</option>
                        <option value="Teknik Informatika">Teknik Informatika</option>
                        <option value="Teknik Industri">Teknik Industri</option>
                        <option value="Teknik Sipil">Teknik Sipil</option>
                    </select>
                    <select id="filterStatus">
                        <option value="">Semua Status</option>
                        <option value="MENUNGGU_PROSES">Menunggu</option>
                        <option value="DIPROSES_FOTOKOPI">Diproses</option>
                        <option value="SELESAI">Selesai</option>
                        <option value="SUDAH_DIAMBIL">Diambil</option>
                    </select>
                    <select id="filterSemester">
                        <option value="">Semua Semester</option>
                        <option value="Ganjil">Ganjil</option>
                        <option value="Genap">Genap</option>
                    </select>
                    <select id="filterTahunAjaran">
                        <option value="">Semua Tahun</option>
                        <option value="2023/2024">2023/2024</option>
                        <option value="2024/2025">2024/2025</option>
                        <option value="2025/2026">2025/2026</option>
                        <option value="2026/2027">2026/2027</option>
                    </select>
                    <button onclick="applyFilter()">Cari</button>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>No Antrian</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Prodi</th>
                        <th>Jenis</th>
                        <th>Jumlah Hal.</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="detailPesanan"></tbody>
            </table>
        </section>

    </main>

    <footer class="footer">
        <p>© 2025 Business Center UKDLSM</p>
    </footer>

    <script src="js/jquery.min.js"></script>
    <script src="js/yayasan.js"></script>
</body>

</html>