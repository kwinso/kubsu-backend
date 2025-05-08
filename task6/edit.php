<?php
session_start();
require 'config.php';

if (empty($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit;
}

$id = intval($_GET['id']);

// Get current submission
$stmt = $pdo->prepare("SELECT * FROM submissions WHERE id = ?");
$stmt->execute([$id]);
$submission = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$submission) {
  die("Submission not found.");
}

// Get languages
$languages = $pdo->query("SELECT * FROM languages")->fetchAll(PDO::FETCH_ASSOC);

// Get selected language IDs
$selected_langs = $pdo->prepare("SELECT language_id FROM submission_languages WHERE submission_id = ?");
$selected_langs->execute([$id]);
$lang_ids = array_column($selected_langs->fetchAll(PDO::FETCH_ASSOC), 'language_id');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $phone = $_POST['phone'];
  $email = $_POST['email'];
  $birth_date = $_POST['birth_date'];
  $bio = $_POST['bio'];
  $sex = intval($_POST['sex']);

  // Update submission
  $pdo->prepare("
        UPDATE submissions SET 
            name = ?, phone = ?, email = ?, birth_date = ?, bio = ?, sex = ?
        WHERE id = ?
    ")->execute([$name, $phone, $email, $birth_date, $bio, $sex, $id]);

  // Update languages
  $pdo->exec("DELETE FROM submission_languages WHERE submission_id = $id");

  if (!empty($_POST['languages'])) {
    foreach ($_POST['languages'] as $lang_id) {
      $pdo->prepare("
                INSERT INTO submission_languages (submission_id, language_id)
                VALUES (?, ?)
            ")->execute([$id, $lang_id]);
    }
  }

  header("Location: dashboard.php");
  exit;
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Edit Submission</title>
</head>

<body>
  <h2>Edit Submission #<?= $id ?></h2>

  <form method="post">
    <label>Name: <input type="text" name="name" value="<?= htmlspecialchars($submission['name']) ?>"></label><br>
    <label>Phone: <input type="text" name="phone" value="<?= htmlspecialchars($submission['phone']) ?>"></label><br>
    <label>Email: <input type="email" name="email" value="<?= htmlspecialchars($submission['email']) ?>"></label><br>
    <label>Birth Date: <input type="date" name="birth_date" value="<?= $submission['birth_date'] ?>"></label><br>
    <label>Bio: <textarea name="bio"><?= htmlspecialchars($submission['bio']) ?></textarea></label><br>
    <label>Sex:
      <select name="sex">
        <option value="0" <?= $submission['sex'] == 0 ? 'selected' : '' ?>>Male</option>
        <option value="1" <?= $submission['sex'] == 1 ? 'selected' : '' ?>>Female</option>
      </select>
    </label><br>

    <label>Languages:</label><br>
    <?php foreach ($languages as $lang): ?>
      <label>
        <input type="checkbox" name="languages[]" value="<?= $lang['id'] ?>"
          <?= in_array($lang['id'], $lang_ids) ? 'checked' : '' ?>>
        <?= htmlspecialchars($lang['name']) ?>
      </label><br>
    <?php endforeach; ?>

    <button type="submit">Save</button>
  </form>
</body>

</html>