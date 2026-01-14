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
            o.tanggal_selesai,
            o.semester,
            o.tahun_ajaran,
            m.nama,
            m.nim,
            m.prodi,
            m.jenis_laporan,
            m.no_wa,
            d.file_path,
            d.catatan,
            d.jumlah_halaman
        FROM orders o
        JOIN mahasiswa m ON o.mahasiswa_id = m.id
        LEFT JOIN dokumen d 
            ON d.mahasiswa_id = m.id
            AND d.uploaded_at = (
                SELECT MAX(uploaded_at)
                FROM dokumen
                WHERE mahasiswa_id = m.id
            )
        ORDER BY o.id DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $orders = [];
    $no = 1;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $orders[] = [
            'id' => (int) $row['id'],
            'no' => $no++,
            'mahasiswa_info' => [
                'nama' => $row['nama'],
                'nim' => $row['nim'],
                'prodi' => $row['prodi'],
                'jenis_laporan' => $row['jenis_laporan'],
                'no_wa' => $row['no_wa'],
                'catatan' => $row['catatan'],
                'jumlah_halaman' => $row['jumlah_halaman']
            ],
            // KIRIM PATH APA ADANYA
            'file_pdf' => $row['file_path'],
            'status' => $row['status'],
            'created_at' => $row['tanggal_order'],
            'finished_at' => $row['tanggal_selesai'],
            'semester' => $row['semester'],
            'tahun_ajaran' => $row['tahun_ajaran']
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