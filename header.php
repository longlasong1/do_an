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
<div class="header">
    <h1>Website Nghe Nhạc</h1>
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
