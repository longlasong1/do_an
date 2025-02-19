<?php
session_start();
include 'db.php'; // Kết nối database

$search_results = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $keyword = "%" . $_POST['keyword'] . "%"; // Thêm ký tự `%` để tìm kiếm một phần

    // Truy vấn tìm kiếm trong bảng `songs`
    $stmt = $pdo->prepare("SELECT * FROM songs WHERE title LIKE :keyword OR artist LIKE :keyword");
    $stmt->bindParam(":keyword", $keyword);
    $stmt->execute();
    $search_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tìm Kiếm Nhạc</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; padding-top: 50px; text-align: center; }
        .container { max-width: 600px; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        table { width: 100%; margin-top: 20px; }
    </style>
</head>
<body>

<div class="container">
    <h2>Tìm Kiếm Nhạc</h2>
    <form method="POST">
        <input type="text" name="keyword" class="form-control" placeholder="Nhập tên bài hát hoặc nghệ sĩ..." required>
        <button type="submit" class="btn btn-primary mt-2">Tìm kiếm</button>
    </form>

    <?php if (!empty($search_results)): ?>
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>Tên bài hát</th>
                    <th>Nghệ sĩ</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($search_results as $song): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($song['title']); ?></td>
                        <td><?php echo htmlspecialchars($song['artist']); ?></td>
                        <td><a href="play.php?id=<?php echo $song['id']; ?>" class="btn btn-success">Phát</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <p class="text-danger mt-3">Không tìm thấy kết quả.</p>
    <?php endif; ?>
</div>

</body>
</html>
