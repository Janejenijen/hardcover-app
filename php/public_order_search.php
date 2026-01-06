<?php
/**
 * PUBLIC ORDER SEARCH - Cek status pesanan berdasarkan nomor antrian
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
    // Cari order berdasarkan ID (nomor antrian)
    $stmt = $pdo->prepare("
        SELECT 
            o.id,
            o.status,
            o.tanggal_order,
            m.nim,
            m.nama,
            m.prodi,
            m.jenis_laporan,
            d.judul,
            d.catatan,
            d.file_path
        FROM orders o
        JOIN mahasiswa m ON m.id = o.mahasiswa_id
        LEFT JOIN dokumen d ON d.mahasiswa_id = o.mahasiswa_id
        WHERE o.id = ?
        ORDER BY d.uploaded_at DESC
        LIMIT 1
    ");

    $stmt->execute([$search]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($order) {
        // Map status ke text yang user-friendly (includes old status for backward compatibility)
        $statusMap = [
            'MENUNGGU_PROSES' => ['text' => 'Menunggu Diproses', 'icon' => '⏳', 'color' => 'orange'],
            'MENUNGGU_VALIDASI' => ['text' => 'Menunggu Diproses', 'icon' => '⏳', 'color' => 'orange'], // Old status, same display
            'DIPROSES_FOTOKOPI' => ['text' => 'Sedang Diproses', 'icon' => '🔄', 'color' => 'blue'],
            'SELESAI' => ['text' => 'Selesai - Siap Diambil', 'icon' => '✅', 'color' => 'green'],
            'SUDAH_DIAMBIL' => ['text' => 'Sudah Diambil', 'icon' => '📦', 'color' => 'gray']
        ];

        $statusInfo = $statusMap[$order['status']] ?? ['text' => $order['status'], 'icon' => '❓', 'color' => 'gray'];

        echo json_encode([
            'found' => true,
            'type' => 'order',
            'order' => [
                'id' => $order['id'],
                'status' => $order['status'],
                'status_text' => $statusInfo['text'],
                'status_icon' => $statusInfo['icon'],
                'status_color' => $statusInfo['color'],
                'tanggal_order' => $order['tanggal_order'],
                'jenis_laporan' => $order['jenis_laporan'],
                'judul' => $order['judul'],
                'catatan' => $order['catatan'],
                'nim' => $order['nim'],
                'nama' => $order['nama'],
                'prodi' => $order['prodi']
            ]
        ]);
    } else {
        echo json_encode(['found' => false]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
?>