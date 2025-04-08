<?php
session_start();
include("db.php"); // Kết nối CSDL
include("header.php"); // Gọi header

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Kiểm tra tham số song_id
if (!isset($_GET['song_id']) || empty($_GET['song_id'])) {
    die("Bài hát không tồn tại!");
}

$song_id = $_GET['song_id'];
$user_id = $_SESSION['user_id']; // Giả sử bạn đã lưu user_id trong session
try {
    // Cập nhật ranking trong bảng ranking
    $update_ranking = "UPDATE ranking SET ranking = ranking + 1 WHERE song_id = :song_id";
    $stmt = $pdo->prepare($update_ranking);
    $stmt->bindParam(':song_id', $song_id, PDO::PARAM_INT);
    $stmt->execute();

    // Cập nhật ranking trong bảng rank7d
    $update_rank7d = "UPDATE rank7d SET ranking = ranking + 1 WHERE song_id = :song_id";
    $stmt = $pdo->prepare($update_rank7d);
    $stmt->bindParam(':song_id', $song_id, PDO::PARAM_INT);
    $stmt->execute();

} catch (PDOException $e) {
    die("Lỗi cập nhật ranking: " . $e->getMessage());
}

try {
    // Truy vấn lấy thông tin bài hát
    $sql = "SELECT * FROM songs WHERE id = :song_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':song_id', $song_id, PDO::PARAM_INT);
    $stmt->execute();
    $song = $stmt->fetch(PDO::FETCH_ASSOC);

    // Kiểm tra xem bài hát có tồn tại không
    if (!$song) {
        die("Bài hát không tìm thấy!");
    }

    // Lưu lịch sử nghe nhạc vào bảng history
    $insert_history = "INSERT INTO history (user_id, song_id, listened_at) VALUES (:user_id, :song_id, NOW())";
    $stmt = $pdo->prepare($insert_history);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':song_id', $song_id, PDO::PARAM_INT);
    $stmt->execute();
    // Truy vấn danh sách các bài hát
    $sql_songs = "SELECT id, title, artist, poster FROM songs";
    $stmt = $pdo->prepare($sql_songs);
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
    <title>Nghe Nhạc - <?php echo htmlspecialchars($song['title']); ?></title>
    <link rel="stylesheet" href="style.css"> <!-- Gọi file CSS ở đây -->
    <style>
        .content {
            margin-left: 260px;
            padding: 20px;
            flex-grow: 1;
        }
        .song-container {
            text-align: center;
            margin-top: 80px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 60%;
            margin-left: auto;
            margin-right: auto;
        }
        .song-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
        .song-item {
            background: white;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            width: 200px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .song-title {
            font-size: 24px;
            font-weight: bold;
        }
        .song-artist {
            font-size: 18px;
            color: gray;
        }
        .song-poster {
            width: 200px;
            height: 200px;
            border-radius: 10px;
            margin: 20px 0;
            object-fit: cover;
        }
        audio {
            width: 100%;
            margin-top: 10px;
        }
        .add-btn {
            display: inline-block;
            padding: 10px 15px;
            background: #008CBA;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            margin-top: 10px;
        }
        .add-btn:hover {
            background: #005f73;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Danh Mục</h2>
    <a href="index.php">🏠 Trang chủ</a>
    <a href="search.php">🎵 Tìm kiếm nhạc</a>
    <a href="ranking.php">📊 Xem bảng xếp hạng</a>
    <a href="newsongs.php">🔥 Nhạc mới</a>
    <a href="history.php">📜 Lịch sử nghe nhạc</a> 
    <a href="rank7d.php">📊 Xem top tuần</a> 
    <a href="mylist.php">💖 Danh Sách Cá Nhân</a>
</div>

<div class="content">
    <div class="song-container">
        <h2 class="song-title"><?php echo htmlspecialchars($song['title']); ?></h2>
        <p class="song-artist">Ca sĩ: <?php echo htmlspecialchars($song['artist']); ?></p>
        
        <img src="<?php echo htmlspecialchars($song['poster']); ?>" alt="Poster" class="song-poster">
        
        <audio controls>
            <source src="<?php echo htmlspecialchars($song['file_path']); ?>" type="audio/mpeg">
            Trình duyệt của bạn không hỗ trợ phát nhạc.
        </audio>
        
        <a href="mylist.php?add=<?php echo $song_id; ?>" class="add-btn">💖 Thêm vào danh sách</a>
    </div>
    <div class="song-list">
        <?php foreach ($songs as $s): ?>
            <div class="song-item">
                <a href="play.php?song_id=<?php echo $s['id']; ?>">
                    <img src="<?php echo htmlspecialchars($s['poster']); ?>" width="100">
                    <p><?php echo htmlspecialchars($s['title']); ?></p>
                    <p><?php echo htmlspecialchars($s['artist']); ?></p>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include("footer.php"); ?>
</body>
</html>
