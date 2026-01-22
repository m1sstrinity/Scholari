<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Tasks Assigned</title>
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    .user-info {
      display: flex;
      flex-direction: column;
      margin-left: 10px;
      text-align: left;
    }

    .user-name {
      font-weight: bold;
    }

    .user-role {
      font-size: 12px;
      color: gray;
      line-height: 1;
    }

    /* Table styling */
    .students-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      font-family: Arial, sans-serif;
    }

    .students-table th, .students-table td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: left;
    }

    .students-table th {
      background-color: #5409DA;
      color: white;
      font-weight: bold;
    }

    .students-table tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    .students-table tr:hover {
      background-color: #eaeaea;
    }
  </style>
</head>
<body>

<div class="sidebar">
  <img src="sclogo.png" alt="Logo" class="logo">

  <div class="user-dropdown">
    <button class="user-btn">
      <img src="profile.png" alt="User Image" class="user-image" />
      <div class="user-info">
        <div class="user-name">Juan</div>
        <div class="user-role">Educator</div>
      </div>
      <span class="arrow">▼</span>
    </button>
    <div class="dropdown-content">
      <a href="profile.html">Profile</a>
      <a href="settings.html">Settings</a>
    </div>
  </div>

  <div class="menu-links">
    <a href="index.html"><i class="fas fa-chart-line"></i> Dashboard</a>
    <a href="students.php"><i class="fas fa-user-graduate"></i> Students</a>
    <a href="taskassigned.php" class="active"><i class="fas fa-tasks"></i> Tasks Assigned</a>
    <a href="archivedclasses.html"><i class="fas fa-archive"></i> Archived Classes</a>
    <a href="#" id="logoutBtn"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </div>
</div>

<div class="content">
  <h1>Tasks Assigned</h1>
  <hr style="margin: 10px 0 20px; border: 1px solid #ccc;">

  <?php
  $conn = new mysqli('localhost', 'root', '', 'scholari_db');
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $sql = "SELECT * FROM tasks_assigned";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    echo "<table class='students-table'>";
    echo "<thead><tr>
            <th>ID</th>
            <th>Assigned Work</th>
            <th>Due Date</th>
            <th>Tasked</th>
            <th>Turned In</th>
          </tr></thead><tbody>";

    while($row = $result->fetch_assoc()) {
      echo "<tr>
              <td>" . $row['id'] . "</td>
              <td>" . htmlspecialchars($row['assigned_work']) . "</td>
              <td>" . htmlspecialchars($row['due_date']) . "</td>
              <td>" . htmlspecialchars($row['tasked']) . "</td>
              <td>" . htmlspecialchars($row['turned_in']) . "</td>
            </tr>";
    }

    echo "</tbody></table>";
  } else {
    echo "<p>No tasks assigned.</p>";
  }

  $conn->close();
  ?>
</div>

<script>
  document.getElementById('logoutBtn').addEventListener('click', function(e) {
    e.preventDefault();
    alert("Logging out..."); // Replace with real logout logic later
  });
  const savedPic = localStorage.getItem("profilePic");
if (savedPic) {
  const userImage = document.querySelector(".user-image");
  if (userImage) {
    userImage.src = savedPic;
  }
}

</script>

</body>
</html>
