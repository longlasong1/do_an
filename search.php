<?php
// Bật hiển thị lỗi (để debug nếu cần)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Kết nối MySQL
$servername = "127.0.0.1";
$username = "root"; // Thay đổi nếu cần
$password = "longzokai1"; // Thay đổi nếu cần
$database = "music1"; // Thay đổi theo tên database của bạn

$conn = new mysqli($servername, $username, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xử lý tìm kiếm
$search = "";
$songs = [];

if (isset($_GET["search"])) {
    $search = trim($_GET["search"]);
    if (!empty($search)) {
        $sql = "SELECT id, title, file_path FROM songs WHERE title LIKE ?";
        $stmt = $conn->prepare($sql);
        $search_param = "%$search%";
        $stmt->bind_param("s", $search_param);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $songs[] = $row;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tìm kiếm nhạc</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #84fab0, #8fd3f4);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }
        .search-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 100%;
            text-align: center;
        }
        .song-list {
            list-style: none;
            padding: 0;
        }
        .song-item {
            background: #f8f9fa;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
            transition: 0.3s;
        }
        .song-item:hover {
            background: #e9ecef;
        }
        .no-results {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <h2 class="mb-3">Tìm kiếm bài hát</h2>
        <form method="GET" action="search.php">
            <input type="text" name="search" class="form-control" placeholder="Nhập tên bài hát..." value="<?php echo htmlspecialchars($search); ?>" required>
            <button type="submit" class="btn btn-primary mt-2">Tìm kiếm</button>
        </form>
        <hr>
        <?php if (isset($_GET["search"])): ?>
            <?php if (!empty($songs)): ?>
                <ul class="song-list">
                    <?php foreach ($songs as $song): ?>
                        <li class="song-item">
                            <a href="play.php?song_id=<?php echo $song['id']; ?>" class="text-decoration-none">
                                🎵 <?php echo htmlspecialchars($song['title']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="no-results">Không tìm thấy bài hát nào!</p>
            <?php endif; ?>
        <?php endif; ?>
        <a href="index.php" class="btn btn-secondary mt-3">Quay lại trang chủ</a>
    </div>
</body>
</html>
