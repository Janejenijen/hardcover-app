<?php
/**
 * REGISTER MAHASISWA - Self Registration (Hybrid)
 * Mahasiswa daftar sendiri, data auto-assign fakultas berdasarkan prodi
 */
include 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Ambil data dari form
$nama = trim($_POST['nama'] ?? '');
$nim = trim($_POST['nim'] ?? '');
$prodi = trim($_POST['prodi'] ?? '');
$no_wa = trim($_POST['no_wa'] ?? '');

// Validasi
if (empty($nama) || empty($nim) || empty($prodi) || empty($no_wa)) {
    echo json_encode(['error' => 'Semua field wajib diisi']);
    exit;
}

if (!preg_match('/^[a-zA-Z\s]+$/', $nama)) {
    echo json_encode(['error' => 'Nama hanya boleh huruf dan spasi']);
    exit;
}

if (!preg_match('/^\d{8}$/', $nim)) {
    echo json_encode(['error' => 'NIM harus 8 digit angka']);
    exit;
}

if (!preg_match('/^\d{10,13}$/', $no_wa)) {
    echo json_encode(['error' => 'No WA harus 10-13 digit angka']);
    exit;
}

try {
    // Cek apakah NIM sudah terdaftar
    $checkStmt = $pdo->prepare("SELECT id FROM mahasiswa WHERE nim = ?");
    $checkStmt->execute([$nim]);

    if ($checkStmt->fetch()) {
        echo json_encode(['error' => 'NIM sudah terdaftar. Gunakan fitur pencarian untuk cek status.']);
        exit;
    }

    // Ambil fakultas_id dari prodi
    $prodiStmt = $pdo->prepare("SELECT fakultas_id FROM prodi WHERE nama = ?");
    $prodiStmt->execute([$prodi]);
    $prodiData = $prodiStmt->fetch(PDO::FETCH_ASSOC);

    if (!$prodiData) {
        echo json_encode(['error' => 'Program studi tidak valid']);
        exit;
    }

    $fakultas_id = $prodiData['fakultas_id'];

    // Insert mahasiswa baru
    $jenis_laporan = trim($_POST['jenis_laporan'] ?? 'KP');
    $stmt = $pdo->prepare("
        INSERT INTO mahasiswa (nim, nama, prodi, no_wa, jenis_laporan, fakultas_id, status_registrasi) 
        VALUES (?, ?, ?, ?, ?, ?, 'PENDING')
    ");
    $stmt->execute([$nim, $nama, $prodi, $no_wa, $jenis_laporan, $fakultas_id]);

    $mahasiswa_id = $pdo->lastInsertId();

    if (!$mahasiswa_id) {
        echo json_encode(['error' => 'Gagal mendapatkan ID mahasiswa']);
        exit;
    }

    // Insert record validasi (kosong - belum divalidasi)
    $validStmt = $pdo->prepare("
        INSERT INTO validasi (mahasiswa_id, valid_fakultas, valid_keuangan) 
        VALUES (?, 0, 0)
    ");
    $validResult = $validStmt->execute([$mahasiswa_id]);

    if (!$validResult) {
        echo json_encode(['error' => 'Gagal membuat record validasi']);
        exit;
    }

    // Kirim notifikasi
    $notifStmt = $pdo->prepare("
        INSERT INTO notifikasi (mahasiswa_id, pesan) 
        VALUES (?, 'Pendaftaran berhasil! Data Anda sedang menunggu verifikasi dari Fakultas dan Keuangan.')
    ");
    $notifStmt->execute([$mahasiswa_id]);

    echo json_encode([
        'success' => true,
        'message' => 'Pendaftaran berhasil! Silakan tunggu verifikasi.',
        'mahasiswa_id' => $mahasiswa_id
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Gagal mendaftar: ' . $e->getMessage()]);
}
?>