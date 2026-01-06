// ===============================================
// YAYASAN REPORT DASHBOARD
// ===============================================

$(document).ready(function () {
    loadSummary();

    // Set default date range (last 30 days)
    const today = new Date();
    const lastMonth = new Date(today.getTime() - (30 * 24 * 60 * 60 * 1000));
    $('#end').val(today.toISOString().split('T')[0]);
    $('#start').val(lastMonth.toISOString().split('T')[0]);
});

// Smooth scroll
function scrollToSection(id) {
    document.getElementById(id).scrollIntoView({ behavior: 'smooth' });
}

// Load summary counts on page load
function loadSummary() {
    $.getJSON('php/get_orders.php', function (data) {
        let total = data.length;
        let selesai = 0, diproses = 0, menunggu = 0;

        data.forEach(o => {
            if (o.status === 'SELESAI' || o.status === 'SUDAH_DIAMBIL') selesai++;
            else if (o.status === 'DIPROSES_FOTOKOPI') diproses++;
            else menunggu++;
        });

        $('#totalPesanan').text(total);
        $('#selesai').text(selesai);
        $('#diproses').text(diproses);
        $('#menunggu').text(menunggu);
    });
}

function loadLaporan() {
    const start = $('#start').val();
    const end = $('#end').val();

    if (!start || !end) {
        alert('Pilih rentang tanggal');
        return;
    }

    $.getJSON('php/get_laporan.php', {
        start: start,
        end: end
    }, function (data) {
        let rows = '';

        if (data.length === 0) {
            rows = '<tr><td colspan="5" style="text-align:center;padding:30px;color:#888">Tidak ada data pada rentang tanggal tersebut</td></tr>';
        } else {
            data.forEach(item => {
                rows += `
                    <tr>
                        <td>${item.tanggal}</td>
                        <td>${item.total}</td>
                        <td>${item.selesai}</td>
                        <td>${item.diproses}</td>
                        <td>${item.menunggu}</td>
                    </tr>
                `;
            });
        }
        $('#laporanData').html(rows);
    }).fail(function () {
        alert('Gagal memuat laporan');
    });
}
