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

// Kiểm tra song_id có tồn tại & có phải số không
if (!isset($_GET["song_id"]) || !ctype_digit($_GET["song_id"])) {
    echo "<h3 class='text-center text-danger mt-5'>Lỗi: Thiếu hoặc sai định dạng song_id!</h3>";
    echo '<div class="text-center"><a href="index.php" class="btn btn-primary">Quay lại trang chủ</a></div>';
    exit;
}

$song_id = intval($_GET["song_id"]);

// Truy vấn lấy bài hát từ CSDL
$sql = "SELECT title, artist, file_path FROM songs WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $song_id);
$stmt->execute();
$result = $stmt->get_result();

// Kiểm tra bài hát có tồn tại không
if ($row = $result->fetch_assoc()) {
    $title = htmlspecialchars($row["title"]);
    $artist = htmlspecialchars($row["artist"]);
    $file_path = htmlspecialchars($row["file_path"]);
} else {
    echo "<h3 class='text-center text-danger mt-5'>Lỗi: Không tìm thấy bài hát!</h3>";
    echo '<div class="text-center"><a href="index.php" class="btn btn-primary">Quay lại trang chủ</a></div>';
    exit;
}

// Đóng kết nối
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nghe Nhạc - <?php echo $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #ff9a9e, #fad0c4);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center;
        }
        .player-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
        }
        audio {
            width: 100%;
            margin-top: 10px;
        }
        .artist {
            font-size: 1.2rem;
            color: gray;
        }
    </style>
</head>
<body>
    <div class="player-container">
        <h2><?php echo $title; ?></h2>
        <p class="artist">Ca sĩ: <?php echo $artist; ?></p>
        <audio controls autoplay>
            <source src="<?php echo $file_path; ?>" type="audio/mpeg">
            Trình duyệt không hỗ trợ phát nhạc.
        </audio>
        <br><br>
        <a href="index.php" class="btn btn-primary">Quay lại danh sách</a>
    </div>
</body>
