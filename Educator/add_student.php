<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "scholari_db";

// Connect to DB
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get form data
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$department_id = $_POST['department_id'];

// Insert into users table with role = 'student'
$sql = "INSERT INTO users (first_name, last_name, department_id, role) 
        VALUES (?, ?, ?, 'Student')";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $first_name, $last_name, $department_id);

if ($stmt->execute()) {
  header("Location: students.php");
  exit();
} else {
  echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
