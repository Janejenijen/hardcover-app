<?php
require 'php/auth_check.php';
if ($_SESSION['role'] !== 'fakultas') {
    die('Akses ditolak');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Validasi Fakultas</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<h2>Validasi Fakultas</h2>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>NIM</th>
            <th>Nama</th>
            <th>Status Fakultas</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody id="dataValidasi"></tbody>
</table>

<script src="js/jquery.min.js"></script>
<script src="js/fakultas.js"></script>
</body>
</html>