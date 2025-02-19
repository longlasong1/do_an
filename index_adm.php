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
    
    <a href="login_adm.php" class="btn">Đăng Xuất</a>
</div>

<h2>Chào mừng đến với trang web nghe nhạc!</h2>
<p>Chào admin bạn muốn làm gì:</p>

<!-- Các button khác -->

<a href="delete_song.php" class="btn">xóa nhạc</a>
<a href="upload.php" class="btn">Tải nhạc lên</a>
</body>
</html>