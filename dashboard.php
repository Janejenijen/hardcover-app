<?php
session_start();
require 'php/auth_check.php';

// Batasi role
if ($_SESSION['role'] !== 'fotokopi') {
  http_response_code(401);
  echo "Unauthorized role";
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Fotokopi - Hardcover UKDLSM</title>
  <link rel="stylesheet" href="css/fotokopi.css">
</head>

<body>

  <header id="top">
    <div class="top-menu">
      <a href="#beranda" onclick="scrollToSection('beranda')">Beranda</a>
      <a href="#pesanan" onclick="scrollToSection('pesanan')">Pesanan</a>
      <a href="php/logout.php" onclick="return confirm('Yakin ingin logout?')">Logout</a>
    </div>
  </header>

  <section id="beranda" class="logo-section">
    <img src="assets/logo.png" class="logo">
    <h1>APLIKASI PENDAFTARAN<br>HARDCOVER<br>MAHASISWA UKDLSM</h1>
  </section>

  <main class="container">

    <!-- RINGKASAN -->
    <div class="summary-box">
      <h3>Ringkasan Pesanan</h3>
      <div class="summary-grid">
        <div class="summary-item">
          <p>Total Pesanan</p>
          <strong>0</strong>
        </div>
        <div class="summary-item">
          <p>Sedang Diproses</p>
          <strong>0</strong>
        </div>
        <div class="summary-item">
          <p>Selesai</p>
          <strong>0</strong>
        </div>
        <div class="summary-item">
          <p>Pesanan Hari Ini</p>
          <strong>0</strong>
        </div>
      </div>
    </div>

    <!-- ANTRIAN -->
    <div class="table-card">
      <h3>Daftar Selesai</h3>

      <!-- Search & Filter -->
      <div class="filter-row">
        <input type="text" id="searchAntrian" placeholder="Cari NIM atau Nama..." />
        <select id="filterStatusAntrian">
          <option value="">Semua Status</option>
          <option value="MENUNGGU_PROSES">Menunggu</option>
          <option value="DIPROSES_FOTOKOPI">Diproses</option>
          <option value="SELESAI">Selesai</option>
          <option value="SUDAH_DIAMBIL">Diambil</option>
        </select>
        <button type="button" onclick="renderAll()">Cari</button>
      </div>

      <table class="antrian-table">
        <thead>
          <tr>
            <th>No Antrian</th>
            <th>Nama</th>
            <th>NIM</th>
            <th>Jenis</th>
            <th>Catatan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>

    <!-- SEMUA PESANAN -->
    <section id="pesanan" class="table-card">
      <h3>Semua Pesanan</h3>

      <!-- Search & Filter -->
      <div class="filter-row">
        <input type="text" id="searchPesanan" placeholder="Cari NIM atau Nama..." />
        <select id="filterStatusPesanan">
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
        <button type="button" onclick="renderAll()">Cari</button>
      </div>

      <table class="semua-table">
        <thead>
          <tr>
            <th>No Antrian</th>
            <th>Nama</th>
            <th>NIM</th>
            <th>Jenis</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
      <!-- Pagination -->
      <div class="pagination-container" id="paginationSemua" style="display:none;">
        <button class="pagination-btn" id="btnPrevSemua" onclick="goToPageSemua('prev')">‹ Prev</button>
        <span id="pageInfoSemua">Page 1 of 1</span>
        <button class="pagination-btn" id="btnNextSemua" onclick="goToPageSemua('next')">Next ›</button>
      </div>
    </section>

  </main>

  <footer class="footer">
    <p>© 2025 Business Center UKDLSM</p>
  </footer>

  <script src="js/fotokopi.js"></script>
</body>

</html>