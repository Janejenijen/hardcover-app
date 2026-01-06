// ===============================================
// FOTOKOPI DASHBOARD - FINAL SYNC VERSION
// ===============================================

const API_GET_ORDERS = 'php/get_orders.php';
const API_UPDATE_STATUS = 'php/update_status.php';

let allOrders = [];

// ===============================
// STATUS MAP (includes old status for backward compatibility)
// ===============================
const STATUS_LABEL = {
    'MENUNGGU_PROSES': 'Menunggu',
    'MENUNGGU_VALIDASI': 'Menunggu', // Old status, same display
    'DIPROSES_FOTOKOPI': 'Diproses',
    'SELESAI': 'Selesai',
    'SUDAH_DIAMBIL': 'Diambil'
};

const STATUS_COLOR = {
    'MENUNGGU_PROSES': 'yellow',
    'MENUNGGU_VALIDASI': 'yellow', // Old status, same display
    'DIPROSES_FOTOKOPI': 'blue',
    'SELESAI': 'green',
    'SUDAH_DIAMBIL': 'gray'
};

// ===============================
// LOAD ORDERS
// ===============================
async function loadOrders() {
    try {
        const res = await fetch(API_GET_ORDERS);
        const data = await res.json();

        if (!res.ok) {
            alert(data.error || 'Gagal mengambil data');
            return;
        }

        allOrders = data.map(o => ({
            id: o.id,
            no: o.no,
            nama: o.mahasiswa_info.nama,
            nim: o.mahasiswa_info.nim,
            prodi: o.mahasiswa_info.prodi,
            wa: o.mahasiswa_info.no_wa,
            catatan: o.mahasiswa_info.catatan,
            fileUrl: o.file_pdf ? `uploads/${o.file_pdf}` : null,
            status: o.status,
            tanggal: o.created_at
        }));

        renderAll();
        updateSummary();

    } catch (err) {
        alert('Gagal terhubung ke server');
        console.error(err);
    }
}

// ===============================
// UPDATE STATUS
// ===============================
async function ubahStatus(orderId, newStatus) {
    if (!confirm('Ubah status pesanan?')) return;

    try {
        const res = await fetch(API_UPDATE_STATUS, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                order_id: orderId,
                status: newStatus
            })
        });

        const data = await res.json();

        if (res.ok && data.success) {
            loadOrders();
        } else {
            alert(data.error || 'Gagal update status');
        }

    } catch (err) {
        alert('Error koneksi');
        console.error(err);
    }
}

// ===============================
// RENDER TABLE
// ===============================
function renderAll() {
    const tbodyAntrian = document.querySelector('.antrian-table tbody');
    const tbodySemua = document.querySelector('.semua-table tbody');

    // Get filter values
    const searchAntrian = (document.getElementById('searchAntrian')?.value || '').toLowerCase();
    const filterStatusAntrian = document.getElementById('filterStatusAntrian')?.value || '';
    const searchPesanan = (document.getElementById('searchPesanan')?.value || '').toLowerCase();
    const filterStatusPesanan = document.getElementById('filterStatusPesanan')?.value || '';

    // Filter antrian (hanya yang DIPROSES_FOTOKOPI by default)
    let antrian = allOrders.filter(o => o.status === 'DIPROSES_FOTOKOPI');
    if (searchAntrian) {
        antrian = antrian.filter(o =>
            o.nama.toLowerCase().includes(searchAntrian) ||
            o.nim.toLowerCase().includes(searchAntrian)
        );
    }
    if (filterStatusAntrian) {
        antrian = antrian.filter(o => o.status === filterStatusAntrian);
    }

    // Filter semua pesanan
    let semua = [...allOrders];
    if (searchPesanan) {
        semua = semua.filter(o =>
            o.nama.toLowerCase().includes(searchPesanan) ||
            o.nim.toLowerCase().includes(searchPesanan)
        );
    }
    if (filterStatusPesanan) {
        semua = semua.filter(o => o.status === filterStatusPesanan);
    }

    renderTable(tbodyAntrian, antrian, true);
    renderTable(tbodySemua, semua, false);
}

