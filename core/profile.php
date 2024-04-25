<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
</head>

<html lang="ru">
    <body>
    <div class="container">
        <div class="row">
            <div class="col">
                <h1>Привет, <?php echo $_COOKIE['Username']?></h1>
            </div>
        </div>
    </div>
    <div class="container">
        <h2>Создать пост</h2>
        <form method="POST" action="profile.php">
            <div class="form-group">
                <label for="title">Заголовок:</label>
                <input type="text" id="title" class="form-control" name="title" placeholder="Введите заголовок">
            </div>
            <div class="form-group">
                <label for="text">Текст поста:</label>
                <textarea id="text" class="form-control" name="text" rows="6" placeholder="Введите текст поста"></textarea>
            </div>
            <div class="form-group">
                <label for="file">Выберите файл:</label>
                <input type="file" id="file" name="file">
            </div>
            <button type="submit" class="btn_red" name="submit">Сохранить пост</button>
        </form>
    </div>
    </body>
</html>

<?php
require_once("db.php");

if (!isset($_COOKIE['Username'])) {
    header("Location: index.php");
}

$servername = getenv('DB_HOST');
$username = getenv('DB_USER');
$password = getenv('DB_PASSWORD');
$dbname = getenv('DB_TABLE_NAME');

$link = ("host=$servername port=5432 dbname=$dbname user=$username password=$password");
$conn = pg_connect($link) or die('Could not connect: '. pg_last_error());

if (isset($_POST['submit'])){
    $title = $_POST['title'];
    $main_text = $_POST['text'];

    if(!$title || !$main_text) die("Заполните все поля");
    $sql = "INSERT INTO posts (title, main_text) VALUES ('$title', '$main_text')";
    if(!pg_query($conn, $sql)) die("Не удалось добавить пост ".pg_last_error());
}

if(!empty($_FILES["file"])){
    if (((@$_FILES["file"]["type"] == "image/gif") || (@$_FILES["file"]["type"] == "image/jpeg")
    || (@$_FILES["file"]["type"] == "image/jpg") || (@$_FILES["file"]["type"] == "image/pjpeg")
    || (@$_FILES["file"]["type"] == "image/x-png") || (@$_FILES["file"]["type"] == "image/png"))
    && (@$_FILES["file"]["size"] < 102400))
    {
        move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $_FILES["file"]["name"]);
        echo "Load in:  " . "upload/" . $_FILES["file"]["name"];
    }
    else
    {
        echo "upload failed!";
    }
}
?>