<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = "localhost";
$db   = "hardcover_app";
$user = "root";
$pass = "";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'DB Error']);
    exit;
}
?>