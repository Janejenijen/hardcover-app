<?php
/**
 * EXPORT EXCEL (CSV FORMAT)
 * Export order data untuk Yayasan dashboard
 * Support filter: all atau SELESAI only
 */

session_start();
require 'config.php';

// Auth check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'yayasan') {
    http_response_code(403);
    die('Unauthorized');
}

// Get filter parameter
$statusFilter = isset($_GET['status']) ? $_GET['status'] : 'all';

try {
    // Build query based on filter
    if ($statusFilter === 'SELESAI') {
        $sql = "
            SELECT 
                m.nim,
                m.nama,
                m.prodi,
                m.jenis_laporan,
                d.jumlah_halaman
            FROM orders o
            INNER JOIN mahasiswa m ON o.mahasiswa_id = m.id
            LEFT JOIN dokumen d ON m.id = d.mahasiswa_id
            WHERE o.status = 'SELESAI' OR o.status = 'SUDAH_DIAMBIL'
            ORDER BY o.id DESC
        ";
    } else {
        // All data
        $sql = "
            SELECT 
                m.nim,
                m.nama,
                m.prodi,
                m.jenis_laporan,
                d.jumlah_halaman
            FROM orders o
            INNER JOIN mahasiswa m ON o.mahasiswa_id = m.id
            LEFT JOIN dokumen d ON m.id = d.mahasiswa_id
            ORDER BY o.id DESC
        ";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Generate filename with timestamp
    $filename = 'Laporan_Hardcover_' . date('Y-m-d_His') . '.csv';

    // Set headers for CSV download
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');

    // Open output stream
    $output = fopen('php://output', 'w');

    // Add UTF-8 BOM for Excel compatibility
    fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

    // CSV Header
    fputcsv($output, ['NIM', 'Nama', 'Prodi', 'Jenis Laporan', 'Jumlah Halaman'], ';');

    // CSV Rows
    foreach ($data as $row) {
        fputcsv($output, [
            $row['nim'],
            $row['nama'],
            $row['prodi'],
            $row['jenis_laporan'] ?: 'KP',
            $row['jumlah_halaman'] ?: '0'
        ], ';');
    }

    fclose($output);
    exit;

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
