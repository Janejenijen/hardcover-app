<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Aplikasi Pendaftaran Hardcover - UKDLSM</title>
  <link rel="stylesheet" href="css/style.css" /> <!-- Disesuaikan path, hilang subfolder mahasiswa/ -->
  <link rel="icon" href="assets/favicon.ico" />
  <script src="js/jquery.min.js"></script>
</head>
<body>
  <!-- Header Sticky + Bar Menu -->
  <header id="top">
    <div class="top-menu">
      <a href="javascript:void(0)" data-target="beranda" class="menu-link" onclick="scrollToSection('beranda')">Beranda</a>
      <a href="#pendaftaran" class="menu-link" onclick="scrollToSection('pendaftaran')">Pendaftaran</a>
      <a href="#kontak" class="menu-link" onclick="scrollToSection('kontak')">Kontak</a>
      <a href="login.html" class="menu-login">Login</a>
    </div>
  </header>

  <!-- Beranda -->
  <section id="beranda">
    <div class="logo-title">
      <img src="assets/logo.png" alt="Logo UKDLSM" class="logo"/>
      <div class="title">
        <h1>LAYANAN PEMBUATAN HARDCOVER <br> MAHASISWA UKDLSM</h1>
      </div>
    </div>
  </section>

  <!-- Main Content -->
  <main>
    <!-- Search Section -->
    <section class="search-section">
      <h2>Selamat Datang</h2>
      <p>Masukkan NIM atau Nama untuk cek Status Validasi / Pesanan</p>
      <div class="search-box">
        <input type="text" id="searchInput" placeholder="Contoh: 22013017 atau Janehfers" maxlength="20" />
        <button type="button" id="btnCari">Cari</button>
      </div>
      <div id="searchResult" class="search-result"></div>
      <div id="notifList" class="notif-list"></div> <!-- Tambah class untuk styling jika perlu -->
    </section>

    <!-- Form Pendaftaran -->
    <section class="form-section" id="pendaftaran">
      <h2>Form Pendaftaran</h2>
      <form id="registrationForm">
        <div class="form-grid">
          <!-- Nama Lengkap (hanya huruf & spasi) -->
          <div class="form-group">
            <label>Nama Lengkap <span class="required">*</span></label>
            <input type="text" id="nama" placeholder="Andi Putra Wijaya" required />
            <small class="error" id="error-nama"></small>
          </div>
        
          <!-- NIM (hanya angka, tepat 8 digit) -->
          <div class="form-group">
            <label>NIM <span class="required">*</span></label>
            <input type="text" id="nim" maxlength="8" placeholder="25013010" required />
            <small class="error" id="error-nim"></small>
          </div>
        
          <!-- Program Studi (dropdown 13 pilihan) -->
          <div class="form-group">
            <label>Program Studi <span class="required">*</span></label>
            <select id="prodi" required>
              <option value="" disabled selected>Pilih Program Studi</option>
              <option value="Agribisnis">Agribisnis</option>
              <option value="Akuntansi">Akuntansi</option>
              <option value="Manajemen">Manajemen</option>
              <option value="Fisioterapi">Fisioterapi</option>
              <option value="Ilmu Keperawatan">Ilmu Keperawatan</option>
              <option value="Profesi Ners">Profesi Ners</option>
              <option value="Hospitality dan Pariwisata">Hospitality dan Pariwisata</option>
              <option value="Ilmu Hukum">Ilmu Hukum</option>
              <option value="Pendidikan Guru Sekolah Dasar">Pendidikan Guru Sekolah Dasar</option>
              <option value="Teknik Elektro">Teknik Elektro</option>
              <option value="Teknik Informatika">Teknik Informatika</option>
              <option value="Teknik Industri">Teknik Industri</option>
              <option value="Teknik Sipil">Teknik Sipil</option>
            </select>
          </div>
        
          <!-- No. WhatsApp (maks 13 digit angka) -->
          <div class="form-group">
            <label>No. WhatsApp <span class="required">*</span></label>
            <input type="text" id="wa" maxlength="13" placeholder="081234567890" required />
            <small class="error" id="error-wa"></small>
          </div>
        
          <!-- Nama Dokumen (maks 200 karakter) -->
          <div class="form-group">
            <label>Nama Dokumen <span class="required">*</span></label>
            <input type="text" id="namaDokumen" maxlength="200" placeholder="Analisis Pengaruh Kualitas Produk terhadap Keputusan Pembelian" required oninput="updateCharCount()" />
            <small>Sisa karakter: <span id="charCount">200</span></small>
          </div>
        
          <!-- Upload File (hanya PDF) -->
          <div class="form-group full-width">
            <label>Upload File (PDF saja) <span class="required">*</span></label>
            <input type="file" id="fileUpload" accept=".pdf" required />
            <button type="button" class="btn-choose" onclick="document.getElementById('fileUpload').click()">Pilih File</button> <!-- Tambah onclick untuk pilih file -->
            <span id="fileName">Belum ada file dipilih</span>
            <small class="error" id="error-file"></small>
          </div>
        
          <!-- Catatan Tambahan (maks 1200 char, hitung kata) -->
          <div class="form-group full-width">
            <label>Catatan Tambahan (opsional)</label>
            <textarea id="catatan" maxlength="1200" placeholder="Contoh: Mohon hardcover warna biru tua, laminasi doff, dan jilid spiral hitam. Terima kasih." oninput="updateWordCount()"></textarea>
            <small>Kata saat ini: <span id="wordCount">0</span>/1200 karakter</small> <!-- Ubah ke karakter, sesuai maxlength -->
          </div>
        </div>

        <button type="submit" class="btn-submit">Simpan</button>
      </form>
    </section>

    <!-- Queue Status (dynamic) -->
    <section class="queue-status" id="kontak">
      <h3>Antrian saat ini</h3>
      <div class="queue-cards">
        <div class="queue-card green">
          <div class="icon">
            <div class="icon-green-wrapper">
              <img src="assets/Box.png" alt="Total Pesanan">
            </div>
          </div>
          <div class="text">
            <div>Total Pesanan</div>
            <div class="number" id="totalPesanan">0</div> <!-- Ubah ke dynamic -->
          </div>
        </div>
      
        <div class="queue-card yellow">
          <div class="icon">
            <div class="icon-yellow-wrapper">
              <img src="assets/Clock.png" alt="Sedang diproses">
            </div>
          </div>
          <div class="text">
            <div>Sedang diproses</div>
            <div class="number" id="sedangProses">0</div>
          </div>
        </div>
      
        <div class="queue-card blue">
          <div class="icon">
            <div class="icon-blue-wrapper">
              <img src="assets/Check square.png" alt="Selesai">
            </div>
          </div>
          <div class="text">
            <div>Selesai</div>
            <div class="number" id="selesai">0</div>
          </div>
        </div>
      </div>
    </section>

    <!-- Bagian Kontak -->
    <section class="contact-section">
      <h2>Kontak & Informasi</h2>
      <div class="contact-info">
        <p><strong>Lokasi:</strong> Gedung xxx lantai 1, Kairagi I Kombos Manado - 95253</p>
        <p><strong>Jam Operasional:</strong> Senin–Jumat 08:00–16:00 WITA</p>
        <p><strong>WhatsApp Admin:</strong> <a href="https://wa.me/62895803717360" target="_blank">0895-8037-17360</a></p>
      </div>
    </section>
  
  </main>

  <!-- Footer -->
  <footer>
    <p>© 2025 - Business Center Yayasan Perguruan Tinggi Universitas Katolik De La Salle Manado</p>
  </footer>

  <script src="js/script.js"></script>
</body>
</html>