<?php
$servername = getenv('DB_HOST');
$username = getenv('DB_USER');
$password = getenv('DB_PASSWORD');
$dbname = getenv('DB_TABLE_NAME');

$link = ("host=$servername port=5432 dbname=$dbname user=$username password=$password");
$conn = pg_connect($link) or die('Could not connect: '.pg_last_error());
$sql = "CREATE TABLE IF NOT EXISTS users(
    id SERIAL PRIMARY KEY,
    username VARCHAR(15) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(20) NOT NULL
)";
$result = pg_query($conn, $sql) or die('Query failed: '.pg_last_error());
$sql = "CREATE TABLE IF NOT EXISTS posts(
    id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    main_text TEXT NOT NULL
)";
$result = pg_query($conn, $sql) or die('Query failed: '.pg_last_error());
pg_close($conn);
?>