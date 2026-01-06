// ===============================================
// FAKULTAS VALIDATION DASHBOARD
// ===============================================

$(document).ready(function () {
    loadData();

    // Enter key search
    $('#searchInput').keypress(function (e) {
        if (e.which === 13) loadData();
    });
});

// Smooth scroll
function scrollToSection(id) {
    document.getElementById(id).scrollIntoView({ behavior: 'smooth' });
}

function loadData() {
    var search = $('#searchInput').val() || '';
    var filterStatus = $('#filterStatus').val() || '';

    var url = 'php/get_validasi.php?search=' + encodeURIComponent(search);

    $.getJSON(url, function (data) {
        let rows = '';
        let no = 1;
        let totalValid = 0;
        let totalBelum = 0;

        // Client-side filter for status
        let filtered = data;
        if (filterStatus === 'valid') {
            filtered = data.filter(item => item.valid_fakultas);
        } else if (filterStatus === 'belum') {
            filtered = data.filter(item => !item.valid_fakultas);
        }

        filtered.forEach(item => {
            if (item.valid_fakultas) totalValid++;
            else totalBelum++;

            rows += `
                <tr>
                    <td>${no++}</td>
                    <td>${item.nim}</td>
                    <td>${item.nama}</td>
                    <td>${item.prodi || '-'}</td>
                    <td>
                        <span class="status ${item.valid_fakultas ? 'selesai' : 'proses'}">
                            ${item.valid_fakultas ? 'VALID' : 'BELUM'}
                        </span>
                    </td>
                    <td>
                        ${item.valid_fakultas
                    ? '<button class="btn-detail" disabled>Sudah Valid</button>'
                    : `<button class="btn-proses" onclick="validasi(${item.id})">VALIDASI</button>`}
                    </td>
                </tr>
            `;
        });

        $('#dataValidasi').html(rows || '<tr><td colspan="6" style="text-align:center;padding:30px;color:#888">Tidak ada data</td></tr>');

        // Update summary from full data
        $('#totalPengajuan').text(data.length);
        $('#sudahValid').text(data.filter(i => i.valid_fakultas).length);
        $('#belumValid').text(data.filter(i => !i.valid_fakultas).length);
    });
}

function validasi(id) {
    if (!confirm('Yakin validasi mahasiswa ini?')) return;

    fetch('php/validate.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: parseInt(id) })
    })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                alert('Validasi berhasil');
                loadData();
            } else {
                alert(res.error || 'Gagal validasi');
            }
        })
        .catch(err => {
            alert('Error koneksi');
            console.error(err);
        });
}
