<?php
/**
 * PUBLIC QUEUE STATS - Get queue statistics for public page
 * No authentication required
 */
include 'config.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->prepare("
        SELECT status, COUNT(*) as count
        FROM orders
        GROUP BY status
    ");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Initialize counters
    $stats = [
        'total' => 0,
        'diproses' => 0,
        'selesai' => 0
    ];

    // Process results
    foreach ($results as $row) {
        $stats['total'] += $row['count'];

        if ($row['status'] === 'DIPROSES_FOTOKOPI') {
            $stats['diproses'] = $row['count'];
        }

        if ($row['status'] === 'SELESAI' || $row['status'] === 'SUDAH_DIAMBIL') {
            $stats['selesai'] += $row['count'];
        }
    }

    echo json_encode($stats);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
}
?>