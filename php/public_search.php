<?php
/**
 * PUBLIC SEARCH - Endpoint untuk mahasiswa cek status validasi
 * Tidak butuh login
 */
include 'config.php';

header('Content-Type: application/json');

$search = $_GET['search'] ?? '';

if (empty($search)) {
    echo json_encode([]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT 
            m.id,
            m.nim,
            m.nama,
            m.prodi,
            COALESCE(v.valid_fakultas, 0) as valid_fakultas,
            COALESCE(v.valid_keuangan, 0) as valid_keuangan
        FROM mahasiswa m
        LEFT JOIN validasi v ON v.mahasiswa_id = m.id
        WHERE m.nim = ? OR m.nama LIKE ?
        ORDER BY m.id DESC
        LIMIT 10
    ");

    $stmt->execute([
        $search,
        "%$search%"
    ]);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Cast validation fields to integers (PDO returns strings)
    foreach ($results as &$row) {
        $row['valid_fakultas'] = (int) $row['valid_fakultas'];
        $row['valid_keuangan'] = (int) $row['valid_keuangan'];
    }

    echo json_encode($results);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
}
?>