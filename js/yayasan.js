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
        $('#laporanData').html(rows);
    });
}
