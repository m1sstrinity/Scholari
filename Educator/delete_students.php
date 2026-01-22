<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_ids'])) {
  $conn = new mysqli('localhost', 'root', '', 'scholari_db');

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $ids = $_POST['delete_ids'];
  $in = str_repeat('?,', count($ids) - 1) . '?';
  $stmt = $conn->prepare("DELETE FROM users WHERE user_id IN ($in)");

  $types = str_repeat('i', count($ids));
  $stmt->bind_param($types, ...$ids);

  $stmt->execute();
  $stmt->close();
  $conn->close();
}

header("Location: students.php");
exit;
?>
