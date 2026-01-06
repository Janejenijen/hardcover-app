<?php
/**
 * PUBLIC QUEUE - Endpoint untuk tampilkan antrian di landing page
 * Tidak butuh login, hanya return counts
 */
include 'config.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->query("
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN status = 'DIPROSES_FOTOKOPI' THEN 1 ELSE 0 END) as diproses,
            SUM(CASE WHEN status IN ('SELESAI', 'SUDAH_DIAMBIL') THEN 1 ELSE 0 END) as selesai
        FROM orders
    ");

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'total' => (int) $row['total'],
        'diproses' => (int) $row['diproses'],
        'selesai' => (int) $row['selesai']
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
}
?>