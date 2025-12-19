<?php
require 'php/auth_check.php';
if ($_SESSION['role'] !== 'yayasan') {
    die('Akses ditolak');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Yayasan</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<h2>Laporan Pemesanan Hardcover</h2>

<label>Dari tanggal:</label>
<input type="date" id="start">
<label>Sampai:</label>
<input type="date" id="end">
<button onclick="loadLaporan()">Tampilkan</button>

<br><br>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Total Order</th>
            <th>Selesai</th>
            <th>Diproses</th>
            <th>Menunggu</th>
        </tr>
    </thead>
    <tbody id="laporanData"></tbody>
</table>

<script src="js/jquery.min.js"></script>
<script src="js/yayasan.js"></script>

</body>
</html>
