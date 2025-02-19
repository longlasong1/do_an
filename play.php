<?php
session_start();
include 'db.php'; // Kết nối database

if (isset($_GET['id'])) {
    $song_id = $_GET['id'];

    // Lấy thông tin bài hát từ bảng `songs`
    $stmt = $pdo->prepare("SELECT * FROM songs WHERE id = :id");
    $stmt->bindParam(":id", $song_id);
    $stmt->execute();
    $song = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($song) {
        // Cập nhật ranking (+1) trong bảng `ranking`
        $stmt_rank = $pdo->prepare("UPDATE ranking SET ranking = ranking + 1 WHERE song_id = :id");
        $stmt_rank->bindParam(":id", $song_id);
        $stmt_rank->execute();
    } else {
        die("Bài hát không tồn tại.");
    }
} else {
    die("Thiếu ID bài hát.");
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phát Nhạc</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; text-align: center; padding-top: 50px; }
        .player-container {
            width: 50%;
            margin: auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<div class="player-container">
    <h2><?php echo htmlspecialchars($song['title']); ?></h2>
    <p><strong>Nghệ sĩ:</strong> <?php echo htmlspecialchars($song['artist']); ?></p>
    <audio controls autoplay>
        <source src="<?php echo htmlspecialchars($song['file_path']); ?>" type="audio/mpeg">
        Trình duyệt của bạn không hỗ trợ phát nhạc.
    </audio>
    <br><br>
    <a href="index.php" class="btn btn-primary">Quay lại danh sách</a>
</div>

</body>
</html>
