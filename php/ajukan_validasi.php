<?php
include 'config.php';
$data = json_decode(file_get_contents('php://input'), true);
$mahasiswa_id = $data['mahasiswa_id'];
// Check if already exists
$stmt = $pdo->prepare("SELECT * FROM validasi WHERE mahasiswa_id = ?");
$stmt->execute([$mahasiswa_id]);
if (!$stmt->fetch()) {
    $stmt = $pdo->prepare("INSERT INTO validasi (mahasiswa_id) VALUES (?)");
    $stmt->execute([$mahasiswa_id]);
    // Kirim notif
    $notif_stmt = $pdo->prepare("INSERT INTO notifikasi (mahasiswa_id, pesan) VALUES (?, 'Pengajuan validasi dikirim.')");
    $notif_stmt->execute([$mahasiswa_id]);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Sudah diajukan']);
}
?>