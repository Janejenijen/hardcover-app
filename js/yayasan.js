// ===============================================
// YAYASAN REPORT DASHBOARD
// ===============================================

let allOrders = [];
let filteredOrders = [];
let currentPage = 1;
const itemsPerPage = 10;

$(document).ready(function () {
    loadSummary();
    loadAllOrders();

    // Set default date range (last 30 days)
    const today = new Date();
    const lastMonth = new Date(today.getTime() - (30 * 24 * 60 * 60 * 1000));
    $('#end').val(today.toISOString().split('T')[0]);
    $('#start').val(lastMonth.toISOString().split('T')[0]);

    // AUTO-SELECT current academic year and semester
    const currentTahunAjaran = detectCurrentTahunAjaran();
    const currentSemester = detectCurrentSemester();
    $('#filterTahunAjaran').val(currentTahunAjaran);
    $('#filterSemester').val(currentSemester);
});

// Detect current academic year
function detectCurrentTahunAjaran() {
    const now = new Date();
    const month = now.getMonth() + 1; // 1-12
    const year = now.getFullYear();

    // Juli - Desember: tahun X/X+1
    if (month >= 7) {
        return year + '/' + (year + 1);
    } else {
        // Januari - Juni: tahun (X-1)/X
        return (year - 1) + '/' + year;
    }
}

// Detect current semester
function detectCurrentSemester() {
    const now = new Date();
    const month = now.getMonth() + 1; // 1-12

    // Juli - Desember: Ganjil
    // Januari - Juni: Genap
    return (month >= 7) ? 'Ganjil' : 'Genap';
}

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

// Load all orders for detail table
function loadAllOrders() {
    $.getJSON('php/get_orders.php', function (data) {
        allOrders = data.map(o => ({
            id: o.id,
            nim: o.mahasiswa_info.nim,
            nama: o.mahasiswa_info.nama,
            prodi: o.mahasiswa_info.prodi,
            jenisLaporan: o.mahasiswa_info.jenis_laporan,
            jumlahHalaman: o.mahasiswa_info.jumlah_halaman,
            wa: o.mahasiswa_info.no_wa,
            catatan: o.mahasiswa_info.catatan,
            status: o.status,
            tanggal: o.created_at,
            semester: o.semester,
            tahunAjaran: o.tahun_ajaran
        }));
        applyFilter();
    });
}

// Apply search and prodi filter
function applyFilter() {
    const searchTerm = $('#searchPesanan').val().toLowerCase();
    const fakultasFilter = $('#filterFakultas').val();
    const prodiFilter = $('#filterProdi').val();
    const statusFilter = $('#filterStatus').val();
    const semesterFilter = $('#filterSemester').val();
    const tahunAjaranFilter = $('#filterTahunAjaran').val();

    // Mapping prodi to fakultas
    const prodiToFakultas = {
        'Agribisnis': 'Fakultas Pertanian',
        'Akuntansi': 'Fakultas Ekonomi dan Bisnis',
        'Manajemen': 'Fakultas Ekonomi dan Bisnis',
        'Fisioterapi': 'Fakultas Keperawatan',
        'Ilmu Keperawatan': 'Fakultas Keperawatan',
        'Profesi Ners': 'Fakultas Keperawatan',
        'Hospitality dan Pariwisata': 'Fakultas Pariwisata',
        'Ilmu Hukum': 'Fakultas Hukum',
        'Pendidikan Guru Sekolah Dasar': 'Fakultas Ilmu Pendidikan',
        'Teknik Elektro': 'Fakultas Teknik',
        'Teknik Informatika': 'Fakultas Teknik',
        'Teknik Industri': 'Fakultas Teknik',
        'Teknik Sipil': 'Fakultas Teknik'
    };

    filteredOrders = allOrders.filter(o => {
        const matchSearch = !searchTerm ||
            o.nim.toLowerCase().includes(searchTerm) ||
            o.nama.toLowerCase().includes(searchTerm);
        const matchFakultas = !fakultasFilter || prodiToFakultas[o.prodi] === fakultasFilter;
        const matchProdi = !prodiFilter || o.prodi === prodiFilter;
        const matchStatus = !statusFilter || o.status === statusFilter;
        const matchSemester = !semesterFilter || o.semester === semesterFilter;
        const matchTahunAjaran = !tahunAjaranFilter || o.tahunAjaran === tahunAjaranFilter;
        return matchSearch && matchFakultas && matchProdi && matchStatus && matchSemester && matchTahunAjaran;
    });

    currentPage = 1; // Reset to first page
    renderDetailTable();
}

