<?php
include 'db.php';

try {
    // Reset ranking về 0 cho toàn bộ bảng rank7d
    $stmt = $pdo->prepare("UPDATE rank7d SET ranking = 0");
    $stmt->execute();

    echo "<h2>✅ Đã đặt lại toàn bộ thứ hạng về 0 trong bảng rank7d.</h2>";
    echo "<a href='index_adm.php'>🔙 Quay lại trang admin</a>";
} catch (PDOException $e) {
    echo "<h2>❌ Lỗi khi reset bảng xếp hạng: " . $e->getMessage() . "</h2>";
    echo "<a href='index_adm.php'>🔙 Quay lại trang admin</a>";
}
?>