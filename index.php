<?php

require_once('config.php');
require_once('functions.php');

$dbh = connectDb();

$sql = 'SELECT * FROM tweets';
$stmt = $dbh->prepare($sql);
$stmt->execute();

$tweets = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = $_POST['content'];
    $errors = [];

    if ($content == '') {
        $errors['content'] = 'ツイート内容を入力してください。';
    }

    if(!$errors){
        $sql = 'INSERT INTO tweets (content) VALUES (:content)';

        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':content', $content, PDO::PARAM_STR);
        $stmt->execute();

        header('Location: index.php');
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Tweet</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>新規Tweet</h1>

    <?php if ($errors) : ?>
        <ul class="error-list">
            ツイート内容を入力してください。
        </ul>
    <?php endif; ?>

    <form action="" method="post">
        <div>
            <label for="content">ツイート内容</label><br>
            <textarea name="tweet" cols="30" rows="5"placeholder="いまどうしてる？"></textarea>
        </div>
        <div>
            <input type="submit" value="投稿する" >
        </div>
    </form>

    <h2>Tweet一覧</h2>
    <?php if ($tweets) : ?>
        <ul class = "tweet-list">
            <?php foreach ($tweets as $tweet) : ?>
                <ul>
                    <?= h($tweet['content']) ?><br>
                    投稿日時:<?=h($tweet['created_at']) ?>
                    
                    <hr>
                </ul>
            <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <div>投稿されたtweetはありません</div>
    <?php endif; ?>
</body>
</html>