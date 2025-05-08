<?php
session_start();
require 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
  $stmt->execute([$username]);
  $admin = $stmt->fetch();

  if ($admin && password_verify($password, $admin['password'])) {
    $_SESSION['admin_id'] = $admin['id'];
    header("Location: dashboard.php");
    exit;
  } else {
    $error = "Invalid credentials.";
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Login</title>
</head>

<body>
  <h2>Admin Login</h2>
  <?php if ($error): ?>
    <p style="color:red"><?= $error ?></p>
  <?php endif; ?>
  <form method="post">
    <label>Username: <input type="text" name="username" required></label><br>
    <label>Password: <input type="password" name="password" required></label><br>
    <button type="submit">Login</button>
  </form>
</body>

</html>