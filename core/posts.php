<?php
$servername = getenv('DB_HOST');
$username = getenv('DB_USER');
$password = getenv('DB_PASSWORD');
$dbname = getenv('DB_TABLE_NAME');

if (!isset($_COOKIE['Username'])){
    echo "<body><div class='container'><h1> Авторизуйтесь, чтобы увдиеть статью </h1></body></div>";
} else{
    $id = $_GET['id'];
    settype($id, 'integer');
    
    $link = ("host=$servername port=5432 dbname=$dbname user=$username password=$password");
    $conn = pg_connect($link) or die('Could not connect: '.pg_last_error());

    $sql = "SELECT * FROM posts WHERE id=$id";
    $res = pg_query($conn, $sql);
    $rows = pg_fetch_array($res);
    
    $title = $rows['title'];
    $main_text = $rows['main_text'];
}
?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
</head>

<html lang="ru">
    <body>
    <div class="container">
    <?php
        echo "<h1> $title </h1>";
        echo "<p> $main_text </p>";
    ?>
    </div>
</body>
</html>