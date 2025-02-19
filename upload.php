<?php
include 'db.php'; // Kết nối database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $artist = $_POST['artist'];
    $upload_dir = "uploads/"; // Thư mục lưu file
    $file_name = basename($_FILES["music_file"]["name"]);
    $file_path = $upload_dir . $file_name;
    $upload_ok = 1;

    // Kiểm tra file có phải định dạng nhạc không
    $file_type = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
    $allowed_types = ["mp3", "wav", "flac"];

    if (!in_array($file_type, $allowed_types)) {
        $error = "Chỉ chấp nhận file nhạc (MP3, WAV, FLAC).";
        $upload_ok = 0;
    }

    // Kiểm tra nếu file_path đã tồn tại trong database
    $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM songs WHERE file_path = :file_path");
    $stmt_check->bindParam(":file_path", $file_path);
    $stmt_check->execute();
    $file_exists = $stmt_check->fetchColumn();

    if ($file_exists > 0) {
        $error = "File đã tồn tại trong hệ thống.";
        $upload_ok = 0;
    }

    // Kiểm tra nếu file hợp lệ
    if ($upload_ok && move_uploaded_file($_FILES["music_file"]["tmp_name"], $file_path)) {
        // Lưu vào bảng `songs`
        $sql = "INSERT INTO songs (title, artist, file_path) VALUES (:title, :artist, :file_path)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":title", $title);
        $stmt->bindParam(":artist", $artist);
        $stmt->bindParam(":file_path", $file_path);
        $stmt->execute();
        $song_id = $pdo->lastInsertId(); // Lấy ID của bài hát vừa thêm

        // Lưu vào bảng `ranking`
        $sql_rank = "INSERT INTO ranking (song_id, title, ranking) VALUES (:song_id, :title, 0)";
        $stmt_rank = $pdo->prepare($sql_rank);
        $stmt_rank->bindParam(":song_id", $song_id);
        $stmt_rank->bindParam(":title", $title);
        $stmt_rank->execute();

        $success = "Upload thành công!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Nhạc</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .upload-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .upload-container h2 { text-align: center; }
        .message {
            text-align: center;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>

<div class="upload-container">
    <h2>Upload Nhạc</h2>
    <?php 
        if (isset($error)) echo "<p class='message error'>$error</p>"; 
        if (isset($success)) echo "<p class='message success'>$success</p>";
    ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Tên bài hát</label>
            <input type="text" class="form-control" name="title" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Tên nghệ sĩ</label>
            <input type="text" class="form-control" name="artist" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Chọn file nhạc</label>
            <input type="file" class="form-control" name="music_file" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Upload</button>
    </form>
</div>

</body>
</html>
