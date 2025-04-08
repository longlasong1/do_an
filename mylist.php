<?php
session_start();
include("db.php"); // Kết nối CSDL
include("header.php"); // Gọi header

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Xử lý thêm bài hát vào danh sách
if (isset($_GET['add'])) {
    $song_id = $_GET['add'];
    try {
        $sql = "INSERT INTO mylist (user_id, song_id) VALUES (:user_id, :song_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['user_id' => $user_id, 'song_id' => $song_id]);
    } catch (PDOException $e) {
        die("Lỗi thêm bài hát: " . $e->getMessage());
    }
}

// Xử lý xóa bài hát khỏi danh sách
if (isset($_GET['remove'])) {
    $song_id = $_GET['remove'];
    try {
        $sql = "DELETE FROM mylist WHERE user_id = :user_id AND song_id = :song_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['user_id' => $user_id, 'song_id' => $song_id]);
    } catch (PDOException $e) {
        die("Lỗi xóa bài hát: " . $e->getMessage());
    }
}

// Lấy danh sách bài hát của người dùng
try {
    $sql = "SELECT songs.id, songs.title, songs.artist, songs.poster, songs.file_path 
            FROM mylist 
            JOIN songs ON mylist.song_id = songs.id 
            WHERE mylist.user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $user_id]);
    $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Lỗi truy vấn danh sách bài hát: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Cá Nhân</title>
    <link rel="stylesheet" href="style.css"> <!-- Gọi file CSS -->
    <style>
        .content {
            margin-left: 260px;
            padding: 20px;
        }
        .song-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .song-item {
            background: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            width: 200px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .song-item img {
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
        .play-btn, .remove-btn {
            display: inline-block;
            padding: 8px 12px;
            background: #008CBA;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            margin-top: 5px;
        }
        .remove-btn {
            background: #e74c3c;
        }
        .play-btn:hover {
            background: #007bb5;
        }
        .remove-btn:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h2>Danh Mục</h2>
    <a href="index.php">🏠 Trang chủ</a>
    <a href="search.php">🎵 Tìm kiếm nhạc</a>
    <a href="ranking.php">📊 Xem bảng xếp hạng</a>
    <a href="newsongs.php">🔥 Nhạc mới</a>
    <a href="history.php">📜 Lịch sử nghe nhạc</a>
    <a href="rank7d.php">📊 Xem top tuần</a>
    <a href="mylist.php" style="background: #1abc9c;">💖 Danh Sách Cá Nhân</a>
</div>

<!-- Nội dung chính -->
<div class="content">
    <h2><center>🎵 Danh Sách Bài Hát Yêu Thích</center></h2>
    <div class="song-list">
        <?php if (!empty($songs)): ?>
            <?php foreach ($songs as $song): ?>
                <div class="song-item">
                    <img src="<?php echo htmlspecialchars($song['poster']); ?>" alt="Poster">
                    <div class="song-title"><?php echo htmlspecialchars($song['title']); ?></div>
                    <div class="song-artist"><?php echo htmlspecialchars($song['artist']); ?></div>
                    <audio controls>
                        <source src="<?php echo htmlspecialchars($song['file_path']); ?>" type="audio/mpeg">
                        Trình duyệt không hỗ trợ phát nhạc.
                    </audio>
                    <br>
                    <a href="play.php?song_id=<?php echo $song['id']; ?>" class="play-btn">🎵 Nghe ngay</a>
                    <a href="mylist.php?remove=<?php echo $song['id']; ?>" class="remove-btn">❌ Xóa</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Danh sách của bạn đang trống. Hãy thêm bài hát yêu thích!</p>
        <?php endif; ?>
    </div>
</div>

<?php include("footer.php"); ?> <!-- Gọi footer -->
</body>
</html>
