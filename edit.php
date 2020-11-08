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
    $content = $_POST['content']
    $errors = [];

    if ($content == '') {
        $errors['content'] = 'ツイート内容が未入力です';
    }

    if (!$errors) {
        $dbh = connectDb();
        $sql = 'UPDATE tweets SET content = :content = :content WHERE id = :id';
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
    <?php if ($errors):?>
        <ul class= "error-List">
            <?php foreach ($errors as $error) : ?>
                <li>
                    <?= h($error) ?>
                    </li>
            <?php endforeach; ?>
        </ul>
    <form action="" method="post">
        <div>
            <label for="body">ツイート内容</label><br>
            <textarea name="body" cols="30" rows="5"><?= h($tweets['content']) ?></textarea>
        </div>
        <div>
            <input type="submit" value="編集する" placeholder="テストツイート">
        </div>
    </form>
</body>
</html>