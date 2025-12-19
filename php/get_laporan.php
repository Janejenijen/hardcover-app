<?php
include 'config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'yayasan') {
    die(json_encode(['error' => 'Unauthorized']));
}

$start = $_GET['start'] ?? null;
$end   = $_GET['end'] ?? null;

if (!$start || !$end) {
    die(json_encode([]));
}

$stmt = $pdo->prepare("
    SELECT 
        DATE(created_at) AS tanggal,
        COUNT(*) AS total,
        SUM(status = 'selesai') AS selesai,
        SUM(status = 'diproses') AS diproses,
        SUM(status = 'menunggu') AS menunggu
    FROM orders
    WHERE DATE(created_at) BETWEEN ? AND ?
    GROUP BY DATE(created_at)
    ORDER BY tanggal ASC
");

$stmt->execute([$start, $end]);
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
exit;
