<?php
//queste info in production sono da nascondere
    $db_name = "nowreview";
    $host = "localhost";
    $user = "webapp_user";
    $password = "password";

    $pdo = new pdo("mysql:host=$host;dbname=$db_name", $user, $password);
?>