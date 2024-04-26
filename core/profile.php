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
        <form method="POST" action="profile.php" enctype="multipart/form-data" name="upload">
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

if(!empty($_FILES["file"])){
    $errors = [];
    $allowedtypes = ['image/gif', 'image/jpeg', 'image/jpg', 'image/pjpeg', 'image/x-png', 'image/png'];
    $maxFileSize = 102400;

    $realFileSize = filesize($_FILES['file']['tmp_name']);
    if ($realFileSize > $maxFileSize) {
        $errors[] = 'Файл слишком большой.';
    }

    $fileType = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $_FILES['file']['tmp_name']);
    if (!in_array($fileType, $allowedTypes)) {
        $errors[] = 'Недопустимый тип файла.';
    }

    if (empty($errors)) {
        $tempPath = $_FILES['file']['tmp_name'];
        $destinationPath = 'upload/' . uniqid() . '_' . basename($_FILES['file']['name']);
        if (move_uploaded_file($tempPath, $destinationPath)) {
            echo "Файл загружен успешно: " . $destinationPath;
        } else {
            $errors[] = 'Не удалось переместить загруженный файл.';
        }
    } else {
        foreach ($errors as $error) {
            echo $error . '<br>';
        }
    }
}

if (isset($_POST['submit'])){
    $title = $_POST['title'];
    $main_text = $_POST['text'];

    $title = strip_tags($_POST['title']);
    $main_text = strip_tags($_POST['text']);

    if(!$title || !$main_text) die("Заполните все поля");

    $title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
    $main_text = htmlspecialchars($main_text, ENT_QUOTES, 'UTF-8');

    $sql = "INSERT INTO posts (title, main_text) VALUES ('$title', '$main_text')";
    if(!pg_query($conn, $sql)) die("Не удалось добавить пост ".pg_last_error());
}

?>