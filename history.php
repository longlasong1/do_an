<?php

include("db.php"); // Kết nối CSDL
include("header.php"); // Gọi header

// Kiểm tra đăng nhập
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Lấy user_id
$username = $_SESSION['username'];
$query = "SELECT id FROM users WHERE username = :username";
$stmt = $pdo->prepare($query);
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    die("Lỗi: Không tìm thấy người dùng.");
}
$user_id = $user['id'];

// Lấy lịch sử nghe nhạc (chỉ lấy lần gần nhất của mỗi bài hát)
try {
    $sql = "SELECT songs.id, songs.title, songs.artist, MAX(history.listened_at) AS listened_at 
            FROM history 
            JOIN songs ON history.song_id = songs.id
            WHERE history.user_id = :user_id
            GROUP BY songs.id, songs.title, songs.artist
            ORDER BY listened_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $user_id]);
    $history_songs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Lỗi truy vấn: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch Sử Nghe Nhạc</title>
    <link rel="stylesheet" href="style.css"> <!-- Gọi file CSS -->
    <style>
        .content {
            margin-left: 260px;
            padding: 80px;
            flex-grow: 1;
        }

        .history-table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .history-table th, .history-table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
            cursor: pointer;
        }

        .history-table th {
            background: #008CBA;
            color: white;
            font-size: 16px;
        }

        .history-table tr:nth-child(even) {
            background: #f9f9f9;
        }

        .history-table tr:hover {
            background: #d0f0ff;
        }
    </style>
    <script>
        function playSong(songId) {
            window.location.href = "play.php?song_id=" + songId;
        }
    </script>
</head>
<body>

<div class="wrapper">
<!-- Sidebar -->
<div class="sidebar">
    <h2>Danh Mục</h2>
    <a href="index.php">🏠 Trang chủ</a>
    <a href="search.php">🎵 Tìm kiếm nhạc</a>
    <a href="ranking.php">📊 Xem bảng xếp hạng</a>
    <a href="newsongs.php">🔥 Nhạc mới</a>
    <a href="history.php" style="background: #1abc9c;">📜 Lịch sử nghe nhạc</a>
    <a href="rank7d.php">📊 Xem top tuần</a> 
    <a href="mylist.php">💖 Danh Sách Cá Nhân</a>
</div>

<!-- Nội dung chính -->
<div class="content">




    <h2 style="text-align: center;">Lịch Sử Nghe Nhạc</h2>
    
    <?php if (!empty($history_songs)): ?>
        <table class="history-table">
            <thead>
                <tr>
                    <th>Tên Bài Hát</th>
                    <th>Ca Sĩ</th>
                    <th>Thời Gian Nghe</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($history_songs as $song): ?>
                    <tr onclick="playSong(<?php echo $song['id']; ?>)">
                        <td><?php echo htmlspecialchars($song['title']); ?></td>
                        <td><?php echo htmlspecialchars($song['artist']); ?></td>
                        <td><?php echo date("d/m/Y H:i", strtotime($song['listened_at'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align: center;">Bạn chưa nghe bài hát nào.</p>
    <?php endif; ?>
</div>
<?php include("footer.php"); ?> <!-- Gọi footer -->
</div>

</body>
</html>
