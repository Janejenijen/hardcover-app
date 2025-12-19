<?php
include 'config.php';

if (!isset($_SESSION['role'])) {
    die(json_encode(['error' => 'Unauthorized']));
}

$role = $_SESSION['role'];
if (!in_array($role, ['keuangan', 'fakultas'])) {
    die(json_encode(['error' => 'Access denied']));
}

$search = $_GET['search'] ?? '';

$stmt = $pdo->prepare("
    SELECT 
        v.id AS id,
        m.nim,
        m.nama,
        v.valid_keuangan,
        v.valid_fakultas
    FROM validasi v
    JOIN mahasiswa m ON m.id = v.mahasiswa_id
    WHERE m.nim LIKE ? OR m.nama LIKE ?
    ORDER BY m.nama ASC
");

$stmt->execute([
    "%$search%",
    "%$search%"
]);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
exit;
?>