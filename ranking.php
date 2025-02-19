<?php
session_start();
include 'db.php'; // Kết nối database

// Lấy danh sách bài hát từ bảng `ranking`, sắp xếp theo `ranking` giảm dần
$stmt = $pdo->prepare("SELECT * FROM ranking ORDER BY ranking DESC");
$stmt->execute();
$songs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng Xếp Hạng</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; text-align: center; padding-top: 50px; }
        .container { max-width: 600px; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        table { width: 100%; margin-top: 20px; }
        .rank-number { font-weight: bold; font-size: 20px; }
    </style>
</head>
<body>

<div class="container">
    <h2>Bảng Xếp Hạng Bài Hát</h2>
    <table class="table table-striped mt-3">
        <thead>
            <tr>
                <th>Hạng</th>
                <th>Tên bài hát</th>
                <th>Lượt nghe</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $rank = 1;
            foreach ($songs as $song): 
            ?>
                <tr>
                    <td class="rank-number"><?php echo $rank++; ?></td>
                    <td><?php echo htmlspecialchars($song['title']); ?></td>
                    <td><?php echo $song['ranking']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
