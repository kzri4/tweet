<?php

require_once('config.php');
require_once('functions.php');

$id = $_GET['id'];

$dbh = connectDb();
$sql = 'SELECT * FROM tweets WHERE id = :id';
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();

$tweets = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tweets){
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD']=='POST'){
    $content = $_POST['content'];
    $errors = [];

    if ($content == '') {
        $errors['content'] = 'ツイート内容を入力して下さい';
    }

    if ($tweets['content'] == $content) {
        $errors['content'] = '内容が変更されていません。';
    }

    if (!$errors) {
        $dbh = connectDb();
        $sql = 'UPDATE tweets SET 
                content = :content,
                created_at = CURRENT_TIMESTAMP 
                WHERE id = :id';
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':content', $content, PDO::PARAM_STR);
        $stmt->execute();

        header('Location: index.php');
        exit;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>編集画面</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>tweetの編集</h1>
    <div>
        <a href="index.php">戻る</a>
    </div>

        <?php if ($errors) : ?>
        <ul class="error-list">
            <?php foreach ($errors as $error) : ?>
                <li>
                    <?= h($error) ?>
                </li>
                <?php endforeach;?>
        </ul>
        <?php endif; ?>

    <form action="" method="post">
        <div>
            <label for="content">ツイート内容</label><br>
            <textarea name="content" cols="30" rows="5"><?= h($tweets['content']) ?></textarea>
        </div>
        <div>
            <input type="submit" value="編集する">
        </div>
    </form>
</body>
</html>