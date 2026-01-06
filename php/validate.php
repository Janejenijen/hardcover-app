<?php
include 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['role'])) {
  echo json_encode(['error' => 'Unauthorized']);
  exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$id = isset($data['id']) ? (int) $data['id'] : 0;
$role = $_SESSION['role'];

if (!$id || $id <= 0) {
  echo json_encode(['error' => 'Invalid ID']);
  exit;
}

$allowed = [
  'fakultas' => 'valid_fakultas',
  'keuangan' => 'valid_keuangan'
];

if (!isset($allowed[$role])) {
  echo json_encode(['error' => 'Unauthorized role']);
  exit;
}

$field = $allowed[$role];

// Update hanya record dengan ID spesifik
$updateStmt = $pdo->prepare("UPDATE validasi SET $field = 1 WHERE id = ?");
$updateStmt->execute([$id]);

// Cek apakah ada row yang terupdate
if ($updateStmt->rowCount() === 0) {
  echo json_encode(['error' => 'Data tidak ditemukan']);
  exit;
}

// Cek apakah validasi lengkap
$stmt = $pdo->prepare("
    SELECT mahasiswa_id 
    FROM validasi 
    WHERE id = ? 
    AND valid_fakultas = 1 
    AND valid_keuangan = 1
");
$stmt->execute([$id]);

if ($row = $stmt->fetch()) {
  // Kirim notifikasi jika validasi lengkap
  $notifStmt = $pdo->prepare("
        INSERT INTO notifikasi (mahasiswa_id, pesan)
        VALUES (?, 'Validasi lengkap. Silakan melakukan pemesanan hardcover.')
    ");
  $notifStmt->execute([$row['mahasiswa_id']]);
}

echo json_encode(['success' => true, 'message' => 'Validasi berhasil']);
exit;
?>