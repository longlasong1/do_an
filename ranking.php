<?php
include 'includes/db.php';
$stmt = $pdo->prepare("SELECT songs.title, rankings.ranking FROM songs JOIN rankings ON songs.id = rankings.song_id ORDER BY rankings.ranking ASC");
$stmt->execute();
$rankings = $stmt->fetchAll();
?>

<ul>
    <?php foreach ($rankings as $ranking): ?>
        <li><?php echo $ranking['title'] . " - Rank: " . $ranking['ranking']; ?></li>
    <?php endforeach; ?>
</ul>
