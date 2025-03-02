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

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  if (!empty($_GET['save'])) {
    print('Спасибо, результаты сохранены.');
    print('<a href="/task3">Назад</a>');
    exit();
  }

  include('form.php');
  exit();
}


function notEmpty($msg)
{
  return function ($value) use ($msg) {
    if ($value == null) {
      return $msg;
    }
    return null;
  };
}

function exists($db, $table, $colum, $msg)
{
  return function ($value) use ($db, $table, $colum, $msg) {
    $stmt = $db->prepare("SELECT $colum FROM $table WHERE $colum = ?");
    $stmt->execute([$value]);
    if ($stmt->rowCount() == 0) {
      return $msg;
    }
    return null;
  };
}

function validateArray($validators)
{
  return function ($value) use ($validators) {
    $errors = [];
    foreach ($validators as $validator) {
      foreach ($value as $key => $val) {
        $error = $validator($val);
        if ($error !== null) {
          $errors[] = strval($key) . ': ' . $error;
        }
      }
    }
    if (count($errors) > 0) {
      return implode('<br/>', $errors);
    }
    return null;
  };
}

$validations = [
  'name' => [
    notEmpty('Имя не может быть пустым'),
    fn($val) => strlen($val) > 50 ? 'Имя должно быть не более 50 символов.' : null,
    fn($val) => preg_match('/^[a-zA-Z ]+$/', $val) ? null : 'Имя должно состоять только из букв и пробелов.',
  ],
  'phone' => [
    notEmpty('Номер телефона не может быть пустым.'),
    fn($val) => !preg_match('/^\d{10}$/', $val) ? 'Номер телефона должен состоять из 10 цифр' : null,
  ],
  'email' => [
    notEmpty('Электронная почта не может быть пустой.'),
    fn($val) => !filter_var($val, FILTER_VALIDATE_EMAIL, FILTER_NULL_ON_FAILURE) ? 'Неверный формат электронной почты' : null,
  ],
  'birth_date' => [
    notEmpty('Дата рождения не может быть пустой.'),
    fn($val) => DateTime::createFromFormat("Y-m-d", $val) === false ? 'Неверный формат даты рождения' : null,
  ],
  'bio' => [
    notEmpty('Описание не может быть пустым.'),
    fn($val) => strlen($val) > 200 ? 'Описание должно быть не более 200 символов.' : null,
  ],
  'sex' => [
    notEmpty('Пол не может быть пустым.'),
    fn($val) => $val != 0 && $val != 1 ? 'Пол должен быть целым булевым значением' : null,
  ],
  'languages' => [
    notEmpty('Список языков не может быть пустым.'),
    validateArray([
      notEmpty('Язык не может быть пустым.'),
      exists($db, 'languages', 'id', 'Язык не существует.'),
    ]),
  ],
];

$errors = FALSE;
$data = [
  'name' => $_POST['name'] ?? null,
  'phone' => $_POST['phone'] ?? null,
  'email' => $_POST['email'] ?? null,
  'birth_date' => $_POST['birth_date'] ?? null,
  'bio' => $_POST['bio'] ?? null,
  'sex' => $_POST['sex'] ?? null,
  'languages' => $_POST['languages'] ?? null
];

foreach ($data as $key => $val) {
  $validators = $validations[$key] ?? [];
  foreach ($validators as $validator) {
    $error = $validator($val);
    if ($error !== null) {
      print($error . '<br/>');
      $errors = TRUE;

      // Early return, not checking the rest of the validators
      break;
    }
  }
}

if ($errors) {
  print('<br/><a href="/task3">Назад</a>');
  exit();
}


function insert($db, $table, $data)
{
  $columns = implode(', ', array_keys($data));
  $labels = [];
  foreach ($data as $key => $_) {
    $labels[] = ":$key";
  }
  $labels = implode(', ', $labels);

  $stmt = $db->prepare("INSERT INTO $table ($columns) VALUES ($labels)");
  try {
    $stmt->execute($data);
  } catch (PDOException $e) {
    print('Error : ' . $e->getMessage());
    exit();
  }
  return $db->lastInsertId();
}

$languages = $data['languages'];

unset($data['languages']);

$submissionId = insert($db, 'submissions', $data);

foreach ($languages as $lang) {
  insert($db, 'submission_languages', ['submission_id' => $submissionId, 'language_id' => $lang]);
}

// Redirect back
header('Location: ?save=1');
