<?php
require 'php/auth_check.php';
if ($_SESSION['role'] !== 'keuangan') {
    die('Akses ditolak');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Validasi Keuangan</title>
    <link rel="stylesheet" href="css/finance.css">
</head>
<body>

<h2>Validasi Keuangan Mahasiswa</h2>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>NIM</th>
            <th>Nama</th>
            <th>Status Keuangan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody id="dataValidasi"></tbody>
</table>

<script src="js/jquery.min.js"></script>
<script src="js/finance.js"></script>
</body>
</html>
