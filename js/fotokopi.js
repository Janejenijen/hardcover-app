// ===============================================
// FOTOKOPI DASHBOARD - PHP BACKEND VERSION
// ===============================================

const API_GET_ORDERS = 'php/get_orders.php';
const API_UPDATE_STATUS = 'php/update_status.php';

let allOrders = [];

/**
 * Mapping status DB → label UI
 */
const STATUS_LABEL = {
    MENUNGGU_VALIDASI: 'Menunggu Validasi',
    DIPROSES_FOTOKOPI: 'Diproses',
    SELESAI: 'Selesai',
    SUDAH_DIAMBIL: 'Diambil'
};

const STATUS_COLOR = {
    MENUNGGU_VALIDASI: 'yellow',
    DIPROSES_FOTOKOPI: 'blue',
    SELESAI: 'green',
    SUDAH_DIAMBIL: 'gray'
};

// ===============================================
// LOAD ORDERS
// ===============================================
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
            wa: o.mahasiswa_info.wa,
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

// ===============================================
// UPDATE STATUS
// ===============================================
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

// ===============================================
// RENDER ALL TABLE
// ===============================================
function renderAll() {
    const tbodyAntrian = document.querySelector('.antrian-table tbody');
    const tbodySemua = document.querySelector('.semua-table tbody');

    const antrian = allOrders.filter(o => o.status === 'DIPROSES_FOTOKOPI');

    renderTable(tbodyAntrian, antrian, true);
    renderTable(tbodySemua, allOrders, false);
}

function renderTable(tbody, data, isAntrian) {
    tbody.innerHTML = '';

    if (data.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" style="text-align:center;color:#888;padding:30px">
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
                        <option value="MENUNGGU_VALIDASI" ${order.status==='MENUNGGU_VALIDASI'?'selected':''}>Menunggu</option>
                        <option value="DIPROSES_FOTOKOPI" ${order.status==='DIPROSES_FOTOKOPI'?'selected':''}>Diproses</option>
                        <option value="SELESAI" ${order.status==='SELESAI'?'selected':''}>Selesai</option>
                        <option value="SUDAH_DIAMBIL" ${order.status==='SUDAH_DIAMBIL'?'selected':''}>Diambil</option>
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

// ===============================================
// DETAIL POPUP
// ===============================================
function bukaDetail(orderId) {
    const o = allOrders.find(x => x.id === orderId);
    if (!o) return;

    alert(
        `Nama : ${o.nama}\n` +
        `NIM  : ${o.nim}\n` +
        `Prodi: ${o.prodi}\n` +
        `WA   : ${o.wa}\n` +
        `Catatan: ${o.catatan || '-'}`
    );
}

// ===============================================
// SUMMARY
// ===============================================
function updateSummary() {
    document.querySelectorAll('.summary-item strong')[0].textContent = allOrders.length;
    document.querySelectorAll('.summary-item strong')[1].textContent =
        allOrders.filter(o => o.status === 'DIPROSES_FOTOKOPI').length;
    document.querySelectorAll('.summary-item strong')[2].textContent =
        allOrders.filter(o => o.status === 'SELESAI').length;
}

// ===============================================
// INIT
// ===============================================
document.addEventListener('DOMContentLoaded', loadOrders);
