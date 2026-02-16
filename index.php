<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Aplikasi Pendaftaran Hardcover - UKDLSM</title>
  <link rel="stylesheet" href="css/style.css" />
  <link rel="icon" href="assets/favicon.ico" />
  <script src="js/jquery.min.js"></script>
</head>

<body>
  <!-- Header -->
  <header id="top">
    <div class="top-menu">
      <a href="javascript:void(0)" class="menu-link" onclick="scrollToSection('beranda')">Beranda</a>
      <a href="#pendaftaran" class="menu-link" onclick="scrollToSection('pendaftaran')">Pendaftaran</a>
      <a href="#kontak" class="menu-link" onclick="scrollToSection('kontak')">Kontak</a>
      <a href="login.html" class="menu-login">Login</a>
    </div>
  </header>

  <!-- Beranda -->
  <section id="beranda">
    <div class="logo-title">
      <img src="assets/logo.png" alt="Logo UKDLSM" class="logo" />
      <div class="title">
        <h1>LAYANAN PEMBUATAN HARDCOVER <br> MAHASISWA UKDLSM</h1>
      </div>
    </div>
  </section>

  <!-- Main Content -->
  <main>
    <!-- Search Section -->
    <section class="search-section">
      <h2>Cek Status</h2>
      <p>Masukkan NIM (8 digit) untuk cek status</p>
      <div class="search-box">
        <input type="text" id="searchInput" placeholder="Masukkan NIM" maxlength="8" />
        <button type="button" id="btnCari">Cari</button>
      </div>
      <div id="searchResult" class="search-result"></div>
      <div id="notifList" class="notif-list"></div>
    </section>

    <!-- Form Pendaftaran Baru -->
    <section class="form-section" id="pendaftaran">
      <h2>Form Pendaftaran Mahasiswa</h2>
      <p style="text-align:center;margin-bottom:20px;color:#666">Belum terdaftar? Isi form di bawah untuk mendaftar</p>

      <form id="registrationForm">
        <div class="form-grid">
          <!-- Nama Lengkap -->
          <div class="form-group">
            <label>Nama Lengkap <span class="required">*</span></label>
            <input type="text" id="nama" placeholder="Contoh: Andi Putra Wijaya" required
              style="text-transform: capitalize;" />
            <small class="error" id="error-nama"></small>
          </div>

          <!-- NIM -->
          <div class="form-group">
            <label>NIM <span class="required">*</span></label>
            <input type="text" id="nim" maxlength="8" placeholder="Contoh: 22013001" required />
            <small class="error" id="error-nim"></small>
          </div>

          <!-- Program Studi -->
          <div class="form-group">
            <label>Program Studi <span class="required">*</span></label>
            <select id="prodi" required>
              <option value="" disabled selected>Pilih Program Studi</option>
              <optgroup label="Fakultas Pertanian">
                <option value="Agribisnis">Agribisnis</option>
              </optgroup>
              <optgroup label="Fakultas Ekonomi dan Bisnis">
                <option value="Akuntansi">Akuntansi</option>
                <option value="Manajemen">Manajemen</option>
              </optgroup>
              <optgroup label="Fakultas Keperawatan">
                <option value="Fisioterapi">Fisioterapi</option>
                <option value="Ilmu Keperawatan">Ilmu Keperawatan</option>
                <option value="Profesi Ners">Profesi Ners</option>
              </optgroup>
              <optgroup label="Fakultas Pariwisata">
                <option value="Hospitality dan Pariwisata">Hospitality dan Pariwisata</option>
              </optgroup>
              <optgroup label="Fakultas Hukum">
                <option value="Ilmu Hukum">Ilmu Hukum</option>
              </optgroup>
              <optgroup label="Fakultas Ilmu Pendidikan">
                <option value="Pendidikan Guru Sekolah Dasar">Pendidikan Guru Sekolah Dasar</option>
              </optgroup>
              <optgroup label="Fakultas Teknik">
                <option value="Teknik Elektro">Teknik Elektro</option>
                <option value="Teknik Informatika">Teknik Informatika</option>
                <option value="Teknik Industri">Teknik Industri</option>
                <option value="Teknik Sipil">Teknik Sipil</option>
              </optgroup>
            </select>
          </div>

          <!-- No. WhatsApp -->
          <div class="form-group">
            <label>No. WhatsApp <span class="required">*</span></label>
            <input type="text" id="wa" maxlength="13" placeholder="Contoh: 081234567890" required />
            <small class="error" id="error-wa"></small>
          </div>

          <!-- Jenis Laporan -->
          <div class="form-group">
            <label>Jenis Laporan <span class="required">*</span></label>
            <select id="jenisLaporan" required>
              <option value="" disabled selected>Pilih Jenis Laporan</option>
              <option value="KP">Kerja Praktik (KP)</option>
              <option value="SKRIPSI">Skripsi</option>
            </select>
          </div>
        </div>

        <button type="submit" class="btn-submit">Daftar Sekarang</button>
      </form>
    </section>

    <!-- Form Order (Muncul setelah validasi lengkap) -->
    <section class="form-section" id="orderSection" style="display:none;">
      <h2>Form Pemesanan Hardcover</h2>

      <div id="infoMahasiswa" style="background:#f0f8ff;padding:15px;border-radius:10px;margin-bottom:20px;">
        <p><strong>Nama:</strong> <span id="displayNama">-</span></p>
        <p><strong>NIM:</strong> <span id="displayNim">-</span></p>
        <p><strong>Prodi:</strong> <span id="displayProdi">-</span></p>
      </div>

      <form id="orderForm">
        <input type="hidden" id="mahasiswaId" />

        <div class="form-grid">
          <div class="form-group full-width">
            <label>Judul Dokumen KP/Skripsi <span class="required">*</span></label>
            <input type="text" id="namaDokumen" maxlength="200" placeholder="Judul lengkap KP/skripsi Anda" required
              oninput="updateCharCount()" style="text-transform: capitalize;" />
            <small>Sisa karakter: <span id="charCount">200</span></small>
          </div>

          <div class="form-group full-width">
            <label>Upload File (PDF, max 10MB) <span class="required">*</span></label>
            <input type="file" id="fileUpload" accept=".pdf" required />
            <button type="button" class="btn-choose" onclick="document.getElementById('fileUpload').click()">Pilih
              File</button>
            <span id="fileName">Belum ada file dipilih</span>
            <small class="error" id="error-file"></small>
          </div>

          <div class="form-group">
            <label>Jumlah Halaman <span class="required">*</span></label>
            <input type="number" id="jumlahHalaman" min="1" max="1000" placeholder="Contoh: 80" required />
            <small class="error" id="error-halaman"></small>
          </div>

          <div class="form-group full-width">
            <label>Catatan Tambahan (opsional)</label>
            <textarea id="catatan" maxlength="1200" placeholder="Contoh: Hardcover warna biru tua"
              oninput="updateWordCount()"></textarea>
            <small>Karakter: <span id="wordCount">0</span>/1200</small>
          </div>
        </div>

        <button type="submit" class="btn-submit">Kirim Order</button>
      </form>
    </section>

    <!-- Queue Status -->
    <section class="queue-status" id="kontak">
      <h3>Antrian saat ini</h3>
      <div class="queue-cards">
        <div class="queue-card green">
          <div class="icon">
            <div class="icon-green-wrapper"><img src="assets/Box.png" alt="Total"></div>
          </div>
          <div class="text">
            <div>Total Pesanan</div>
            <div class="number" id="totalPesanan">0</div>
          </div>
        </div>
        <div class="queue-card yellow">
          <div class="icon">
            <div class="icon-yellow-wrapper"><img src="assets/Clock.png" alt="Proses"></div>
          </div>
          <div class="text">
            <div>Sedang diproses</div>
            <div class="number" id="sedangProses">0</div>
          </div>
        </div>
        <div class="queue-card blue">
          <div class="icon">
            <div class="icon-blue-wrapper"><img src="assets/Check square.png" alt="Selesai"></div>
          </div>
          <div class="text">
            <div>Selesai</div>
            <div class="number" id="selesai">0</div>
          </div>
        </div>
      </div>
    </section>

    <!-- Kontak -->
    <section class="contact-section">
      <h2>Kontak & Informasi</h2>
      <div class="contact-info">
        <p><strong>Lokasi:</strong> Gedung xxx lantai 1, Kairagi I Kombos Manado - 95253</p>
        <p><strong>Jam Operasional:</strong> Senin–Jumat 08:00–16:00 WITA</p>
        <p><strong>WhatsApp:</strong> <a href="https://wa.me/62895803717360" target="_blank">0895-8037-17360</a>
        </p>
      </div>
    </section>
  </main>

  <!-- Footer -->
  <footer>
    <p>© 2025 - Business Center Yayasan Perguruan Tinggi Universitas Katolik De La Salle Manado</p>
  </footer>

  <script src="js/mahasiswa.js"></script>
</body>

</html>