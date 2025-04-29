<?php

include("db.php");

// В суперглобальном массиве $_SESSION хранятся переменные сессии.
// Будем сохранять туда логин после успешной авторизации.
$session_started = false;
if ($_COOKIE[session_name()] ?? null && session_start()) {
  $session_started = true;
  if (!empty($_SESSION['login'])) {
    header('Location: ./');
    exit();
  }
}

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
?>

  <form action="" method="post">
    <label for="login">Логин</label>
    <input name="login" />
    <label for="login">Пароль</label>
    <input name="pass" />
    <input type="submit" value="Войти" />
  </form>

<?php
}
// Иначе, если запрос был методом POST, т.е. нужно сделать авторизацию с записью логина в сессию.
else {
  // TODO: Проверть есть ли такой логин и пароль в базе данных.
  $username = $_POST['login'] ?? null;
  $password = $_POST['pass'] ?? null;
  $user = null;
  if ($username && $password) {
    // find in submissions table by username and password hash
    $passwordHash = md5($password);
    $stmt = $db->prepare("SELECT * FROM submissions WHERE username = ? AND password = ?");
    $stmt->execute([$username, $passwordHash]);
    if ($stmt->rowCount() != 1) {
      print("Неверный логин или пароль");
      print("<br>");
      print('<a href="/task5/login.php">Назад к странице входа</a>');
      exit();
    }
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
  }

  if (!$session_started) {
    session_start();
  }

  $_SESSION['username'] = $username;
  $_SESSION['name'] = $user['name'];
  $_SESSION['submission_id'] = $user['id'];

  // Делаем перенаправление.
  header('Location: /task5');
}
