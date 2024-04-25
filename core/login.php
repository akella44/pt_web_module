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
            <div class="col 12">
                <h1>Авторизация</h1>
            </div>
        </div>

        <div class="row">
            <div class="col 12">
                <form method="POST" action="login.php">
                    <div class="row form_auth"><input class="form" type="email" name="email" placeholder="Электронная почта"></div>
                    <div class="row form_auth"><input class="form" type="password" name="password" placeholder="Пароль"></div>
                    <button type="submit" class="btn__auth" name="submit">Войти</button>
                </form>
            </div>
    </div>
    </body>
</html>

<?php
require_once('db.php');

if (isset($_COOKIE['Username'])) {
    header("Location: profile.php");
}

$servername = getenv('DB_HOST');
$username = getenv('DB_USER');
$password = getenv('DB_PASSWORD');
$dbname = getenv('DB_TABLE_NAME');

$link = ("host=$servername port=5432 dbname=$dbname user=$username password=$password");
$conn = pg_connect($link) or die('Could not connect: '.pg_last_error());

if(isset($_POST['submit'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    if(!$username || !$password) die("Пожалуйста введите все значения");

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = pg_query($conn, $sql);
    $info = pg_fetch_array($result);

    if(pg_num_rows($result)==1){
        setcookie("Username", $info["username"], time()+7200);
        header('Location: profile.php');
    } else{
        echo "Неправильно имя или пароль";
    }
}
?>