function renderTable(tbody, data, isAntrian) {
    tbody.innerHTML = '';

    if (data.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" style="text-align:center;padding:30px;color:#888">
                    Tidak ada data
                </td>
            </tr>`;
        return;
    }

    data.forEach(order => {
        const tr = document.createElement('tr');

        if (isAntrian) {
            tr.innerHTML = `
                <td>${order.no}</td>
                <td>${order.nama}</td>
                <td>${order.nim}</td>
                <td>Hardcover</td>
                <td>${order.catatan || '-'}</td>
                <td>
                    <button class="btn-proses"
                        onclick="ubahStatus(${order.id}, 'SELESAI')">
                        Selesai
                    </button>
                    <button class="btn-detail"
                        onclick="bukaDetail(${order.id})">
                        Detail
                    </button>
                </td>
            `;
        } else {
            tr.innerHTML = `
                <td>${order.no}</td>
                <td>${order.nama}</td>
                <td>${order.nim}</td>
                <td>Hardcover</td>
                <td>
                    <span class="status ${STATUS_COLOR[order.status]}">
                        ${STATUS_LABEL[order.status]}
                    </span>
                </td>
                <td>
                    <select onchange="ubahStatus(${order.id}, this.value)">
                        <option value="MENUNGGU_PROSES" ${order.status === 'MENUNGGU_PROSES' ? 'selected' : ''}>Menunggu</option>
                        <option value="DIPROSES_FOTOKOPI" ${order.status === 'DIPROSES_FOTOKOPI' ? 'selected' : ''}>Diproses</option>
                        <option value="SELESAI" ${order.status === 'SELESAI' ? 'selected' : ''}>Selesai</option>
                        <option value="SUDAH_DIAMBIL" ${order.status === 'SUDAH_DIAMBIL' ? 'selected' : ''}>Diambil</option>
                    </select>
                    <button class="btn-detail"
                        onclick="bukaDetail(${order.id})">
                        Detail
                    </button>
                </td>
            `;
        }

        tbody.appendChild(tr);
    });
}

// ===============================
// DETAIL POPUP 
// ===============================
function bukaDetail(orderId) {
    const o = allOrders.find(x => x.id == orderId);
    if (!o) return alert('Data tidak ditemukan');

    const overlay = document.createElement('div');
    overlay.className = 'popup-overlay';
    overlay.onclick = () => overlay.remove();

    overlay.innerHTML = `
        <div class="popup-box" onclick="event.stopPropagation()">
            <div class="popup-header">
                <h3>Detail Pesanan</h3>
                <span class="popup-close" onclick="this.closest('.popup-overlay').remove()">×</span>
            </div>

            <div class="popup-content">
                <div class="info">
                    <p><b>Nama</b>: ${o.nama}</p>
                    <p><b>NIM</b>: ${o.nim}</p>
                    <p><b>Prodi</b>: ${o.prodi}</p>
                    <p><b>WhatsApp</b>: ${o.wa || '-'}</p>
                    <p><b>Catatan</b>: ${o.catatan || '-'}</p>

                    ${o.fileUrl
            ? `<a href="${o.fileUrl}" target="_blank" class="btn-download">
                                Download PDF
                           </a>`
            : `<i>Tidak ada file</i>`
        }
                </div>

                <div class="preview">
                    ${o.fileUrl
            ? `<iframe src="${o.fileUrl}" frameborder="0"></iframe>`
            : `<p style="text-align:center;color:#999">Preview tidak tersedia</p>`
        }
                </div>
            </div>
        </div>
    `;

    document.body.appendChild(overlay);
}

// ===============================
// SUMMARY
// ===============================
function updateSummary() {
    const today = new Date().toISOString().split('T')[0];

    document.querySelectorAll('.summary-item strong')[0].textContent =
        allOrders.length;

    document.querySelectorAll('.summary-item strong')[1].textContent =
        allOrders.filter(o => o.status === 'DIPROSES_FOTOKOPI').length;

    document.querySelectorAll('.summary-item strong')[2].textContent =
        allOrders.filter(o => o.status === 'SELESAI' || o.status === 'SUDAH_DIAMBIL').length;

    // Pesanan Hari Ini
    document.querySelectorAll('.summary-item strong')[3].textContent =
        allOrders.filter(o => o.tanggal && o.tanggal.startsWith(today)).length;
}

// ===============================
// INIT
// ===============================
document.addEventListener('DOMContentLoaded', loadOrders);
