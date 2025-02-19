<?php
session_start();
include 'includes/db.php'; // Kết nối cơ sở dữ liệu

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Truy vấn kiểm tra tài khoản
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
    $stmt->execute(['username' => $username, 'password' => $password]);
    $user = $stmt->fetch();

    if ($user) {
        // Đăng nhập thành công -> Lưu session
        $_SESSION['username'] = $username;
        header("Location: index.php"); // Chuyển hướng sang trang chính
        exit();
    } else {
        echo "<script>alert('Tên đăng nhập hoặc mật khẩu không đúng!'); window.location.href='login.html';</script>";
    }
}
?>
