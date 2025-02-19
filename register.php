<?php
session_start();
include 'includes/db.php'; // Kết nối đến MySQL

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Kiểm tra mật khẩu có khớp không
    if ($password !== $confirm_password) {
        echo "<script>alert('Mật khẩu xác nhận không khớp!'); window.location.href='register.html';</script>";
        exit();
    }

    // Kiểm tra xem username hoặc email đã tồn tại chưa
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
    $stmt->execute(['username' => $username, 'email' => $email]);
    
    if ($stmt->fetch()) {
        echo "<script>alert('Tên đăng nhập hoặc email đã tồn tại!'); window.location.href='register.html';</script>";
        exit();
    }

    // Thêm tài khoản vào database
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
    $stmt->execute([
        'username' => $username,
        'email' => $email,
        'password' => $password // Nếu muốn bảo mật, hãy sử dụng password_hash($password, PASSWORD_DEFAULT)
    ]);

    echo "<script>alert('Đăng ký thành công!'); window.location.href='login.html';</script>";
    exit();
}
?>
