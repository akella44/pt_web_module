<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col 12 index">
            <?php
            if (!isset($_COOKIE['Username'])){
            ?>
                <h1>Авторизуйтесь!</h1>
                <a href="registration.php">Зарегиструйтесь</a> или <a href="login.php">войдите</a>!
            <?php    
            } else {?>
                <h1>Список постов</h1>
                <?php
                $servername = getenv('DB_HOST');
                $username = getenv('DB_USER');
                $password = getenv('DB_PASSWORD');
                $dbname = getenv('DB_TABLE_NAME');    
                       
                $link = ("host=$servername port=5432 dbname=$dbname user=$username password=$password");
                $conn = pg_connect($link) or die('Could not connect: '.pg_last_error());

                $sql = "SELECT * FROM posts";
                $res = pg_query($conn, $sql);

                if (pg_num_rows($res) >  0) {
                    while ($post = pg_fetch_array($res)) {
                        echo "<a href='posts.php?id=".$post["id"]."'>".$post['title']."</a><br>";
                    }
                   } else {
                        echo "Записей пока нет";
                }
            }
            ?>
            </div>
        </div>
    </div>
</body>
</html>