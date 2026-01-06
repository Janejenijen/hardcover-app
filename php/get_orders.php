<?php
require 'config.php';
require 'auth_check.php';

header('Content-Type: application/json');
ini_set('display_errors', 0);
error_reporting(0);

try {

    $sql = "
        SELECT 
            o.id,
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
        LEFT JOIN dokumen d 
            ON d.mahasiswa_id = m.id
            AND d.uploaded_at = (
                SELECT MAX(uploaded_at)
                FROM dokumen
                WHERE mahasiswa_id = m.id
            )
        ORDER BY o.tanggal_order DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $orders = [];
    $no = 1;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $orders[] = [
            'id' => (int)$row['id'],
            'no' => $no++,
            'mahasiswa_info' => [
                'nama' => $row['nama'],
                'nim' => $row['nim'],
                'prodi' => $row['prodi'],
                'no_wa' => $row['no_wa'],
                'catatan' => $row['catatan']
            ],
            // KIRIM PATH APA ADANYA
            'file_pdf' => $row['file_path'],
            'status' => $row['status'],
            'created_at' => $row['tanggal_order']
        ];
    }

    echo json_encode($orders);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Server error'
    ]);
}
?>