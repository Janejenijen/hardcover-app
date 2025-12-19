<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die(json_encode(['error' => 'Invalid request']));
}

$mahasiswa_id = $_POST['mahasiswa_id'] ?? null;

// Cek validasi
$stmt = $pdo->prepare("
  SELECT * FROM validasi 
  WHERE mahasiswa_id = ? 
  AND valid_fakultas = TRUE 
  AND valid_keuangan = TRUE
");
$stmt->execute([$mahasiswa_id]);

if (!$stmt->fetch()) {
    die(json_encode(['error' => 'Belum tervalidasi']));
}

// Upload PDF
if (
    isset($_FILES['file']) &&
    $_FILES['file']['error'] === 0 &&
    $_FILES['file']['size'] <= 10485760 &&
    mime_content_type($_FILES['file']['tmp_name']) === 'application/pdf'
) {
    $file_path = '../uploads/' . $mahasiswa_id . '_' . time() . '.pdf';
    move_uploaded_file($_FILES['file']['tmp_name'], $file_path);

    $pdo->prepare("
      INSERT INTO orders (mahasiswa_id, file_pdf, status, created_at)
      VALUES (?, ?, 'menunggu', NOW())
    ")->execute([$mahasiswa_id, $file_path]);

    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['error' => 'File tidak valid']);
exit;
?>