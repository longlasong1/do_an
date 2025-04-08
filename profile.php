<?php
session_start();
require 'db.php'; // Kết nối CSDL

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Lấy thông tin người dùng từ CSDL
$stmt = $pdo->prepare("SELECT id, email FROM users WHERE username = :username");
$stmt->bindParam(":username", $username, PDO::PARAM_STR);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Lỗi: Không tìm thấy người dùng";
    exit();
}

$user_id = $user['id'];
$email = $user['email'];

// Lấy lịch sử nghe nhạc
$stmt = $pdo->prepare("SELECT songs.title, history.listened_at FROM history JOIN songs ON history.song_id = songs.id WHERE history.user_id = :user_id ORDER BY history.listened_at DESC");
$stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
$stmt->execute();
$history_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Tin Người Dùng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: auto;
        }

        .btn {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 15px;
            background: #008CBA;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn:hover {
            background: #007bb5;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Thông Tin Cá Nhân</h2>
        <p><b>Username:</b> <?php echo htmlspecialchars($username); ?></p>
        <p><b>Email:</b> <?php echo htmlspecialchars($email); ?></p>
        
        <h3>Lịch sử nghe nhạc</h3>
        <ul>
            <?php foreach ($history_result as $row): ?>
                <li><?php echo htmlspecialchars($row['title']) . " - " . htmlspecialchars($row['listened_at']); ?></li>
            <?php endforeach; ?>
        </ul>

        <a href="index.php" class="btn">Quay lại Trang Chủ</a>
    </div>
</body>
</html>