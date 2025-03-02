<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <style>
    form {
      margin: auto;
      max-width: 400px;
    }
  </style>
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
</head>

<body>
  <form action="" method="POST">
    <div class="nice-form-group">
      <label>Ваше имя</label>
      <input type="text" placeholder="Фамилия Имя" name="name" />
      <small>Только буквы и пробелы, не более 50 символов</small>

      <label>Телефон</label>
      <input
        name="phone"
        type="tel"
        placeholder="Введите телефон"
        class="icon-left" />
      <small>Телефон должен состоять из 10 цифр</small>

      <label>Email</label>
      <input
        name="email"
        type="email"
        placeholder="Введите email"
        class="icon-left" />

      <label>Дата рождения</label>
      <input type="date" name="birth_date" placeholder="Дата рождения" />

      <label>Ваши навыки</label>
      <select name="languages[]" multiple>
        <?php
        $languages = $db->query('SELECT * FROM languages');
        foreach ($languages as $lang) {
          print('<option value="' . $lang['id'] . '">' . $lang['name'] . '</option>');
        }
        ?>
      </select>

      <label>Расскажите о себе</label>
      <textarea
        name="bio"
        placeholder="О себе"
        aria-label="Professional short bio">
      </textarea>

      <fieldset>
        <legend>Ваш пол</legend>
        <label>
          <input type="radio" name="sex" value="1" checked />
          Мужской
        </label>
        <label>
          <input type="radio" name="sex" value="0" />
          Женский
        </label>
      </fieldset>

      <input type="submit" value="ok" hidden />
      <button type="submit"> Отправить </button>
    </div>
  </form>
</body>