<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'scholari_db';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// JOIN users with departments to get the abbreviation
$sql = "SELECT u.user_id, u.first_name, u.last_name, u.role, d.abbreviation 
        FROM users u
        LEFT JOIN departments d ON u.department_id = d.department_id
        WHERE u.role = 'student'";

$result = $conn->query($sql);

$students = [];

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $students[] = $row;
  }
}

header('Content-Type: application/json');
echo json_encode($students);

$conn->close();
?>
