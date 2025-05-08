<?php
session_start();
require 'config.php';

if (empty($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit;
}

// Total submissions
$total_submissions = $pdo->query("SELECT COUNT(*) FROM submissions")->fetchColumn();

// Language stats
$stmt = $pdo->query("
    SELECT l.name, COUNT(sl.submission_id) AS count
    FROM languages l
    LEFT JOIN submission_languages sl ON l.id = sl.language_id
    GROUP BY l.id
");

$language_stats = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
  <title>Statistics</title>
</head>

<body>
  <h2>Statistics</h2>
  <p>Total Submissions: <strong><?= $total_submissions ?></strong></p>

  <h3>Language Popularity</h3>
  <ul>
    <?php foreach ($language_stats as $row): ?>
      <li><?= htmlspecialchars($row['name']) ?>: <?= $row['count'] ?></li>
    <?php endforeach; ?>
  </ul>

  <a href="dashboard.php">Back to Dashboard</a> | <a href="logout.php">Logout</a>
</body>

</html>