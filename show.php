<?php

require_once('config.php');
require_once('functions.php');

$id = $_GET['id'];

$dbh = connectDb();
$sql = 'SELECT * FROM tweets WHERE id = :id ';
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();

$tweets = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tweets) {
    header('Location: index.php');
    exit;
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tweet</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1><?= h($tweets['content']) ?></h1>
    <a href="index.php">戻る</a>
    <ul class="tweet-list">
        <li>
            [#<?= h($tweets['id']) ?>]
            <?= h($tweets['content']) ?><br>
            投稿日時: <?= h($tweets['created_at']) ?>
            <?php if ($tweet['good']) : ?>
                <a href="good.php?id=<?= h($tweet['id']) ?>&good=0" class="good-list">★</a>
            <?php else : ?>
                <a href="good.php?id=<?= h($tweet['id']) ?>&good=1" class="good-list">☆</a>
            <?php endif; ?>
            <a href="edit.php?id=<?= h($tweet['id']) ?>">[編集]</a>
            <a href="delete.php?id=<?= h($tweet['id']) ?>">[削除]</a>
            <hr>
        </li>
    </ul>
</body>
</html>