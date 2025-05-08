<?php

$env = parse_ini_file('.env');
// Connect to DB
$host = $env['DB_HOST'] ?? 'localhost';
$db_name   = $env['DB_NAME'];
$user = $env['DB_USER'];
$pass = $env['DB_PASS'];

try {
  $pdo = new PDO(
    "mysql:host=127.0.0.1;dbname=$db_name",
    $user,
    $pass,
    [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
  );
} catch (PDOException $e) {
  die("Database connection failed: " . $e->getMessage());
}
