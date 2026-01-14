<?php
/**
 * PLACE ORDER - Submit pesanan hardcover
 * Mahasiswa yang sudah divalidasi bisa submit order
 */
include 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['error' => 'Invalid request']);
  exit;
}


$mahasiswa_id = $_POST['mahasiswa_id'] ?? null;
$catatan = trim($_POST['catatan'] ?? '');
$nama_dokumen = trim($_POST['nama_dokumen'] ?? '');
$jumlah_halaman = (int) ($_POST['jumlah_halaman'] ?? 0);

if (!$mahasiswa_id) {
  echo json_encode(['error' => 'Mahasiswa ID required']);
  exit;
}

if (empty($nama_dokumen)) {
  echo json_encode(['error' => 'Judul dokumen wajib diisi']);
  exit;
}

if ($jumlah_halaman < 1) {
  echo json_encode(['error' => 'Jumlah halaman minimal 1']);
  exit;
}

// Cek validasi - hanya perlu fakultas dan keuangan
$stmt = $pdo->prepare("
    SELECT * FROM validasi 
    WHERE mahasiswa_id = ? 
    AND valid_fakultas = TRUE 
    AND valid_keuangan = TRUE
");
$stmt->execute([$mahasiswa_id]);

if (!$stmt->fetch()) {
  echo json_encode(['error' => 'Belum tervalidasi oleh Fakultas dan Keuangan']);
  exit;
}

// Cek apakah sudah ada order yang belum selesai (mencegah duplicate)
$checkOrder = $pdo->prepare("
    SELECT id FROM orders 
    WHERE mahasiswa_id = ? 
    AND status NOT IN ('SELESAI', 'SUDAH_DIAMBIL')
");
$checkOrder->execute([$mahasiswa_id]);

if ($checkOrder->fetch()) {
  echo json_encode(['error' => 'Anda masih memiliki pesanan yang sedang diproses. Tunggu pesanan selesai sebelum membuat pesanan baru.']);
  exit;
}

// Upload PDF
if (
  isset($_FILES['file']) &&
  $_FILES['file']['error'] === 0 &&
  $_FILES['file']['size'] <= 10485760 &&
  mime_content_type($_FILES['file']['tmp_name']) === 'application/pdf'
) {
  $file_name = $mahasiswa_id . '_' . time() . '.pdf';
  $file_path = '../uploads/' . $file_name;

  if (!move_uploaded_file($_FILES['file']['tmp_name'], $file_path)) {
    echo json_encode(['error' => 'Gagal upload file']);
    exit;
  }

  try {
    // AUTO-DETECT Semester dan Tahun Ajaran
    $currentMonth = (int) date('n'); // 1-12
    $currentYear = (int) date('Y');

    // Semester: Ganjil (Juli-Des), Genap (Jan-Jun)
    $semester = ($currentMonth >= 7) ? 'Ganjil' : 'Genap';

    // Tahun Ajaran: Juli tahun X - Juni tahun X+1
    if ($currentMonth >= 7) {
      // Juli - Desember: tahun ajaran X/X+1
      $tahunAjaran = $currentYear . '/' . ($currentYear + 1);
    } else {
      // Januari - Juni: tahun ajaran (X-1)/X
      $tahunAjaran = ($currentYear - 1) . '/' . $currentYear;
    }

    // INSERT ORDER
    $orderStmt = $pdo->prepare("
        INSERT INTO orders (mahasiswa_id, status, semester, tahun_ajaran, tanggal_order) 
        VALUES (?, 'MENUNGGU_PROSES', ?, ?, NOW())
    ");
    $orderStmt->execute([$mahasiswa_id, $semester, $tahunAjaran]);
    $order_id = $pdo->lastInsertId();

    // Insert ke tabel dokumen dengan judul dan catatan terpisah
    $docStmt = $pdo->prepare("
            INSERT INTO dokumen (mahasiswa_id, file_path, judul, jumlah_halaman, catatan, uploaded_at)
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
    $docStmt->execute([$mahasiswa_id, $file_name, $nama_dokumen, $jumlah_halaman, $catatan]);

    // Kirim notifikasi
    $notifStmt = $pdo->prepare("
            INSERT INTO notifikasi (mahasiswa_id, pesan)
            VALUES (?, ?)
        ");
    $notifStmt->execute([$mahasiswa_id, "Pesanan #$order_id berhasil disubmit! Silakan tunggu proses fotokopi."]);

    echo json_encode(['success' => true, 'order_id' => $order_id]);

  } catch (Exception $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
  }
  exit;
}

echo json_encode(['error' => 'File tidak valid (harus PDF, max 10MB)']);
exit;
?>