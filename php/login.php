<?php
error_reporting(E_ALL); // Debug: Tampil semua error
ini_set('display_errors', 1); // Tampil error di output
include 'config.php';

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
        if ($user) {
            if (password_verify($pass, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $redirect = '';
                switch ($user['role']) {
                    case 'keuangan': $redirect = './finance.php'; break;
                    case 'fakultas': $redirect = './fakultas.php'; break;
                    case 'fotokopi': $redirect = './dashboard.php'; break;
                    case 'yayasan': $redirect = './yayasan.php'; break;
                    default: $redirect = './index.php';
                }
                echo json_encode(['success' => true, 'redirect' => $redirect, 'debug_session' => $_SESSION, 'debug_user' => $user['role']]);
            } else {
                echo json_encode(['error' => 'Password salah', 'debug_hash' => $user['password']]); // Debug: Lihat hash
            }
        } else {
            echo json_encode(['error' => 'Username tidak ditemukan']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid request method - Expect POST']);
}
?>