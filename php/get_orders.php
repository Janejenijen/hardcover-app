<?php
require 'config.php';
require 'auth_check.php';

/**
 * Hanya role fotokopi
 */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'fotokopi') {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$sql = "
SELECT 
    o.id AS order_id,
    o.status,
    o.tanggal_order,
    m.nama,
    m.nim,
    m.prodi,
    m.no_wa,
    d.file_path,
    d.catatan
FROM orders o
JOIN mahasiswa m ON o.mahasiswa_id = m.id
LEFT JOIN dokumen d ON d.mahasiswa_id = m.id
ORDER BY o.tanggal_order DESC
";

$stmt = $pdo->query($sql);

$orders = [];
$no = 1;

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $orders[] = [
        'id' => $row['order_id'],
        'no' => $no++,
        'status' => $row['status'],
        'created_at' => $row['tanggal_order'],
        'file_pdf' => $row['file_path'] ? basename($row['file_path']) : null,
        'mahasiswa_info' => [
            'nama' => $row['nama'],
            'nim' => $row['nim'],
            'prodi' => $row['prodi'],
            'wa' => $row['no_wa'],
            'catatan' => $row['catatan']
        ]
    ];
}

header('Content-Type: application/json');
echo json_encode($orders);
?>