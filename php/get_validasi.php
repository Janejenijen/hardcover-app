<?php
/**
 * GET VALIDASI - Untuk dashboard fakultas dan keuangan
 * Fakultas: filter by fakultas_id dari session user
 * Keuangan: lihat semua
 */
include 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$role = $_SESSION['role'];
if (!in_array($role, ['keuangan', 'fakultas'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Access denied']);
    exit;
}

$search = $_GET['search'] ?? '';

try {
    // Base query
    $sql = "
        SELECT 
            v.id AS id,
            m.id AS mahasiswa_id,
            m.nim,
            m.nama,
            m.prodi,
            m.jenis_laporan,
            m.fakultas_id,
            f.nama AS fakultas_nama,
            v.valid_keuangan,
            v.valid_fakultas
        FROM validasi v
        JOIN mahasiswa m ON m.id = v.mahasiswa_id
        LEFT JOIN fakultas f ON f.id = m.fakultas_id
        WHERE 1=1
    ";

    $params = [];

    // Filter by search
    if (!empty($search)) {
        $sql .= " AND (m.nim LIKE ? OR m.nama LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }

    // Filter by fakultas jika role = fakultas
    if ($role === 'fakultas') {
        $fakultas_id = $_SESSION['fakultas_id'] ?? null;
        if ($fakultas_id) {
            $sql .= " AND m.fakultas_id = ?";
            $params[] = $fakultas_id;
        }
    }

    // Filter by jenis laporan
    $jenis = $_GET['jenis'] ?? '';
    if (!empty($jenis)) {
        $sql .= " AND m.jenis_laporan = ?";
        $params[] = $jenis;
    }

    $sql .= " ORDER BY m.nama ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

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