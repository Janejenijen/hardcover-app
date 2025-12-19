<?php
include 'config.php';

if (!isset($_SESSION['role'])) {
    die(json_encode(['error' => 'Unauthorized']));
}

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];
$role = $_SESSION['role'];

$allowed = [
    'fakultas' => 'valid_fakultas',
    'keuangan' => 'valid_keuangan'
];

if (!isset($allowed[$role])) {
    die(json_encode(['error' => 'Unauthorized']));
}

$field = $allowed[$role];

$pdo->prepare("
  UPDATE validasi SET $field = TRUE WHERE id = ?
")->execute([$id]);

// Cek lengkap
$stmt = $pdo->prepare("
  SELECT mahasiswa_id 
  FROM validasi 
  WHERE id = ? 
  AND valid_fakultas = TRUE 
  AND valid_keuangan = TRUE
");
$stmt->execute([$id]);

if ($row = $stmt->fetch()) {
    $pdo->prepare("
      INSERT INTO notifikasi (mahasiswa_id, pesan)
      VALUES (?, 'Validasi lengkap. Silakan melakukan pemesanan hardcover.')
    ")->execute([$row['mahasiswa_id']]);
}

echo json_encode(['success' => true]);
exit;
?>