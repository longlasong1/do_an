<?php
include 'db.php';
$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $avatar = $_POST['avatar'];

    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, avatar = ? WHERE id = ?");
    $stmt->execute([$username, $email, $avatar, $id]);

    header("Location: index.php");
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cập nhật người dùng</title>
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
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #0069d9;
        }

        .back-link {
            display: inline-block;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Cập nhật người dùng</h2>
    <form method="POST">
        Username: <input type="text" name="username" value="<?= $user['username'] ?>" required><br>
        Email: <input type="email" name="email" value="<?= $user['email'] ?>" required><br>
        Avatar URL: <input type="text" name="avatar" value="<?= $user['avatar'] ?>"><br>
        <button type="submit">Cập nhật</button>
    </form>
    <a class="back-link" href="qluser.php">← Quay lại</a>
</div>
</body>
</html>
