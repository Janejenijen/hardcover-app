// ===============================================
// MAHASISWA LANDING PAGE - HYBRID FLOW
// Pendaftaran baru + Cek Status + Order
// ===============================================

$(document).ready(function () {

  // Smooth scroll
  function scrollToSection(sectionId) {
    const el = document.getElementById(sectionId);
    if (el) el.scrollIntoView({ behavior: 'smooth' });
  }
  window.scrollToSection = scrollToSection;

  // File name display
  $('#fileUpload').change(function () {
    $('#fileName').text(this.files[0] ? this.files[0].name : 'Belum ada file dipilih');
  });

  // Char counts
  window.updateCharCount = function () {
    $('#charCount').text(200 - $('#namaDokumen').val().length);
  };
  window.updateWordCount = function () {
    $('#wordCount').text($('#catatan').val().length);
  };

  // ===============================
  // LOAD QUEUE STATUS
  // ===============================
  function loadQueueStatus() {
    $.get('php/public_queue.php', function (data) {
      $('#totalPesanan').text(data.total || 0);
      $('#sedangProses').text(data.diproses || 0);
      $('#selesai').text(data.selesai || 0);
    });
  }
  loadQueueStatus();

  // ===============================
  // SEARCH NIM or ORDER NUMBER
  // ===============================
  $('#btnCari').click(function () {
    var input = $('#searchInput').val().trim();

    if (!input || !/^\d+$/.test(input)) {
      alert('Masukkan NIM (8 digit) atau nomor antrian');
      return;
    }

    $('#searchResult').html('<p style="text-align:center">Mencari...</p>').show();
    $('#orderSection').hide();
    $('#notifList').hide();

    // Jika bukan 8 digit, coba cari sebagai nomor antrian dulu
    if (input.length !== 8) {
      searchOrder(input);
    } else {
      // 8 digit: bisa NIM atau nomor antrian, coba NIM dulu
      searchNIM(input);
    }
  });

  function searchOrder(orderId) {
    $.get('php/public_order_search.php?search=' + encodeURIComponent(orderId), function (data) {
      if (data.found && data.type === 'order') {
        var o = data.order;
        var statusHtml = `
          <div class="status-order status-${o.status_color}">
            <div class="order-header">
              <span class="order-number">Antrian #${o.id}</span>
              <span class="order-status ${o.status_color}">${o.status_icon} ${o.status_text}</span>
            </div>
            <hr>
            <p><strong>Judul:</strong> ${o.judul || '-'}</p>
            <p><strong>Pemesan:</strong> ${o.nama} (${o.nim})</p>
            <p><strong>Prodi:</strong> ${o.prodi || '-'}</p>
            <p><strong>Tanggal Order:</strong> ${formatDate(o.tanggal_order)}</p>
            ${o.catatan ? `<p><strong>Catatan:</strong> ${o.catatan}</p>` : ''}
          </div>
        `;
        $('#searchResult').html(statusHtml).show();
      } else {
        $('#searchResult').html(`
          <div class="status-notfound">
            <p>Nomor antrian <strong>#${orderId}</strong> tidak ditemukan.</p>
            <p>Pastikan nomor antrian benar atau cari dengan NIM (8 digit).</p>
          </div>
        `).show();
      }
    }).fail(function () {
      $('#searchResult').html('<div class="status-notfound">Gagal mencari. Coba lagi.</div>').show();
    });
  }

  function searchNIM(nim) {
    $.get('php/public_search.php?search=' + encodeURIComponent(nim), function (data) {
      if (data.length === 0) {
        // Tidak ada NIM, coba cari sebagai order
        searchOrder(nim);
        return;
      }

      var mhs = data[0];
      var isValid = mhs.valid_fakultas && mhs.valid_keuangan;

      var statusHtml = `
        <div class="status-${isValid ? 'found' : 'notfound'}">
          <p><strong>${mhs.nama}</strong> (${mhs.nim})</p>
          <p>Prodi: ${mhs.prodi || '-'}</p>
          <hr>
          <p>Fakultas: ${mhs.valid_fakultas ? '✅ Tervalidasi' : '⏳ Menunggu'}</p>
          <p>Keuangan: ${mhs.valid_keuangan ? '✅ Tervalidasi' : '⏳ Menunggu'}</p>
          ${isValid
          ? '<p style="color:#4CAF50;font-weight:bold;margin-top:10px">✅ Validasi lengkap! Silakan isi form order di bawah.</p>'
          : '<p style="color:#ff6b6b;margin-top:10px">⏳ Menunggu validasi. Cek berkala atau hubungi bagian terkait.</p>'}
        </div>
      `;
      $('#searchResult').html(statusHtml).show();

      if (isValid) {
        $('#displayNama').text(mhs.nama);
        $('#displayNim').text(mhs.nim);
        $('#displayProdi').text(mhs.prodi || '-');
        $('#mahasiswaId').val(mhs.id);
        $('#pendaftaran').hide(); // Hide registration form
        $('#orderSection').show();
        scrollToSection('orderSection');
      }

      // Load notifikasi
      $.get('php/get_notif.php?mahasiswa_id=' + mhs.id, function (notifs) {
        if (notifs && notifs.length > 0) {
          var html = '<h4>Notifikasi:</h4><ul>';
          notifs.forEach(n => html += `<li>${n.pesan}</li>`);
          html += '</ul>';
          $('#notifList').html(html).show();
        }
      });
    }).fail(function () {
      $('#searchResult').html('<div class="status-notfound">Gagal mencari. Coba lagi.</div>').show();
    });
  }

  function formatDate(dateStr) {
    if (!dateStr) return '-';
    var d = new Date(dateStr);
    return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' });
  }

  // Enter key search
  $('#searchInput').keypress(function (e) {
    if (e.which === 13) $('#btnCari').click();
  });

  // ===============================
  // REGISTRATION FORM
  // ===============================
  $('#registrationForm').submit(function (e) {
    e.preventDefault();

    // Validate
    var valid = true;
    $('#error-nama, #error-nim, #error-wa').text('');

    var nama = $('#nama').val().trim();
    var nim = $('#nim').val().trim();
    var prodi = $('#prodi').val();
    var wa = $('#wa').val().trim();
    var jenisLaporan = $('#jenisLaporan').val();

    if (!/^[a-zA-Z\s]+$/.test(nama)) {
      $('#error-nama').text('Hanya huruf dan spasi');
      valid = false;
    }
    if (!/^\d{8}$/.test(nim)) {
      $('#error-nim').text('Tepat 8 digit angka');
      valid = false;
    }
    if (!prodi) {
      alert('Pilih program studi');
      valid = false;
    }
    if (!/^\d{10,13}$/.test(wa)) {
      $('#error-wa').text('10-13 digit angka');
      valid = false;
    }
    if (!jenisLaporan) {
      alert('Pilih jenis laporan');
      valid = false;
    }

    if (!valid) return;

    var $btn = $(this).find('button[type="submit"]');
    $btn.prop('disabled', true).text('Mendaftar...');

    $.ajax({
      url: 'php/register_mahasiswa.php',
      type: 'POST',
      data: { nama: nama, nim: nim, prodi: prodi, no_wa: wa, jenis_laporan: jenisLaporan },
      dataType: 'json',
      success: function (res) {
        if (res.success) {
          alert('✅ Pendaftaran berhasil!\n\nData Anda sedang menunggu validasi dari Fakultas dan Keuangan.\n\nGunakan fitur "Cek Status" dengan NIM Anda untuk melihat progress.');
          $('#registrationForm')[0].reset();
        } else {
          alert('Gagal: ' + (res.error || 'Unknown error'));
        }
        $btn.prop('disabled', false).text('Daftar Sekarang');
      },
      error: function () {
        alert('Error koneksi ke server');
        $btn.prop('disabled', false).text('Daftar Sekarang');
      }
    });
  });

  // ===============================
  // ORDER FORM
  // ===============================
  $('#orderForm').submit(function (e) {
    e.preventDefault();

    var mahasiswaId = $('#mahasiswaId').val();
    var namaDokumen = $('#namaDokumen').val().trim();
    var file = $('#fileUpload')[0].files[0];

    if (!mahasiswaId) {
      alert('Error: Data tidak ditemukan. Cari ulang NIM Anda.');
      return;
    }
    if (!namaDokumen) {
      alert('Judul dokumen wajib diisi');
      return;
    }
    if (!file) {
      alert('Pilih file PDF');
      return;
    }
    if (file.size > 10485760 || file.type !== 'application/pdf') {
      alert('File harus PDF dan max 10MB');
      return;
    }

    var formData = new FormData();
    formData.append('mahasiswa_id', mahasiswaId);
    formData.append('nama_dokumen', namaDokumen);
    formData.append('catatan', $('#catatan').val().trim());
    formData.append('file', file);

    var $btn = $(this).find('button[type="submit"]');
    $btn.prop('disabled', true).text('Mengirim...');

    $.ajax({
      url: 'php/place_order.php',
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function (res) {
        if (res.success) {
          var orderId = res.order_id || '';
          alert('✅ Order berhasil!\n\nNomor Antrian Anda: #' + orderId + '\n\nSimpan nomor ini untuk cek status pesanan.');
          $('#orderForm')[0].reset();
          $('#fileName').text('Belum ada file dipilih');
          $('#charCount').text('200');
          $('#wordCount').text('0');
          $('#orderSection').hide();
          $('#searchResult').hide();
          $('#searchInput').val('');
          $('#pendaftaran').show();
          loadQueueStatus();
          scrollToSection('beranda');
        } else {
          alert('Gagal: ' + (res.error || 'Unknown error'));
        }
        $btn.prop('disabled', false).text('Submit Order');
      },
      error: function () {
        alert('Error koneksi');
        $btn.prop('disabled', false).text('Submit Order');
      }
    });
  });

});