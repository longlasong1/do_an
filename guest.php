<?php
session_start();
$_SESSION['username'] = "Guest"; // Gán tài khoản là Guest
$_SESSION['guest'] = true; // Đánh dấu là tài khoản khách
header("Location: index.php"); // Chuyển hướng sang trang chủ
exit();
?>