// Render detail table with pagination
function renderDetailTable() {
    const tbody = $('#detailPesanan');
    const totalData = filteredOrders.length;

    // Show/hide pagination
    const paginationContainer = $('#paginationContainer');
    if (totalData > itemsPerPage) {
        paginationContainer.show();
    } else {
        paginationContainer.hide();
    }

    // Calculate pagination
    const totalPages = Math.ceil(totalData / itemsPerPage);
    const startIdx = (currentPage - 1) * itemsPerPage;
    const endIdx = Math.min(startIdx + itemsPerPage, totalData);
    const pageData = filteredOrders.slice(startIdx, endIdx);

    // Update page info
    $('#pageInfo').text(`Page ${currentPage} of ${totalPages || 1}`);
    $('#btnPrevPage').prop('disabled', currentPage === 1);
    $('#btnNextPage').prop('disabled', currentPage >= totalPages);

    // Render table
    if (pageData.length === 0) {
        tbody.html('<tr><td colspan="8" style="text-align:center;padding:30px;color:#888">Tidak ada data</td></tr>');
        return;
    }

    let rows = '';
    pageData.forEach(o => {
        const statusLabel = getStatusLabel(o.status);
        const statusColor = getStatusColor(o.status);

        rows += `
            <tr>
                <td>#${o.id}</td>
                <td>${o.nim}</td>
                <td>${o.nama}</td>
                <td>${o.prodi}</td>
                <td>${o.jenisLaporan || 'KP'}</td>
                <td>${o.jumlahHalaman || '-'}</td>
                <td><span class="status ${statusColor}">${statusLabel}</span></td>
                <td><button class="btn-detail" onclick="showDetail(${o.id})">Detail</button></td>
            </tr>
        `;
    });
    tbody.html(rows);
}

// Get status label
function getStatusLabel(status) {
    const labels = {
        'MENUNGGU_PROSES': 'Menunggu',
        'MENUNGGU_VALIDASI': 'Menunggu',
        'DIPROSES_FOTOKOPI': 'Diproses',
        'SELESAI': 'Selesai',
        'SUDAH_DIAMBIL': 'Diambil'
    };
    return labels[status] || status;
}

// Get status color
function getStatusColor(status) {
    const colors = {
        'MENUNGGU_PROSES': 'yellow',
        'MENUNGGU_VALIDASI': 'yellow',
        'DIPROSES_FOTOKOPI': 'red',
        'SELESAI': 'green',
        'SUDAH_DIAMBIL': 'blue'
    };
    return colors[status] || 'gray';
}

// Show detail popup
function showDetail(orderId) {
    const order = allOrders.find(o => o.id === orderId);
    if (!order) return;

    const overlay = document.createElement('div');
    overlay.className = 'popup-overlay';
    document.body.appendChild(overlay);
    overlay.onclick = () => overlay.remove();

    overlay.innerHTML = `
        <div class="popup-box" onclick="event.stopPropagation()">
            <div class="popup-header">
                <h3>Detail Pesanan #${order.id}</h3>
                <span class="popup-close" onclick="this.closest('.popup-overlay').remove()">×</span>
            </div>
            <div class="popup-content">
                <div class="info">
                    <p><b>NIM</b>: ${order.nim}</p>
                    <p><b>Nama</b>: ${order.nama}</p>
                    <p><b>Prodi</b>: ${order.prodi}</p>
                    <p><b>Jenis Laporan</b>: ${order.jenisLaporan || 'KP'}</p>
                    <p><b>Jumlah Halaman</b>: ${order.jumlahHalaman || '-'}</p>
                    <p><b>WhatsApp</b>: ${order.wa || '-'}</p>
                    <p><b>Catatan</b>: ${order.catatan || '-'}</p>
                    <p><b>Status</b>: <span class="status ${getStatusColor(order.status)}">${getStatusLabel(order.status)}</span></p>
                    <p><b>Tanggal Order</b>: ${order.tanggal}</p>
                </div>
            </div>
        </div>
    `;
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

// ===============================================
// PAGINATION
// ===============================================
function goToPage(direction) {
    const totalPages = Math.ceil(filteredOrders.length / itemsPerPage);

    if (direction === 'prev' && currentPage > 1) {
        currentPage--;
    } else if (direction === 'next' && currentPage < totalPages) {
        currentPage++;
    }

    renderDetailTable();

    // Scroll to top of table
    document.getElementById('detailPesanan').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// ===============================================
// EXPORT EXCEL
// ===============================================
function showExportModal() {
    document.getElementById('exportModal').style.display = 'flex';
}

function closeExportModal() {
    document.getElementById('exportModal').style.display = 'none';
}

function executeExport() {
    const filterValue = document.querySelector('input[name="exportFilter"]:checked').value;

    // Redirect to download endpoint
    window.location.href = `php/export_excel.php?status=${filterValue}`;

    // Close modal after short delay
    setTimeout(() => {
        closeExportModal();
    }, 500);
}
