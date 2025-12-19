<?php
include 'config.php';
$data = json_decode(file_get_contents('php://input'), true);
$mahasiswa_id = $data['mahasiswa_id'];
$pesan = $data['pesan'];
$stmt = $pdo->prepare("INSERT INTO notifikasi (mahasiswa_id, pesan) VALUES (?, ?)");
$stmt->execute([$mahasiswa_id, $pesan]);
echo json_encode(['success' => true]);
?>