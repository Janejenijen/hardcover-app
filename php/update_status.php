<?php
require 'config.php';
require 'auth_check.php';

// Pastikan hanya fotokopi yang boleh
if ($_SESSION['role'] !== 'fotokopi') {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Ambil JSON body
$data = json_decode(file_get_contents('php://input'), true);

$order_id = $data['id'] ?? null;
$status   = $data['status'] ?? null;

if (!$order_id || !$status) {
    echo json_encode(['error' => 'Data tidak lengkap']);
    exit;
}

// Status yang diizinkan
$allowed_status = ['Menunggu Pembayaran', 'Diproses', 'Selesai', 'Diambil'];

if (!in_array($status, $allowed_status)) {
    echo json_encode(['error' => 'Status tidak valid']);
    exit;
}

// Update status
$stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
$stmt->execute([$status, $order_id]);

// Log status (nilai plus KP)
$log = $pdo->prepare("
    INSERT INTO order_status_log (order_id, status, changed_at)
    VALUES (?, ?, NOW())
");
$log->execute([$order_id, $status]);

echo json_encode(['success' => true]);
?>