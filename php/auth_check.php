<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Anti cache untuk SEMUA halaman protected
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");

// Belum login
if (!isset($_SESSION['user_id'])) {

    // Request dari fetch / AJAX
    if (
        isset($_SERVER['HTTP_ACCEPT']) &&
        str_contains($_SERVER['HTTP_ACCEPT'], 'application/json')
    ) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Akses halaman langsung
    header("Location: login.html");
    exit;
}
?>