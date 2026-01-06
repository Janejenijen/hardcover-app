<?php
require 'php/auth_check.php';
if ($_SESSION['role'] !== 'fakultas') {
    die('Akses ditolak');
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validasi Fakultas - Hardcover UKDLSM</title>
    <link rel="stylesheet" href="css/fotokopi.css">
</head>

<body>

    <header id="top">
        <div class="top-menu">
            <a href="#beranda" onclick="scrollToSection('beranda')">Beranda</a>
            <a href="#validasi" onclick="scrollToSection('validasi')">Validasi</a>
            <a href="php/logout.php" onclick="return confirm('Yakin ingin logout?')">Logout</a>
        </div>
    </header>

    <section id="beranda" class="logo-section">
        <img src="assets/logo.png" class="logo">
        <h1>VALIDASI FAKULTAS<br>HARDCOVER<br>MAHASISWA UKDLSM</h1>
    </section>

    <main class="container">

        <!-- RINGKASAN -->
        <div class="summary-box">
            <h3>Ringkasan Validasi</h3>
            <div class="summary-grid">
                <div class="summary-item">
                    <p>Total Pengajuan</p>
                    <strong id="totalPengajuan">0</strong>
                </div>
                <div class="summary-item">
                    <p>Sudah Divalidasi</p>
                    <strong id="sudahValid">0</strong>
                </div>
                <div class="summary-item">
                    <p>Belum Divalidasi</p>
                    <strong id="belumValid">0</strong>
                </div>
            </div>
        </div>

        <!-- TABEL VALIDASI -->
        <section id="validasi" class="table-card">
            <h3>Daftar Mahasiswa</h3>

            <!-- Search & Filter -->
            <div class="filter-row">
                <input type="text" id="searchInput" placeholder="Cari NIM atau Nama..." />
                <select id="filterStatus">
                    <option value="">Semua Status</option>
                    <option value="valid">Sudah Valid</option>
                    <option value="belum">Belum Valid</option>
                </select>
                <button type="button" onclick="loadData()">Cari</button>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Prodi</th>
                        <th>Status Validasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="dataValidasi"></tbody>
            </table>
        </section>

    </main>

    <footer class="footer">
        <p>© 2025 Business Center UKDLSM</p>
    </footer>

    <script src="js/jquery.min.js"></script>
    <script src="js/fakultas.js"></script>
</body>

</html>