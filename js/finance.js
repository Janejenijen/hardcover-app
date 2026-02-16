// ===============================================
// KEUANGAN (FINANCE) VALIDATION DASHBOARD
// ===============================================

// Detect current semester
function detectCurrentSemester() {
    const month = new Date().getMonth() + 1;
    return (month >= 7) ? 'Ganjil' : 'Genap';
}

// Detect current academic year
function detectCurrentTahunAjaran() {
    const now = new Date();
    const month = now.getMonth() + 1;
    const year = now.getFullYear();
    if (month >= 7) {
        return year + '/' + (year + 1);
    } else {
        return (year - 1) + '/' + year;
    }
}

$(document).ready(function () {
    // Auto-select current semester & tahun ajaran
    $('#filterSemester').val(detectCurrentSemester());
    $('#filterTahunAjaran').val(detectCurrentTahunAjaran());

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
    var filterJenis = $('#filterJenis').val() || '';
    var filterSemester = $('#filterSemester').val() || '';
    var filterTahunAjaran = $('#filterTahunAjaran').val() || '';

    var url = 'php/get_validasi.php?search=' + encodeURIComponent(search);
    if (filterJenis) url += '&jenis=' + encodeURIComponent(filterJenis);
    if (filterSemester) url += '&semester=' + encodeURIComponent(filterSemester);
    if (filterTahunAjaran) url += '&tahun_ajaran=' + encodeURIComponent(filterTahunAjaran);

    $.getJSON(url, function (data) {
        let rows = '';
        let no = 1;
        let totalValid = 0;
        let totalBelum = 0;

        // Client-side filter for status (keuangan)
        let filtered = data;
        if (filterStatus === 'valid') {
            filtered = data.filter(item => item.valid_keuangan);
        } else if (filterStatus === 'belum') {
            filtered = data.filter(item => !item.valid_keuangan);
        }

        filtered.forEach(item => {
            if (item.valid_keuangan) totalValid++;
            else totalBelum++;

            rows += `
                <tr>
                    <td>${no++}</td>
                    <td>${item.nim}</td>
                    <td>${item.nama}</td>
                    <td>${item.prodi || '-'}</td>
                    <td>${item.fakultas_nama || '-'}</td>
                    <td><span class="badge ${item.jenis_laporan === 'SKRIPSI' ? 'badge-skripsi' : 'badge-kp'}">${item.jenis_laporan || 'KP'}</span></td>
                    <td>
                        <span class="status ${item.valid_keuangan ? 'selesai' : 'proses'}">
                            ${item.valid_keuangan ? 'VALID' : 'BELUM'}
                        </span>
                    </td>
                    <td>
                        ${item.valid_keuangan
                    ? '<button class="btn-detail" disabled>Sudah Valid</button>'
                    : `<button class="btn-proses" onclick="validasi(${item.id})">VALIDASI</button>`}
                    </td>
                </tr>
            `;
        });

        $('#dataValidasi').html(rows || '<tr><td colspan="8" style="text-align:center;padding:30px;color:#888">Tidak ada data</td></tr>');

        // Update summary from full data
        $('#totalPengajuan').text(data.length);
        $('#sudahValid').text(data.filter(i => i.valid_keuangan).length);
        $('#belumValid').text(data.filter(i => !i.valid_keuangan).length);
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
