$(document).ready(function() {
  // Smooth scroll untuk menu
  function scrollToSection(sectionId) {
    document.getElementById(sectionId).scrollIntoView({ behavior: 'smooth' });
  }
  window.scrollToSection = scrollToSection; // Expose untuk onclick

  // Update file name saat pilih file
  $('#fileUpload').change(function() {
    $('#fileName').text(this.files[0] ? this.files[0].name : 'Belum ada file dipilih');
  });

  // Validasi input sebelum submit
  function validateForm() {
    var valid = true;
    // Nama: hanya huruf & spasi
    if (!/^[a-zA-Z\s]+$/.test($('#nama').val())) {
      $('#error-nama').text('Hanya huruf dan spasi');
      valid = false;
    } else $('#error-nama').text('');
    // NIM: tepat 8 digit angka
    if (!/^\d{8}$/.test($('#nim').val())) {
      $('#error-nim').text('Tepat 8 digit angka');
      valid = false;
    } else $('#error-nim').text('');
    // WA: 10-13 digit angka
    if (!/^\d{10,13}$/.test($('#wa').val())) {
      $('#error-wa').text('10-13 digit angka');
      valid = false;
    } else $('#error-wa').text('');
    // File: PDF & <10MB
    var file = $('#fileUpload')[0].files[0];
    if (file && (file.size > 10485760 || file.type !== 'application/pdf')) {
      $('#error-file').text('PDF saja, max 10MB');
      valid = false;
    } else $('#error-file').text('');
    return valid;
  }

  // Char count untuk namaDokumen
  window.updateCharCount = function() {
    var len = $('#namaDokumen').val().length;
    $('#charCount').text(200 - len);
  };

  // Word count untuk catatan (sebenarnya char, tapi hitung kata approx)
  window.updateWordCount = function() {
    var text = $('#catatan').val();
    var count = text.length; // Ganti ke char count, sesuai maxlength 1200
    $('#wordCount').text(count);
  };

  // Load dynamic queue status
  function loadQueueStatus() {
    $.get('php/get_orders.php', function(data) {
      var total = data.length;
      var proses = data.filter(o => o.status === 'DIPROSES_FOTOKOPI').length;
      var selesai = data.filter(o => o.status === 'SELESAI' || o.status === 'SUDAH_DIAMBIL').length;
      $('#totalPesanan').text(total);
      $('#sedangProses').text(proses);
      $('#selesai').text(selesai);
    });
  }
  loadQueueStatus(); // Call on load

  // Search button
  $('#btnCari').click(function() {
    var search = $('#searchInput').val();
    if (!search) return alert('Masukkan NIM/Nama');
    $.get('php/get_validasi.php?search=' + search, function(data) {
      $('#searchResult').html('').hide();
      $('#notifList').html('');
      if (data.length > 0) {
        var item = data[0];
        var allValid = item.valid_fakultas && item.valid_keuangan && item.valid_yayasan;
        var status = `
          <div class="status-${allValid ? 'found' : 'notfound'}">
            <p>Nama: ${item.nama} (NIM: ${item.nim})</p>
            <p>Fakultas: ${item.valid_fakultas ? 'Sudah' : 'Belum'}</p>
            <p>Keuangan: ${item.valid_keuangan ? 'Sudah' : 'Belum'}</p>
            <p>Yayasan: ${item.valid_yayasan ? 'Sudah' : 'Belum'}</p>
            ${!allValid ? '<button id="ajukanBtn">Ajukan Validasi</button>' : '<p>Validasi lengkap, bisa order.</p>'}
          </div>
        `;
        $('#searchResult').html(status).show();
        if (!allValid) {
          $('#ajukanBtn').click(function() {
            $.ajax({
              url: 'php/ajukan_validasi.php',
              type: 'POST',
              contentType: 'application/json',
              data: JSON.stringify({mahasiswa_id: item.id}),
              success: function(res) {
                alert(res.success ? 'Pengajuan dikirim!' : res.error);
                loadQueueStatus(); // Refresh jika perlu
              }
            });
          });
        }
        // Fetch notif
        $.get('php/get_notif.php?mahasiswa_id=' + item.id, function(notifs) {
          if (notifs.length > 0) {
            var notifHtml = '<h4>Notifikasi:</h4><ul>';
            notifs.forEach(n => notifHtml += `<li>${n.pesan} (${n.created_at})</li>`);
            notifHtml += '</ul>';
            $('#notifList').html(notifHtml);
          }
        });
      } else {
        $('#searchResult').html('<div class="status-notfound">Tidak ditemukan. Daftar dulu?</div>').show();
      }
    });
  });

  // Form submit
  $('#registrationForm').submit(function(e) {
    e.preventDefault();
    if (!validateForm()) return;
    var nim = $('#nim').val();
    $.get('php/get_validasi.php?search=' + nim, function(data) {
      if (data.length > 0 && data[0].valid_fakultas && data[0].valid_keuangan && data[0].valid_yayasan) {
        var formData = new FormData($('#registrationForm')[0]);
        formData.append('mahasiswa_id', data[0].id); // Tambah ID mahasiswa
        formData.append('nama', $('#nama').val()); // Tambah fields lain jika perlu (backend bisa ambil dari DB)
        formData.append('nim', nim);
        formData.append('prodi', $('#prodi').val());
        formData.append('no_wa', $('#wa').val());
        formData.append('nama_dokumen', $('#namaDokumen').val()); // Tambah untuk insert mahasiswa jika baru
        formData.append('catatan', $('#catatan').val());
        formData.append('file', $('#fileUpload')[0].files[0]);
        $.ajax({
          url: 'php/place_order.php',
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(res) {
            alert(res.success ? 'Order berhasil disimpan!' : res.error);
            loadQueueStatus(); // Refresh queue
          },
          error: function() { alert('Error koneksi'); }
        });
      } else {
        alert('Validasi belum lengkap. Ajukan dulu via search.');
      }
    });
  });
});