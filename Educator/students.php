<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Students</title>
  <link rel="stylesheet" href="styles.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
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
    .remove-btn {
      background-color: #e53935;
      color: white;
      border: none;
      padding: 8px 16px;
      cursor: pointer;
      border-radius: 4px;
      margin-bottom: 10px;
    }
    .remove-btn:hover {
      background-color: #c62828;
    }
    .add-btn {
      font-size: 28px;
      color: #5409DA;
      background: none;
      border: none;
      cursor: pointer;
    }
    .students-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      font-family: Arial, sans-serif;
    }
    .students-table thead {
      background-color: #5409DA;
      color: white;
    }
    .students-table th, .students-table td {
      padding: 12px 15px;
      border: 1px solid #ddd;
      text-align: left;
    }
    .students-table th:first-child, .students-table td:first-child {
      text-align: center;
      width: 50px;
    }
    .students-table tbody tr:hover {
      background-color: #f1f1f1;
    }
    /* Modal Styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 99;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.4);
      justify-content: center;
      align-items: center;
    }
    .modal-content {
      background-color: white;
      padding: 20px;
      border-radius: 8px;
      width: 400px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    .modal-content h2 {
      margin-top: 0;
    }
    .modal-content input,
    .modal-content select {
      width: 100%;
      padding: 8px;
      margin-bottom: 12px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    .modal-buttons {
      display: flex;
      justify-content: flex-end;
    }
    .modal-buttons button {
      margin-left: 10px;
      padding: 8px 14px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    .save-btn {
      background-color: #5409DA;
      color: white;
    }
    .cancel-btn {
      background-color: #aaa;
      color: white;
    }
  </style>
</head>
<body>

<div class="sidebar">
  <img src="sclogo.png" alt="Logo" class="logo" />

  <div class="user-dropdown">
    <button class="user-btn">
      <img id="sidebarProfilePic" src="profile.png" alt="User Image" class="user-image" />
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
    <a href="students.php" class="active"><i class="fas fa-user-graduate"></i> Students</a>
    <a href="taskassigned.php"><i class="fas fa-tasks"></i> Tasks Assigned</a>
    <a href="archivedclasses.html"><i class="fas fa-archive"></i> Archived Classes</a>
    <a href="#" id="logoutBtn"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </div>
</div>

<div class="content">
  <div style="display: flex; align-items: center; justify-content: space-between;">
    <h1>Students</h1>
    <button id="addStudentBtn" class="add-btn" title="Add Student">
      <i class="fas fa-plus"></i>
    </button>
  </div>

  <hr style="margin: 10px 0 20px; border: 1px solid #ccc;" />

  <div style="display: flex; align-items: center; margin-bottom: 20px;">
    <form method="GET" style="display: flex; align-items: center;">
      <input
        type="text"
        name="search"
        placeholder="Search student..."
        value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
        style="padding: 8px; width: 250px; border: 1px solid #ccc; border-radius: 4px 0 0 4px;"
      />
      <button
        type="submit"
        style="padding: 8px 12px; border: none; background-color: #5409DA; color: white; border-radius: 0 4px 4px 0;"
      >
        <i class="fas fa-search"></i>
      </button>
    </form>
  </div>

  <form method="post" action="delete_students.php" onsubmit="return confirm('Remove selected students?');">
    <button type="submit" class="remove-btn">Remove Selected</button>

    <?php
    $conn = new mysqli('localhost', 'root', '', 'scholari_db');
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
    $sql = "SELECT u.user_id, u.first_name, u.last_name, d.abbreviation AS department
            FROM users u
            LEFT JOIN departments d ON u.department_id = d.department_id
            WHERE u.role = 'student'";

    if (!empty($search)) {
      $sql .= " AND (u.first_name LIKE '%$search%' OR u.last_name LIKE '%$search%')";
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

      while ($row = $result->fetch_assoc()) {
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
  </form>
</div>

<!-- Add Student Modal -->
<div id="studentModal" class="modal">
  <div class="modal-content">
    <h2>Add Student</h2>
    <form method="post" action="add_student.php">
      <input type="text" name="first_name" placeholder="First Name" required />
      <input type="text" name="last_name" placeholder="Last Name" required />
      <select name="department_id" required>
        <option value="">Select Department</option>
        <?php
        $conn = new mysqli('localhost', 'root', '', 'scholari_db');
        $departments = $conn->query("SELECT department_id, abbreviation FROM departments");
        while ($dep = $departments->fetch_assoc()) {
          echo "<option value='{$dep['department_id']}'>{$dep['abbreviation']}</option>";
        }
        $conn->close();
        ?>
      </select>
      <div class="modal-buttons">
        <button type="button" class="cancel-btn" onclick="document.getElementById('studentModal').style.display='none'">
          Cancel
        </button>
        <button type="submit" class="save-btn">Save</button>
      </div>
    </form>
  </div>
</div>

<script>
  document.getElementById('addStudentBtn').onclick = function () {
    document.getElementById('studentModal').style.display = 'flex';
  };

  document.getElementById('checkAll').addEventListener('click', function () {
    const checkboxes = document.querySelectorAll('input[type="checkbox"][name="delete_ids[]"]');
    checkboxes.forEach(cb => (cb.checked = this.checked));
  });

  const savedPic = localStorage.getItem('profilePic');
  if (savedPic) {
    document.getElementById('sidebarProfilePic').src = savedPic;
  }

  window.onclick = function (e) {
    const modal = document.getElementById('studentModal');
    if (e.target == modal) {
      modal.style.display = 'none';
    }
  };
</script>

</body>
</html>
