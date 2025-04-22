<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    $avatar = $_POST['avatar'];

    $stmt = $pdo->prepare("INSERT INTO users (username, password, email, avatar) VALUES (?, ?, ?, ?)");
    $stmt->execute([$username, $password, $email, $avatar]);

    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Thêm người dùng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        h2 {
            color: #333;
        }

        form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        form button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #218838;
        }

        .back-link {
            display: inline-block;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Thêm người dùng</h2>
    <form method="POST">
        Username: <input type="text" name="username" required><br>
        Mật khẩu: <input type="password" name="password" required><br>
        Email: <input type="email" name="email" required><br>
        Avatar URL: <input type="text" name="avatar"><br>
        <button type="submit">Thêm</button>
    </form>
    <a class="back-link" href="qluser.php">← Quay lại</a>
</div>
</body>
</html>
