<?php
include 'db.php';

$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quản lý người dùng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        h2 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #f0f0f0;
        }

        img {
            border-radius: 50%;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .btn-add {
            display: inline-block;
            margin-bottom: 10px;
            background: #28a745;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
        }

        .btn-add:hover {
            background: #218838;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Danh sách người dùng</h2>
    <a class="btn-add" href="add_user.php">➕ Thêm người dùng</a>
    <table>
        <tr>
            <th>ID</th><th>Username</th><th>Email</th><th>Avatar</th><th>Hành động</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= $user['username'] ?></td>
                <td><?= $user['email'] ?></td>
                <td><img src="<?= $user['avatar'] ?>" width="50" height="50" /></td>
                <td>
                    <a href="update_user.php?id=<?= $user['id'] ?>">Sửa</a> |
                    <a href="delete_user.php?id=<?= $user['id'] ?>" onclick="return confirm('Xóa người dùng này?')">Xóa</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a class="btn-back" href="index_adm.php">🔙 Quay lại </a>
</div>
</body>
</html>
