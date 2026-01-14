<?php
/**
 * PUBLIC ORDER SEARCH - Cek status validasi & pesanan berdasarkan NIM
 * Tidak butuh login
 */
include 'config.php';

header('Content-Type: application/json');

$search = $_GET['search'] ?? '';

if (empty($search)) {
    echo json_encode(['found' => false]);
    exit;
}

try {
    // Cari mahasiswa berdasarkan NIM
    $stmtMhs = $pdo->prepare("
        SELECT m.id, m.nim, m.nama, m.prodi, m.jenis_laporan,
               v.valid_fakultas, v.valid_keuangan
        FROM mahasiswa m
        LEFT JOIN validasi v ON v.mahasiswa_id = m.id
        WHERE m.nim = ?
    ");
    $stmtMhs->execute([$search]);
    $mahasiswa = $stmtMhs->fetch(PDO::FETCH_ASSOC);

    if (!$mahasiswa) {
        echo json_encode(['found' => false, 'message' => 'NIM tidak terdaftar']);
        exit;
    }

    // Cek apakah sudah ada order
    $stmtOrder = $pdo->prepare("
        SELECT 
            o.id,
            o.status,
            o.tanggal_order,
            d.judul,
            d.catatan,
            d.file_path
        FROM orders o
        LEFT JOIN dokumen d ON d.mahasiswa_id = o.mahasiswa_id
        WHERE o.mahasiswa_id = ?
        ORDER BY o.tanggal_order DESC
        LIMIT 1
    ");
    $stmtOrder->execute([$mahasiswa['id']]);
    $order = $stmtOrder->fetch(PDO::FETCH_ASSOC);

    $response = [
        'found' => true,
        'nim' => $mahasiswa['nim'],
        'nama' => $mahasiswa['nama'],
        'prodi' => $mahasiswa['prodi'],
        'jenis_laporan' => $mahasiswa['jenis_laporan']
    ];

    // Status validasi
    $validFakultas = (bool) $mahasiswa['valid_fakultas'];
    $validKeuangan = (bool) $mahasiswa['valid_keuangan'];
    $validasiLengkap = $validFakultas && $validKeuangan;

    $response['validasi'] = [
        'fakultas' => $validFakultas,
        'keuangan' => $validKeuangan,
        'lengkap' => $validasiLengkap,
        'status_text' => $validasiLengkap ? 'Validasi Lengkap' : 'Menunggu Validasi'
    ];

    // Status pesanan (jika ada)
    if ($order) {
        $statusMap = [
            'MENUNGGU_PROSES' => ['text' => 'Menunggu Diproses', 'icon' => '⏳', 'color' => 'orange'],
            'MENUNGGU_VALIDASI' => ['text' => 'Menunggu Diproses', 'icon' => '⏳', 'color' => 'orange'],
            'DIPROSES_FOTOKOPI' => ['text' => 'Sedang Diproses', 'icon' => '🔄', 'color' => 'blue'],
            'SELESAI' => ['text' => 'Selesai - Siap Diambil', 'icon' => '✅', 'color' => 'green'],
            'SUDAH_DIAMBIL' => ['text' => 'Sudah Diambil', 'icon' => '📦', 'color' => 'gray']
        ];

        $statusInfo = $statusMap[$order['status']] ?? ['text' => $order['status'], 'icon' => '❓', 'color' => 'gray'];

        $response['has_order'] = true;
        $response['order'] = [
            'id' => $order['id'],
            'status' => $order['status'],
            'status_text' => $statusInfo['text'],
            'status_icon' => $statusInfo['icon'],
            'status_color' => $statusInfo['color'],
            'tanggal_order' => $order['tanggal_order'],
            'judul' => $order['judul'],
            'catatan' => $order['catatan']
        ];
    } else {
        $response['has_order'] = false;
        $response['message'] = $validasiLengkap
            ? 'Validasi lengkap. Silakan buat pesanan hardcover.'
            : 'Menunggu validasi dari Fakultas dan Keuangan.';
    }

    echo json_encode($response);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
?>