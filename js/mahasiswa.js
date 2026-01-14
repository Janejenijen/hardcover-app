// ===============================================
// MAHASISWA LANDING PAGE - HYBRID FLOW
// Pendaftaran baru + Cek Status + Order
// ===============================================

// Helper: capitalize each word
function capitalizeWords(str) {
  return str.replace(/\b\w/g, function (char) {
    return char.toUpperCase();
  });
}

$(document).ready(function () {

  // Smooth scroll
  function scrollToSection(sectionId) {
    const el = document.getElementById(sectionId);
    if (el) el.scrollIntoView({ behavior: 'smooth' });
  }
  window.scrollToSection = scrollToSection;

  function formatDate(dateStr) {
    if (!dateStr) return '-';
    const d = new Date(dateStr);
    return d.toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' });
  }

  // Load queue stats
  $.getJSON('php/get_public_stats.php', function (stats) {
    console.log('Queue stats loaded:', stats);
    if (stats) {
      $('#totalPesanan').text(stats.total || 0);
      $('#sedangProses').text(stats.diproses || 0);
      $('#selesai').text(stats.selesai || 0);
    }
  }).fail(function (xhr, status, error) {
    console.error('Failed to load queue stats:', error);
  });

  // File upload display
  $('#fileUpload').change(function () {
    var fileName = this.files[0] ? this.files[0].name : 'Belum ada file dipilih';
    $('#fileName').text(fileName);
  });

  // Character count
  function updateCharCount() {
    var remaining = 200 - $('#namaDokumen').val().length;
    $('#charCount').text(remaining);
  }
  window.updateCharCount = updateCharCount;

  function updateWordCount() {
    var count = $('#catatan').val().length;
    $('#wordCount').text(count);
  }
  window.updateWordCount = updateWordCount;

  // ===============================
  // CEK STATUS - NIM ONLY
  // ===============================
  $('#btnCari').click(function () {
    var input = $('#searchInput').val().trim();

    if (!input) {
      alert('Masukkan NIM');
      return;
    }

    if (input.length !== 8 || !/^\d{8}$/.test(input)) {
      alert('NIM harus 8 digit angka');
      return;
    }

    $('#searchResult').html('<p style="text-align:center">Mencari...</p>').show();
    $('#orderSection').hide();
    $('#notifList').hide();

    searchNIM(input);
  });

  function searchNIM(nim) {
    $.get('php/public_order_search.php?search=' + encodeURIComponent(nim), function (data) {
      if (!data.found) {
        $('#searchResult').html('<div class="status-notfound">NIM tidak terdaftar. Silakan daftar terlebih dahulu.</div>').show();
        return;
      }

      // Status validasi
      var validasi = data.validasi;
      var isValid = validasi.lengkap;

      var statusHtml = `
        <div class="status-${isValid ? 'found' : 'notfound'}">
          <p><strong>${data.nama}</strong> (${data.nim})</p>
          <p>Prodi: ${data.prodi || '-'}</p>
          <hr>
          <h4>Status Validasi:</h4>
          <p>Fakultas: ${validasi.fakultas ? '✅ Tervalidasi' : '⏳ Menunggu'}</p>
          <p>Keuangan: ${validasi.keuangan ? '✅ Tervalidasi' : '⏳ Menunggu'}</p>
      `;

      // Status pesanan (jika ada)
      if (data.has_order) {
        var o = data.order;
        statusHtml += `
          <hr>
          <h4>Status Pesanan Hardcover:</h4>
          <div class="order-status-box status-${o.status_color}">
            <div class="order-header">
              <span class="order-number">Antrian #${o.id}</span>
              <span class="order-status ${o.status_color}">${o.status_icon} ${o.status_text}</span>
            </div>
            <p><strong>Judul:</strong> ${o.judul || '-'}</p>
            <p><strong>Tanggal Order:</strong> ${formatDate(o.tanggal_order)}</p>
            ${o.catatan ? `<p><strong>Catatan:</strong> ${o.catatan}</p>` : ''}
          </div>
        `;
      } else if (isValid) {
        statusHtml += `
          <p style="color:#4CAF50;font-weight:bold;margin-top:10px">
            ✅ Validasi lengkap! Silakan isi form order di bawah.
          </p>
        `;
      } else {
        statusHtml += `
          <p style="color:#ff6b6b;margin-top:10px">
            ⏳ Menunggu validasi. Cek berkala atau hubungi bagian terkait.
          </p>
        `;
      }

      statusHtml += '</div>';
      $('#searchResult').html(statusHtml).show();

      // Jika validasi lengkap dan belum ada order, tampilkan form order
      if (isValid && !data.has_order) {
        // Use mahasiswa_id from backend or lookup
        $.get('php/public_search.php?search=' + nim, function (mhsData) {
          if (mhsData && mhsData.length > 0) {
            var mhs = mhsData[0];
            $('#displayNama').text(mhs.nama);
            $('#displayNim').text(mhs.nim);
            $('#displayProdi').text(mhs.prodi || '-');
            $('#mahasiswaId').val(mhs.id);
            $('#pendaftaran').hide();
            $('#orderSection').show();
            scrollToSection('orderSection');
          }
        });
      }

      // Load notifikasi
      $.get('php/public_search.php?search=' + nim, function (mhsData) {
        if (mhsData && mhsData.length > 0) {
          var mhsId = mhsData[0].id;
          $.get('php/get_notif.php?mahasiswa_id=' + mhsId, function (notifs) {
            if (notifs && notifs.length > 0) {
              var html = '<h4>Notifikasi:</h4><ul>';
              notifs.forEach(n => html += `<li>${n.pesan}</li>`);
              html += '</ul>';
              $('#notifList').html(html).show();
            }
          });
        }
      });
    }).fail(function () {
      $('#searchResult').html('<div class="status-notfound">Gagal mencari. Coba lagi.</div>').show();
    });
  }

  // ===============================
  // REGISTRATION FORM
  // ===============================
  $('#registrationForm').submit(function (e) {
    e.preventDefault();

    // Reset error messages
    var valid = true;
    $('#error-nama, #error-nim, #error-wa').text('');

    var nama = capitalizeWords($('#nama').val().trim());
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
    var namaDokumen = capitalizeWords($('#namaDokumen').val().trim());
    var jumlahHalaman = $('#jumlahHalaman').val();
    var file = $('#fileUpload')[0].files[0];

    if (!mahasiswaId) {
      alert('Error: Data tidak ditemukan. Cari ulang NIM Anda.');
      return;
    }
    if (!namaDokumen) {
      alert('Judul dokumen wajib diisi');
      return;
    }
    if (!jumlahHalaman || jumlahHalaman < 1) {
      alert('Jumlah halaman wajib diisi dan minimal 1');
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
    formData.append('jumlah_halaman', jumlahHalaman);
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
          alert('✅ Order berhasil!\n\nNomor Antrian Anda: #' + orderId );
          $('#orderForm')[0].reset();
          $('#fileName').text('Belum ada file dipilih');
          $('#charCount').text('200');
          $('#wordCount').text('0');
          $('#orderSection').hide();
          $('#searchResult').hide();
        } else {
          alert('Gagal: ' + (res.error || 'Unknown error'));
        }
        $btn.prop('disabled', false).text('Kirim Order');
      },
      error: function () {
        alert('Error koneksi ke server');
        $btn.prop('disabled', false).text('Kirim Order');
      }
    });
  });
});