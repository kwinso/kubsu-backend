<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <style>
    .error {
      color: red !important;
    }

    form {
      margin: auto;
      max-width: 400px;
    }

    .clear {
      float: right;
    }
  </style>
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
</head>

<?php
$errors = json_decode($_COOKIE['errors'] ?? '[]', true);
$values = json_decode($_COOKIE['values'] ?? '[]', true);

function errorLabel($key, $errors)
{
  if (!isset($errors[$key])) {
    return;
  }
  print('<small class="error">' . $errors[$key] . '</small>');
}

function getValue($key, $values)
{
  print($values[$key] ?? '');
}
?>

<body>

  <!-- Show login and password if present in cookies -->
  <?php
  $username = $_COOKIE['username'] ?? null;
  $password = $_COOKIE['password'] ?? null;
  if ($_COOKIE[session_name()] ?? null) {
    $name = $_SESSION['name'];
    print("Рады видеть вас снова, $name");
  } else if ($username && $password) {
    print("<p>Вы можете войти с логином <strong>$username</strong> и паролем <strong>$password</strong> для изменения данных на <a href=\"/task5/login.php\">странице входа</a>.</p>");
  }
  ?>

  <form action="" method="POST">
    <div class="nice-form-group">
      <label>Ваше имя</label>
      <input
        type="text"
        placeholder="Фамилия Имя"
        name="name"
        value="<?php getValue('name', $values); ?>" />
      <?php errorLabel('name', $errors); ?>

      <label>Телефон</label>
      <input
        name="phone"
        value="<?php getValue('phone', $values); ?>"
        type="tel"
        placeholder="Введите телефон"
        class="icon-left" />
      <?php errorLabel('phone', $errors); ?>

      <label>Email</label>
      <input
        name="email"
        type="email"
        placeholder="Введите email"
        value="<?php getValue('email', $values); ?>"
        class="icon-left" />
      <?php errorLabel('email', $errors); ?>

      <label>Дата рождения</label>
      <input type="date" name="birth_date" placeholder="Дата рождения"
        value="<?php getValue('birth_date', $values); ?>" />
      <?php errorLabel('birth_date', $errors); ?>

      <label>Ваши навыки</label>
      <select name="languages[]" multiple>
        <?php
        $languages = $db->query('SELECT * FROM languages');
        foreach ($languages as $lang) {
          $selected = in_array($lang['id'], $values['languages'] ?? []) ? 'selected' : '';
          print('<option value="' . $lang['id'] . '" ' . $selected . '>' . $lang['name'] . '</option>');
        }
        ?>
      </select>
      <?php errorLabel('languages', $errors); ?>

      <label>Расскажите о себе</label>
      <textarea
        name="bio"
        placeholder="О себе"
        aria-label="Professional short bio"><?php getValue('bio', $values); ?></textarea>

      <fieldset>
        <legend>Ваш пол</legend>
        <?php
        $sex = $values['sex'] ?? null;
        ?>
        <label>
          <input type="radio" name="sex" value="1" <?php echo ($sex == 1 || $sex === null ? 'checked' : ''); ?> />
          Мужской
        </label>
        <label>
          <input type="radio" name="sex" value="0" <?php echo ($sex == 0 && $sex !== null ? 'checked' : ''); ?> />
          Женский
        </label>
      </fieldset>

      <input type="submit" value="ok" hidden />
      <button type="submit"> Отправить </button>
      <a class="clear" href="?clear=1">Очистить форму</a>
    </div>
  </form>
</body>