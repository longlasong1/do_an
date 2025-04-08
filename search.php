<?php
session_start();
include("db.php"); // Kết nối CSDL

$searchKeyword = "";
$songs = [];

if (isset($_GET['query']) && !empty($_GET['query'])) {
    $searchKeyword = trim($_GET['query']);

    try {
        // Truy vấn tìm kiếm bài hát theo title hoặc artist
        $sql = "SELECT * FROM songs WHERE title LIKE :keyword OR artist LIKE :keyword";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':keyword', "%$searchKeyword%", PDO::PARAM_STR);
        $stmt->execute();
        $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Lỗi truy vấn: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tìm Kiếm Nhạc</title>
    <link rel="stylesheet" href="style.css"> <!-- Gọi file CSS ở đây -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        

        /* Tìm kiếm */
        .search-bar {
            display: flex;
            align-items: center;
            background: white;
            border-radius: 20px;
            padding: 5px 15px;
            width: 100%;
            max-width: 400px;
        }

        .search-bar input {
            border: none;
            padding: 8px;
            font-size: 16px;
            outline: none;
            width: 100%;
        }

        .search-bar button {
            background: #008CBA;
            color: white;
            border: none;
            padding: 8px 15px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }

        

        /* Nội dung chính */
        .content {
            margin-left: 240px;
            margin-top: 80px;
            width: 100%;
            max-width: 800px;
            padding: 20px;
        }

        .song-list {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .song-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .song-item img {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            margin-right: 15px;
            object-fit: cover;
        }

        .song-info {
            flex-grow: 1;
        }

        .song-title {
            font-size: 18px;
            font-weight: bold;
        }

        .song-artist {
            font-size: 14px;
            color: gray;
        }

        

      
    </style>
</head>
<body>
<div class="wrapper">
<!-- Header -->
<div class="header">
    <h1>Tìm Kiếm Nhạc</h1>

    <form action="search.php" method="GET" class="search-bar">
        <input type="text" name="query" placeholder="Nhập tên bài hát hoặc ca sĩ..." value="<?php echo htmlspecialchars($searchKeyword); ?>">
        <button type="submit">🔍</button>
    </form>

    <div>
        <?php if (isset($_SESSION['username'])): ?>
            <span>Xin chào,</span>
            <a href="profile.php" class="btn"><?php echo $_SESSION['username']; ?></a>
            <a href="logout.php" class="btn logout-btn">Đăng xuất</a>
        <?php else: 
            header("Location: login.php");
            exit();
        endif; ?>
    </div>
</div>

<!-- Sidebar -->
<div class="sidebar">
    <a href="index.php">🏠 Trang chủ</a>
    <a href="search.php" style="background: #1abc9c;">🎵 Tìm Kiếm Nhạc</a>
    <a href="ranking.php">📊 Xem bảng xếp hạng</a>
    <a href="newsongs.php">🔥 Nhạc mới</a>
    <a href="history.php">📜 Lịch sử nghe nhạc</a> 
    <a href="rank7d.php">📊 Xem top tuần</a> 
    <a href="mylist.php">💖 Danh Sách Cá Nhân</a>
</div>

<!-- Nội dung chính -->
<div class="content">
    <div class="song-list">
        <h2>Kết quả tìm kiếm</h2>
        <?php if (count($songs) > 0): ?>
            <?php foreach ($songs as $song): ?>
                <div class="song-item">
                    <img src="<?php echo htmlspecialchars($song['poster']); ?>" alt="Poster">
                    <div class="song-info">
                        <div class="song-title"><?php echo htmlspecialchars($song['title']); ?></div>
                        <div class="song-artist">Ca sĩ: <?php echo htmlspecialchars($song['artist']); ?></div>
                    </div>
                    <a href="play.php?song_id=<?php echo $song['id']; ?>" class="play-btn">🎵 Nghe</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Không tìm thấy bài hát nào.</p>
        <?php endif; ?>
    </div>
</div>
<?php include("footer.php"); ?> <!-- Gọi footer -->
</div>
</body>
</html>
