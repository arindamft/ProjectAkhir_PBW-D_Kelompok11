<?php
session_start();
unset($_SESSION['pengunjung_id']);
header("Location: login_pengunjung.php");
exit();
?>