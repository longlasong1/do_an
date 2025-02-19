<?php

// Kết nối database
$db_host = '127.0.0.1';
$db_name = 'music1';
$db_user = 'root'; //
$db_pass = 'longzokai1';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Xử lý upload file
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $artist = trim($_POST['artist']);
    $file = $_FILES['music_file'];

    // Validate input
    if (empty($title) || empty($artist) || empty($file['name'])) {
        $error = 'Vui lòng điền đầy đủ thông tin';
    } else {
        // Validate file
        $allowed_types = ['audio/mpeg', 'audio/wav', 'audio/mp3'];
        $max_size = 10 * 1024 * 1024; // 10MB

        if (!in_array($file['type'], $allowed_types)) {
            $error = 'Chỉ chấp nhận file MP3, WAV';
        } elseif ($file['size'] > $max_size) {
            $error = 'File quá lớn (tối đa 10MB)';
        } elseif ($file['error'] !== UPLOAD_ERR_OK) {
            $error = 'Có lỗi khi upload file';
        } else {
            // Tạo thư mục upload nếu chưa tồn tại
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            // Tạo tên file unique
            $file_ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $file_name = uniqid('music_', true) . '.' . $file_ext;
            $file_path = $upload_dir . $file_name;

            if (move_uploaded_file($file['tmp_name'], $file_path)) {
                // Lưu vào database
                try {
                    $stmt = $pdo->prepare("INSERT INTO songs (title, artist, file_path, user_id) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$title, $artist, $file_path, $_SESSION['user_id']]);
                    $success = 'Upload bài hát thành công!';
                } catch (PDOException $e) {
                    $error = 'Lỗi database: ' . $e->getMessage();
                }
            } else {
                $error = 'Không thể lưu file';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Upload Nhạc</title>
</head>
<body>
    <h1>Upload Nhạc</h1>
    
    <?php if ($error): ?>
        <div style="color: red;"><?= $error ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div style="color: green;"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div>
            <label>Tiêu đề:</label>
            <input type="text" name="title" required>
        </div>
        
        <div>
            <label>Nghệ sĩ:</label>
            <input type="text" name="artist" required>
        </div>
        
        <div>
            <label>File nhạc:</label>
            <input type="file" name="music_file" accept="audio/mpeg, audio/wav, audio/mp3" required>
        </div>
        
        <button type="submit">Upload</button>
    </form>
</body>
</html>