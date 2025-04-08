<?php
session_start();
?>
<div class="header">
    <h1>Website Nghe Nhạc</h1>
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
