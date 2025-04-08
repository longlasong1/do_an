<?php
include 'db.php'; // Kết nối đến cơ sở dữ liệu

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Lấy danh sách bài hát có tìm kiếm
$query = "SELECT id, title, artist, file_path, poster FROM songs WHERE title LIKE ? OR artist LIKE ?";
$stmt = $pdo->prepare($query);
$stmt->execute(["%$search%", "%$search%"]);
$songs = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $artist = $_POST['artist'];
        $file_path = $_POST['file_path'];
        $poster = $_POST['poster'];

        $updateQuery = "UPDATE songs SET title = ?, artist = ?, file_path = ?, poster = ? WHERE id = ?";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->execute([$title, $artist, $file_path, $poster, $id]);
        
        echo "<script>alert('Cập nhật thành công!'); window.location.href='ql_songs.php';</script>";
    }

    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $deleteQuery = "DELETE FROM songs WHERE id = ?";
        $deleteStmt = $pdo->prepare($deleteQuery);
        $deleteStmt->execute([$id]);
        
        echo "<script>alert('Xóa bài hát thành công!'); window.location.href='ql_songs.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Bài Hát</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white p-6">
    <div class="max-w-4xl mx-auto bg-gray-800 p-6 rounded-lg shadow-lg">
        <h2 class="text-3xl font-bold mb-6 text-center">Quản lý bài hát</h2>
        
        <form method="GET" class="mb-6 flex">
            <input type="text" name="search" placeholder="Tìm kiếm bài hát..." 
                   class="border p-2 w-full rounded-l-lg text-black" value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-r-lg">Tìm kiếm</button>
        </form>

        <table class="w-full border-collapse border border-gray-700">
            <thead>
                <tr class="bg-gray-700">
                    <th class="border p-3">ID</th>
                    <th class="border p-3">Tiêu đề</th>
                    <th class="border p-3">Nghệ sĩ</th>
                    <th class="border p-3">File</th>
                    <th class="border p-3">Poster</th>
                    <th class="border p-3">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($songs as $song): ?>
                <tr class="border border-gray-700">
                    <form method="POST" class="flex items-center">
                        <td class="p-3 text-center"> <input type="hidden" name="id" value="<?= $song['id'] ?>"> <?= $song['id'] ?> </td>
                        <td class="p-3"> <input type="text" name="title" value="<?= htmlspecialchars($song['title']) ?>" class="border p-2 w-full rounded text-black"> </td>
                        <td class="p-3"> <input type="text" name="artist" value="<?= htmlspecialchars($song['artist']) ?>" class="border p-2 w-full rounded text-black"> </td>
                        <td class="p-3"> <input type="text" name="file_path" value="<?= htmlspecialchars($song['file_path']) ?>" class="border p-2 w-full rounded text-black"> </td>
                        <td class="p-3"> <input type="text" name="poster" value="<?= htmlspecialchars($song['poster']) ?>" class="border p-2 w-full rounded text-black"> </td>
                        <td class="p-3 text-center">
                            <button type="submit" name="update" class="bg-green-500 text-white px-4 py-2 rounded">Sửa</button>
                            <button type="submit" name="delete" class="bg-red-500 text-white px-4 py-2 rounded ml-2" onclick="return confirm('Bạn có chắc chắn muốn xóa bài hát này không?');">Xóa</button>
                        </td>
                    </form>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
