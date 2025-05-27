<?php
require 'db.php'; // Kết nối database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $artist = $_POST['artist'];
    
    $target_dir = "uploads/";
    $music_file = $target_dir . basename($_FILES["music_file"]["name"]);
    $poster_file = $target_dir . basename($_FILES["poster_file"]["name"]);

    if (move_uploaded_file($_FILES["music_file"]["tmp_name"], $music_file) && move_uploaded_file($_FILES["poster_file"]["tmp_name"], $poster_file)) {
        $pdo->beginTransaction();
        try {
            // Thêm vào bảng songs
            $sql = "INSERT INTO songs (title, artist, file_path, poster) VALUES (:title, :artist, :file_path, :poster)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':artist', $artist);
            $stmt->bindParam(':file_path', $music_file);
            $stmt->bindParam(':poster', $poster_file);
            $stmt->execute();

            // Lấy ID của bài hát vừa thêm
            $song_id = $pdo->lastInsertId();

            
            // Thêm vào bảng ranking với song_id và ranking mặc định là 0
            $sql_ranking = "INSERT INTO ranking (song_id, title, ranking) VALUES (:song_id, :title, 0)";
            $stmt_ranking = $pdo->prepare($sql_ranking);
            $stmt_ranking->bindParam(':song_id', $song_id, PDO::PARAM_INT);
            $stmt_ranking->bindParam(':title', $title);
            $stmt_ranking->execute();

            // Thêm vào bảng rank7d với song_id và ranking mặc định là 0
            $sql_rank7d = "INSERT INTO rank7d (song_id, title, ranking) VALUES (:song_id, :title, 0)";
            $stmt_rank7d = $pdo->prepare($sql_rank7d);
            $stmt_rank7d->bindParam(':song_id', $song_id, PDO::PARAM_INT);
            $stmt_rank7d->bindParam(':title', $title);
            $stmt_rank7d->execute();

            $pdo->commit();
            echo "<p class='success'>Upload thành công!</p>";
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "<p class='error'>Lỗi: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p class='error'>Lỗi khi tải lên file.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Nhạc</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        input[type="text"], input[type="file"] {
            width: 100%;
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            width: 100%;
            border-radius: 5px;
        }
        input[type="submit"]:hover {
            background: #218838;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Upload Nhạc</h2>
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="Tiêu đề bài hát" required><br>
            <input type="text" name="artist" placeholder="Tên nghệ sĩ" required><br>
            <input type="file" name="music_file" accept="audio/*" required><br>
            <input type="file" name="poster_file" accept="image/*" required><br>
            <input type="submit" value="Upload Nhạc">
            <a href="index_adm.php" class="btn">trang chủ</a>
        </form>
    </div>
</body>
</html>
