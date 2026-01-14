<?php
require 'config.php';
require 'auth_check.php';

header('Content-Type: application/json');

if ($_SESSION['role'] !== 'fotokopi') {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$order_id = $data['order_id'] ?? null;
$status = $data['status'] ?? null;

if (!$order_id || !$status) {
    http_response_code(400);
    echo json_encode(['error' => 'Data tidak lengkap']);
    exit;
}

try {
    // Jika status berubah ke SELESAI, set tanggal_selesai
    if ($status === 'SELESAI') {
        $stmt = $pdo->prepare("UPDATE orders SET status = ?, tanggal_selesai = NOW() WHERE id = ?");
    } else {
        $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    }
    $stmt->execute([$status, $order_id]);

    echo json_encode(['success' => true]);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Gagal update',
        'message' => $e->getMessage()
    ]);
}
