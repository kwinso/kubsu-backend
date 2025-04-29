<?php

$env = parse_ini_file('.env');
$user = $env['DB_USER'];
$pass = $env['DB_PASS'];
$db_name = $env['DB_NAME'];
$db = new PDO(
    "mysql:host=127.0.0.1;dbname=$db_name",
    $user,
    $pass,
    [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);
