<?php
include 'db.php'; // Kết nối CSDL

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $adm_name = $_POST["adm_name"];
    $adm_pass = $_POST["adm_pass"];

    try {
        // Kiểm tra trùng tên
        $check = $conn->prepare("SELECT COUNT(*) FROM admin WHERE adm_name = :adm_name");
        $check->bindParam(':adm_name', $adm_name);
        $check->execute();
        if ($check->fetchColumn() > 0) {
            $message = "❌ Tên đăng nhập đã tồn tại!";
        } else {
            $stmt = $conn->prepare("INSERT INTO admin (adm_name, adm_pass) VALUES (:adm_name, :adm_pass)");
            $stmt->bindParam(':adm_name', $adm_name);
            $stmt->bindParam(':adm_pass', $adm_pass);
            $stmt->execute();
            $message = "✅ Thêm tài khoản admin thành công!";
        }
    } catch (PDOException $e) {
        $message = "❌ Lỗi: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Admin</title>
    <style>
        body {
            background: #f2f2f2;
            font-family: Arial, sans-serif;
        }
        .container {
            width: 400px;
            margin: 100px auto;
            background: #fff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 24px;
            color: #333;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 6px;
            color: #555;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 18px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }
        input[type="submit"] {
            background: #007BFF;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background: #0056b3;
        }
        .message {
            text-align: center;
            margin-top: 15px;
            font-weight: bold;
            color: #cc0000;
        }
        .message.success {
            color: #2e7d32;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Thêm tài khoản Admin</h2>
        <form method="POST" action="">
            <label for="adm_name">Tên đăng nhập:</label>
            <input type="text" name="adm_name" required>

            <label for="adm_pass">Mật khẩu:</label>
            <input type="text" name="adm_pass" required>

            <input type="submit" value="Tạo Admin">
        </form>

        <?php if ($message): ?>
            <div class="message <?= strpos($message, '✅') !== false ? 'success' : '' ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
