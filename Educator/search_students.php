<?php
$conn = new mysqli('localhost', 'root', '', 'scholari_db');
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$department_filter = isset($_GET['department_filter']) ? $conn->real_escape_string($_GET['department_filter']) : '';

$sql = "SELECT u.user_id, u.first_name, u.last_name, d.abbreviation AS department
        FROM users u
        LEFT JOIN departments d ON u.department_id = d.department_id
        WHERE u.role = 'student'";

if (!empty($search)) {
  $sql .= " AND (u.first_name LIKE '%$search%' OR u.last_name LIKE '%$search%')";
}

if (!empty($department_filter)) {
  $sql .= " AND u.department_id = '$department_filter'";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
  echo "<table class='students-table'>";
  echo "<thead><tr>
          <th><input type='checkbox' id='checkAll'></th>
          <th>ID</th>
          <th>First Name</th>
          <th>Last Name</th>
          <th>Department</th>
        </tr></thead><tbody>";

  while($row = $result->fetch_assoc()) {
    echo "<tr>
            <td><input type='checkbox' name='delete_ids[]' value='{$row['user_id']}'></td>
            <td>{$row['user_id']}</td>
            <td>" . htmlspecialchars($row['first_name']) . "</td>
            <td>" . htmlspecialchars($row['last_name']) . "</td>
            <td>" . htmlspecialchars($row['department']) . "</td>
          </tr>";
  }

  echo "</tbody></table>";
} else {
  echo "<p>No students found.</p>";
}

$conn->close();
?>
