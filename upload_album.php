<?php
require 'db.php'; // Kết nối database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $album_title = $_POST['album_title'];
    $artist = $_POST['artist'];
    $upload_dir = 'uploads/';
    $allowed_types = ['audio/mp3', 'audio/mpeg'];
    $poster_path = '';

    // Upload poster (nếu có)
    if (!empty($_FILES['poster']['name'])) {
        $poster_ext = pathinfo($_FILES['poster']['name'], PATHINFO_EXTENSION);
        $poster_name = time() . '_poster.' . $poster_ext;
        $poster_path = $upload_dir . $poster_name;
        move_uploaded_file($_FILES['poster']['tmp_name'], $poster_path);
    }

    // Tạo album_id mới
    $stmt = $pdo->prepare("INSERT INTO albums (title, artist, poster) VALUES (?, ?, ?)");
    $stmt->execute([$album_title, $artist, $poster_path]);
    $album_id = $pdo->lastInsertId();

    // Xử lý upload từng file nhạc
    foreach ($_FILES['songs']['tmp_name'] as $key => $tmp_name) {
        $file_name = $_FILES['songs']['name'][$key];
        $file_type = $_FILES['songs']['type'][$key];
        $file_path = $upload_dir . time() . '_' . $file_name;
        
        if (in_array($file_type, $allowed_types) && move_uploaded_file($tmp_name, $file_path)) {
            $stmt = $pdo->prepare("INSERT INTO songs (title, artist, file_path, poster, album_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$file_name, $artist, $file_path, $poster_path, $album_id]);
        }
    }
    echo "Album uploaded successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
<style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #008CBA;
            padding: 15px;
            color: white;
            border-radius: 8px;
        }

        .header h1 {
            margin: 0;
        }

        .btn {
            padding: 10px 15px;
            background: white;
            color: #008CBA;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            text-decoration: none;
        }

        .btn:hover {
            background: #007bb5;
            color: white;
        }

        .logout-btn {
            background: red;
            color: white;
        }

        .logout-btn:hover {
            background: darkred;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 50%;
            margin: auto;
        }
        h2 {
            color: #333;
        }
        input, button {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background: #28a745;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        .alert {
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Website Nghe Nhạc</h1>
    
    <a href="login_adm.php" class="btn">Đăng Xuất</a>
</div>
    <title>Upload Album</title>
</head>
<body>
<div class="container">
        <h2>Upload Album</h2>
        <form action="upload_album.php" method="POST" enctype="multipart/form-data">
            <label>Album Title:</label>
            <input type="text" name="album_title" required><br>
            <label>Artist:</label>
            <input type="text" name="artist" required><br>
            <label>Album Poster:</label>
            <input type="file" name="poster" accept="image/*"><br>
            <label>Upload Songs:</label>
            <input type="file" name="songs[]" multiple accept="audio/*" required><br>
            <button type="submit">Upload</button>
        </form>
    </div>
</body>
</html>
