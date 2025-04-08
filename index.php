<?php
session_start();
include("db.php"); // Kết nối CSDL
include("header.php"); // Gọi header

try {
    // Truy vấn lấy tất cả bài hát
    $sql = "SELECT * FROM songs";
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
    <title>Trang Chủ - Website Nghe Nhạc</title>
    <link rel="stylesheet" href="style.css"> <!-- Gọi file CSS ở đây -->
    <style>
       
        

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

<div class="wrapper">
    

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Danh Mục</h2>
        <a href="search.php">🎵 Tìm kiếm nhạc</a>
        <a href="ranking.php">📊 Xem bảng xếp hạng</a>
        <a href="newsongs.php">🔥 Nhạc mới</a>
        <a href="history.php">📜 Lịch sử nghe nhạc</a> 
        <a href="rank7d.php">📊 Xem top tuần</a> 
        <a href="mylist.php">💖 Danh Sách Cá Nhân</a>
    </div>

    <!-- Nội dung chính -->
    <div class="content">
        <div class="welcome-text">
            <h2><center>Danh Sách Bài Hát</center></h2>
        </div>

        <div class="song-list">
            <?php if (!empty($songs)): ?>
                <?php foreach ($songs as $song): ?>
                    <div class="song">
                        <img src="<?php echo htmlspecialchars($song['poster']); ?>" alt="Poster">
                        <div class="song-title"><?php echo htmlspecialchars($song['title']); ?></div>
                        <div class="song-artist"><?php echo htmlspecialchars($song['artist']); ?></div>
                        <a href="play.php?song_id=<?php echo $song['id']; ?>" class="play-btn">🎵 Nghe ngay</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Không có bài hát nào trong cơ sở dữ liệu.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php include("footer.php"); ?> <!-- Gọi footer -->
</div>

</body>
</html>
