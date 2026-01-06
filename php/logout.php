<?php
session_start();
session_unset();
session_destroy();

// Hapus cache browser
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");

header("Location: ../login.html");
exit;
?>