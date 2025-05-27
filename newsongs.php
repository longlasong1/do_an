<?php

include("db.php"); // Kết nối CSDL

try {
    // Lấy các bài hát được tải lên hôm nay
    $sql = "SELECT * FROM songs WHERE DATE(created_at) = CURDATE() ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Lỗi truy vấn: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhạc Mới - Website Nghe Nhạc</title>
    <link rel="stylesheet" href="style.css"> <!-- Gọi file CSS ở đây -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
        }


        /* Nội dung chính */
        .content {
            margin-left: 260px;
            padding: 20px;
            flex-grow: 1;
        }

        .welcome-text {
            text-align: center;
            margin-top: 80px;
        }

        /* Danh sách bài hát */
        .song-list {
            width: 90%;
            margin: auto;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .song {
            width: 200px;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .song img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }

        .song-title {
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
        }

        .song-artist {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }

       
    </style>
</head>
<body>
<body>
<div class="wrapper">  <!-- Bọc toàn bộ nội dung trong wrapper -->
    
    <?php include("header.php"); ?> <!-- Gọi header -->
    <!-- Sidebar -->
<div class="sidebar">
    <h2>Danh Mục</h2>
    <a href="index.php">🏠 Trang chủ</a>
    <a href="search.php">🎵 Tìm kiếm nhạc</a>
    <a href="ranking.php">📊 Xem bảng xếp hạng</a>
    <a href="newsongs.php" style="background: #1abc9c;">🔥 Nhạc mới</a>
    <a href="history.php">📜 Lịch sử nghe nhạc</a> 
    <a href="rank7d.php">📊 Xem top tuần</a> 
    <a href="mylist.php">💖 Danh Sách Cá Nhân</a>
</div>

    <!-- Nội dung chính -->
    <div class="content">
        <div class="welcome-text">
            <h2>Các Bài Hát Mới Nhất</h2>
        </div>

        <div class="song-list">
            <?php if (!empty($songs)): ?>
                <?php foreach ($songs as $song): ?>
                    <div class="song">
                        <img src="<?php echo htmlspecialchars($song['poster']); ?>" alt="Poster">
                        <div class="song-title"><?php echo htmlspecialchars($song['title']); ?></div>
                        <div class="song-artist"><?php echo htmlspecialchars($song['artist']); ?></div>
                        <small>Ngày tải lên: <?php echo date("d/m/Y H:i", strtotime($song['created_at'])); ?></small>
                        <br>
                        <a href="play.php?id=<?php echo $song['id']; ?>" class="play-btn">🎵 Nghe ngay</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Hôm nay chưa có bài hát mới.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php include("footer.php"); ?> <!-- Gọi footer, luôn nằm ở cuối -->
</div>
</body>
</html>

</body>
</html>
