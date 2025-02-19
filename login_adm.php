<?php
session_start();
include 'db.php'; // Kết nối database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $adm_name = $_POST['adm_name'];
    $adm_pass = $_POST['adm_pass'];

    // Truy vấn kiểm tra tài khoản
    $sql = "SELECT * FROM admin WHERE adm_name = :adm_name AND adm_pass = :adm_pass";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":adm_name", $adm_name);
    $stmt->bindParam(":adm_pass", $adm_pass);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['admin'] = $adm_name; // Lưu session
        header("Location: index_adm.php"); // Chuyển hướng
        exit();
    } else {
        $error = "Sai tài khoản hoặc mật khẩu!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Đăng nhập Admin</h2>
    <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Tên đăng nhập</label>
            <input type="text" class="form-control" name="adm_name" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Mật khẩu</label>
            <input type="password" class="form-control" name="adm_pass" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
    </form>
</div>

</body>
</html>
