<?php
session_start();
include 'db.php'; // Kết nối database

$search_results = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $keyword = "%" . $_POST['keyword'] . "%";

    // Tìm kiếm bài hát trong bảng `songs`
    $stmt = $pdo->prepare("SELECT * FROM songs WHERE title LIKE :keyword OR artist LIKE :keyword");
    $stmt->bindParam(":keyword", $keyword);
    $stmt->execute();
    $search_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Xóa bài hát nếu có yêu cầu
if (isset($_GET['delete_id'])) {
    $song_id = $_GET['delete_id'];

    // Lấy đường dẫn file
    $stmt = $pdo->prepare("SELECT file_path FROM songs WHERE id = :id");
    $stmt->bindParam(":id", $song_id);
    $stmt->execute();
    $song = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($song) {
        // Xóa file nhạc trên server
        if (file_exists($song['file_path'])) {
            unlink($song['file_path']);
        }

        // Xóa bài hát trong bảng `ranking`
        $stmt_delete_ranking = $pdo->prepare("DELETE FROM ranking WHERE song_id = :id");
        $stmt_delete_ranking->bindParam(":id", $song_id);
        $stmt_delete_ranking->execute();

        // Xóa bài hát trong bảng `songs`
        $stmt_delete_song = $pdo->prepare("DELETE FROM songs WHERE id = :id");
        $stmt_delete_song->bindParam(":id", $song_id);
        if ($stmt_delete_song->execute()) {
            echo "<script>alert('Bài hát đã được xóa thành công!'); window.location.href = 'delete_song.php';</script>";
        } else {
            echo "<script>alert('Lỗi khi xóa bài hát.');</script>";
        }
    } else {
        echo "<script>alert('Bài hát không tồn tại.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xóa Nhạc</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script>
        function confirmDelete(songId, songTitle) {
            if (confirm("Bạn có chắc muốn xóa bài hát '" + songTitle + "' không?")) {
                window.location.href = "delete_song.php?delete_id=" + songId;
            }
        }
    </script>
    <style>
        body { background-color: #f8f9fa; text-align: center; padding-top: 50px; }
        .container { max-width: 600px; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        table { width: 100%; margin-top: 20px; }
    </style>
</head>
<body>

<div class="container">
    <h2>Xóa Nhạc</h2>
    <form method="POST">
        <input type="text" name="keyword" class="form-control" placeholder="Nhập tên bài hát hoặc nghệ sĩ..." required>
        <button type="submit" class="btn btn-danger mt-2">Tìm kiếm</button>
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
                        <td>
                            <button class="btn btn-danger" onclick="confirmDelete(<?php echo $song['id']; ?>, '<?php echo htmlspecialchars($song['title']); ?>')">Xóa</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <p class="text-danger mt-3">Không tìm thấy bài hát.</p>
    <?php endif; ?>
</div>

</body>
</html>
