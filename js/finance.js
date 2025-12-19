$(document).ready(function () {
    loadData();
});

function loadData() {
    $.getJSON('php/get_validasi.php', function (data) {
        let rows = '';
        data.forEach(item => {
            rows += `
                <tr>
                    <td>${item.nim}</td>
                    <td>${item.nama}</td>
                    <td>${item.valid_keuangan ? 'VALID' : 'BELUM'}</td>
                    <td>
                        ${item.valid_keuangan 
                          ? '-' 
                          : `<button onclick="validasi(${item.id})">VALIDASI</button>`}
                    </td>
                </tr>
            `;
        });
        $('#dataValidasi').html(rows);
    });
}

function validasi(id) {
    if (!confirm('Yakin validasi keuangan?')) return;

    fetch('php/validate.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id })
    })
    .then(res => res.json())
    .then(res => {
        alert('Validasi berhasil');
        loadData();
    });
}
