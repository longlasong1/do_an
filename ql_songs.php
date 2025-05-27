<?php
include 'db.php';

$stmt = $pdo->query("SELECT * FROM songs");
$songs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quản lý bài hát</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
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
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .btn-add, .btn-back {
            display: inline-block;
            margin: 10px 10px 20px 0;
            background: #28a745;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
        }

        .btn-add:hover {
            background: #218838;
        }

        .btn-back {
            background: #6c757d;
        }

        .btn-back:hover {
            background: #5a6268;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Danh sách bài hát</h2>

    
    <a class="btn-add" href="upload.php">➕ Thêm bài hát</a>

    <table>
        <tr>
            <th>ID</th>
            <th>Tiêu đề</th>
            <th>Ca sĩ</th>
            <th>File nhạc</th>
            <th>Poster</th>
            <th>Ngày tạo</th>
            <th>Hành động</th>
        </tr>
        <?php foreach ($songs as $song): ?>
            <tr>
                <td><?= $song['id'] ?></td>
                <td><?= htmlspecialchars($song['title']) ?></td>
                <td><?= htmlspecialchars($song['artist']) ?></td>
                <td><?= basename($song['file_path']) ?></td>
                <td><img src="<?= $song['poster'] ?>" alt="poster"></td>
                <td><?= $song['created_at'] ?></td>
                <td>
                    <a href="update_song.php?id=<?= $song['id'] ?>">Sửa</a> |
                    <a href="delete_song.php?id=<?= $song['id'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa bài hát này?')">Xóa</a>
                </td>
            </tr>
        <?php endforeach; ?>

    </table>
    <a class="btn-back" href="index_adm.php">🔙 Quay lại</a>

</div>
</body>
</html>
