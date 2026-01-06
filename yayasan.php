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

    </main>

    <footer class="footer">
        <p>© 2025 Business Center UKDLSM</p>
    </footer>

    <script src="js/jquery.min.js"></script>
    <script src="js/yayasan.js"></script>
</body>

</html>