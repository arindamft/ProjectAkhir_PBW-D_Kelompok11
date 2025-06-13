<?php
session_start();
unset($_SESSION['admin']);
header("Location: login_admin.php");
exit();
?>