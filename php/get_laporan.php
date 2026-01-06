<?php
include 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'yayasan') {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$start = $_GET['start'] ?? null;
$end = $_GET['end'] ?? null;

if (!$start || !$end) {
    echo json_encode([]);
    exit;
}

$stmt = $pdo->prepare("
    SELECT 
        DATE(tanggal_order) AS tanggal,
        COUNT(*) AS total,
        SUM(status IN ('SELESAI', 'SUDAH_DIAMBIL')) AS selesai,
        SUM(status = 'DIPROSES_FOTOKOPI') AS diproses,
        SUM(status = 'MENUNGGU_PROSES') AS menunggu
    FROM orders
    WHERE DATE(tanggal_order) BETWEEN ? AND ?
    GROUP BY DATE(tanggal_order)
    ORDER BY tanggal ASC
");

$stmt->execute([$start, $end]);
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
?>