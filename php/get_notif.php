<?php
include 'config.php';
$mahasiswa_id = $_GET['mahasiswa_id'];
$stmt = $pdo->prepare("SELECT * FROM notifikasi WHERE mahasiswa_id = ? ORDER BY created_at DESC");
$stmt->execute([$mahasiswa_id]);
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
?>