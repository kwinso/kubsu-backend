<?php
session_start();
require 'config.php';

if (empty($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit;
}

// Handle delete
if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);
  $pdo->beginTransaction();

  try {
    $pdo->exec("DELETE FROM submission_languages WHERE submission_id = $id");
    $pdo->exec("DELETE FROM submissions WHERE id = $id");
    $pdo->commit();
  } catch (Exception $e) {
    $pdo->rollBack();
    die("Error deleting submission.");
  }

  header("Location: dashboard.php");
  exit;
}

// Fetch all submissions
$stmt = $pdo->query("SELECT * FROM submissions ORDER BY created_at DESC");
$submissions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
  <title>Dashboard</title>
</head>

<body>
  <h2>All Submissions</h2>
  <a href="stats.php">View Stats</a> | <a href="logout.php">Logout</a>
  <table border="1" cellpadding="10">
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Actions</th>
    </tr>
    <?php foreach ($submissions as $s): ?>
      <tr>
        <td><?= $s['id'] ?></td>
        <td><?= htmlspecialchars($s['name']) ?></td>
        <td><?= htmlspecialchars($s['email']) ?></td>
        <td>
          <a href="edit.php?id=<?= $s['id'] ?>">Edit</a> |
          <a href="?delete=<?= $s['id'] ?>" onclick="return confirm('Delete?')">Delete</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</body>

</html>