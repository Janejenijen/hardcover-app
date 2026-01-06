<?php
include 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';

    if (empty($username) || empty($pass)) {
        echo json_encode(['error' => 'Username dan password wajib diisi']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($pass, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['fakultas_id'] = $user['fakultas_id'] ?? null;
            $_SESSION['username'] = $user['username'];

            $redirect = match ($user['role']) {
                'keuangan' => 'finance.php',
                'fakultas' => 'fakultas.php',
                'fotokopi' => 'dashboard.php',
                'yayasan' => 'yayasan.php',
                default => 'index.php'
            };

            echo json_encode(['success' => true, 'redirect' => $redirect]);
        } else {
            echo json_encode(['error' => 'Username atau password salah']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Terjadi kesalahan sistem']);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>