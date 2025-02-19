<?php
session_start();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ - Website Nghe Nhạc</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #008CBA;
            padding: 15px;
            color: white;
            border-radius: 8px;
        }

        .header h1 {
            margin: 0;
        }

        .btn {
            padding: 10px 15px;
            background: white;
            color: #008CBA;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            text-decoration: none;
        }

        .btn:hover {
            background: #007bb5;
            color: white;
        }

        .logout-btn {
            background: red;
            color: white;
        }

        .logout-btn:hover {
            background: darkred;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Website Nghe Nhạc</h1>

    <div>
        <?php if (isset($_SESSION['username'])): ?>
            <span>Xin chào, <b><?php echo $_SESSION['username']; ?></b>!</span>
            <a href="logout.php" class="btn logout-btn">Đăng xuất</a>
        <?php else: ?>
            <a href="login.html" class="btn">Đăng Nhập</a>
        <?php endif; ?>
    </div>
</div>

<h2>Chào mừng đến với trang web nghe nhạc!</h2>
<p>Chọn chức năng bạn muốn sử dụng:</p>

<!-- Các button khác -->
<a href="search.php" class="btn">Tìm kiếm nhạc</a>
<a href="ranking.php" class="btn">Xem bảng xếp hạng</a>



</body>
</html>
