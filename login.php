<?php

session_start();

include 'db.php'; // Kết nối cơ sở dữ liệu

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Truy vấn kiểm tra tài khoả
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
    $stmt->execute(['username' => $username, 'password' => $password]);
    $user = $stmt->fetch();

    if ($user) {
        // Đăng nhập thành công -> Lưu session
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $user['id']; // Lưu ID người dùng vào session
        header("Location: index.php"); // Chuyển hướng sang trang chính
        exit();
    
    } else {
        echo "<script>alert('Tên đăng nhập hoặc mật khẩu không đúng!'); window.location.href='login.html';</script>";
    }   
}
?>
    <?php // Nếu đã đăng nhập, chuyển hướng đến trang chủ
    if (isset($_SESSION['username'])) {
         header("Location: index.php");
         exit(); // Dừng script sau khi chuyển hướng
}?>
<!DOCTYPE html>
<html lang="vi">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - Website Nghe Nhạc</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: url('bg/bg.jpg') no-repeat center center/cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background: rgba(183, 39, 186, 0.87);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            width: 350px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #fff;
        }

        .input-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .input-group label {
            display: block;
            color: #fff;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
        }

        .btn {
            width: 100%;
            background: #4CAF50;
            color: white;
            border: none;
            padding: 12px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #45a049;
        }

        .register-link {
            margin-top: 10px;
            font-size: 14px;
            color: #fff;
        }

        .register-link a {
            color: #ffcc00;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Đăng Nhập</h2>
        <form action="login.php" method="post">
            <div class="input-group">
                <label for="username">Tên đăng nhập:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Mật khẩu:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">Đăng Nhập</button>
        </form>

       
        <p class="register-link">Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>
        
                
    </div>
</body>
</html>
