<?php

include("db.php"); // Kết nối CSDL
include("header.php"); // Gọi header
try {
    // Truy vấn lấy bài hát xếp hạng từ cao đến thấp
    $sql = "SELECT song_id, title, ranking FROM ranking ORDER BY ranking DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rankings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Lỗi truy vấn: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng Xếp Hạng - Website Nghe Nhạc</title>
    <link rel="stylesheet" href="style.css"> <!-- Gọi file CSS ở đây -->
    <style>
        body 
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

        /* Bảng xếp hạng */
        .ranking-table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .ranking-table th, .ranking-table td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        .ranking-table th {
            background: #008CBA;
            color: white;
            font-size: 18px;
        }

        .ranking-table tr:hover {
            background: #f1f1f1;
        }

        .rank-number {
            font-weight: bold;
            font-size: 20px;
            color: #d35400;
        }

      
    </style>
</head>
<body>

<div class="wrapper">
<!-- Sidebar -->
<div class="sidebar">
    <h2>Danh Mục</h2>
    <a href="index.php">🏠 Trang chủ</a>
    <a href="search.php">🎵 Tìm kiếm nhạc</a>
    <a href="ranking.php" style="background: #1abc9c;">📊 Xem bảng xếp hạng</a>
    <a href="newsongs.php">🔥 Nhạc mới</a>
    <a href="history.php">📜 Lịch sử nghe nhạc</a> 
    <a href="rank7d.php">📊 Xem top tuần</a> 
    <a href="mylist.php">💖 Danh Sách Cá Nhân</a>
</div>

<!-- Nội dung chính -->
<div class="content">
    <div class="welcome-text">
        <h2>Bảng Xếp Hạng Bài Hát</h2>
    </div>

    <table class="ranking-table">
        <tr>
            <th>Hạng</th>
            <th>Bài Hát</th>
            <th>Điểm</th>
            <th>Nghe Ngay</th>
        </tr>
        <?php if (!empty($rankings)): ?>
            <?php foreach ($rankings as $index => $song): ?>
                <tr>
                    <td class="rank-number">#<?php echo ($index + 1); ?></td>
                    <td><?php echo htmlspecialchars($song['title']); ?></td>
                    <td><?php echo htmlspecialchars($song['ranking']); ?></td>
                    <td>
    <?php if (isset($song['song_id'])): ?>
        <a href="play.php?song_id=<?php echo $song['song_id']; ?>" class="play-btn">🎵 Nghe</a>
    <?php else: ?>
        <span>Không có ID</span>
    <?php endif; ?>
</td>

                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">Chưa có bài hát nào trong bảng xếp hạng.</td>
            </tr>
        <?php endif; ?>
    </table>
</div>
<?php include("footer.php"); ?> <!-- Gọi footer -->
</div>

</body>
</html>
