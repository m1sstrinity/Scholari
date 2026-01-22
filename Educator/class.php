<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "scholari_db";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$class = isset($_GET['class']) ? urldecode($_GET['class']) : 'Unknown Class';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">
  <title><?php echo htmlspecialchars($class); ?> - Class Page</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="class.css?v=<?php echo time(); ?>">
</head>
<body>

<button class="back-btn" onclick="window.location.href='index.html'">← Back to Dashboard</button>

<h1>
  <?php echo htmlspecialchars($class); ?>
  <div class="add-button">
    <i class="fas fa-plus" onclick="toggleDropdown(event)"></i>
    <div id="myDropdown" class="dropdown-content">
      <a href="#" onclick="openModal('assignmentModal')">Assignments</a>
      <a href="#" onclick="openModal('materialModal')">Materials</a>
      <a href="#" onclick="openModal('announcementModal')">Announcement</a>
    </div>
  </div>
</h1>

<!-- Announcement Modal -->
<div id="announcementModal" class="modal">
  <div class="modal-content">
    <h2>Add New Announcement</h2>
    <form id="announcementForm" method="post">
      <div>
        <label for="announcementContent">Announcement:</label>
        <textarea id="announcementContent" name="content" rows="4" required></textarea>
      </div>
      <div class="modal-buttons">
        <button type="button" class="cancel-btn" onclick="closeModal('announcementModal')">Cancel</button>
        <button type="submit" class="submit-btn">Submit</button>
      </div>
    </form>
  </div>
</div>

<!-- Assignment Modal -->
<div id="assignmentModal" class="modal">
  <div class="modal-content">
    <h2>Add New Assignment</h2>
    <form id="assignmentForm" method="post" enctype="multipart/form-data">
      <div>
        <label for="assignmentTitle">Title:</label>
        <input type="text" id="assignmentTitle" name="title" required>
      </div>
      <div>
        <label for="assignmentInstructions">Instructions:</label>
        <textarea id="assignmentInstructions" name="instructions" rows="4" required></textarea>
      </div>
      <div>
        <label for="assignmentFile">File (optional):</label>
        <input type="file" id="assignmentFile" name="file">
      </div>
      <div class="modal-buttons">
        <button type="button" class="cancel-btn" onclick="closeModal('assignmentModal')">Cancel</button>
        <button type="submit" class="submit-btn">Submit</button>
      </div>
    </form>
  </div>
</div>

<!-- Material Modal -->
<div id="materialModal" class="modal">
  <div class="modal-content">
    <h2>Add New Material</h2>
    <form id="materialForm" method="post" enctype="multipart/form-data">
      <div>
        <label for="materialTitle">Title:</label>
        <input type="text" id="materialTitle" name="title" required>
      </div>
      <div>
        <label for="materialInstructions">Instructions:</label>
        <textarea id="materialInstructions" name="instructions" rows="4" required></textarea>
      </div>
      <div>
        <label for="materialFile">File (optional):</label>
        <input type="file" id="materialFile" name="file">
      </div>
      <div class="modal-buttons">
        <button type="button" class="cancel-btn" onclick="closeModal('materialModal')">Cancel</button>
        <button type="submit" class="submit-btn">Submit</button>
      </div>
    </form>
  </div>
</div>

<hr style="margin: 10px 0 20px; border: 1px solid #ccc;" />

<section>
  <h2>Announcements</h2>
  <?php
  $sql = "SELECT * FROM announcements";
  $result = $conn->query($sql);
  if ($result && $result->num_rows > 0): ?>
    <table>
      <tr><th>Content</th><th>Date Posted</th><th class="action-column">Action</th></tr>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?php echo htmlspecialchars($row['content']); ?></td>
          <td><?php echo htmlspecialchars($row['posted_at']); ?></td>
          <td>
            <button class="delete-btn" onclick="deleteItem('announcement', <?php echo $row['id']; ?>)">Delete</button>
          </td>
        </tr>
      <?php endwhile; ?>
    </table>
  <?php else: ?>
    <p>No announcements.</p>
  <?php endif; ?>
</section>

<section>
  <h2>Assignments</h2>
  <?php
  $sql = "SELECT * FROM assignments ORDER BY uploaded_at DESC";
  $result = $conn->query($sql);
  if ($result && $result->num_rows > 0): ?>
    <table>
      <tr><th>Title</th><th>Description</th><th>Attachment</th><th class="action-column">Action</th></tr>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?php echo htmlspecialchars($row['title']); ?></td>
          <td><?php echo htmlspecialchars($row['instructions']); ?></td>
          <td>
            <?php 
              $file_path = $row['image_path'];
              if (!empty($file_path)) {
                $file_extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
                if (in_array($file_extension, ['jpg', 'jpeg', 'png'])) {
                  echo '<a href="' . htmlspecialchars($file_path) . '" target="_blank">';
                  echo '<img src="' . htmlspecialchars($file_path) . '" alt="Preview" style="max-width: 100px; max-height: 100px;">';
                  echo '</a>';
                } elseif ($file_extension === 'pdf') {
                  echo '<div class="pdf-actions">';
                  echo '<i class="fas fa-file-pdf" style="color: #dc3545; font-size: 24px;"></i>';
                  echo '<div class="pdf-buttons">';
                  echo '<a href="' . htmlspecialchars($file_path) . '" target="_blank" class="btn-view">View</a>';
                  echo '<a href="' . htmlspecialchars($file_path) . '" download class="btn-download">Download</a>';
                  echo '</div></div>';
                } else {
                  echo '<a href="' . htmlspecialchars($file_path) . '" download>';
                  echo '<i class="fas fa-download"></i> Download File';
                  echo '</a>';
                }
              } else {
                echo 'No file attached';
              }
            ?>
          </td>
          <td>
            <button class="delete-btn" onclick="deleteItem('assignment', <?php echo $row['id']; ?>)">Delete</button>
          </td>
        </tr>
      <?php endwhile; ?>
    </table>
  <?php else: ?>
    <p>No assignments.</p>
  <?php endif; ?>
</section>

<section>
  <h2>Materials</h2>
  <?php
  $sql = "SELECT * FROM materials ORDER BY uploaded_at DESC";
  $result = $conn->query($sql);
  if ($result && $result->num_rows > 0): ?>
    <table>
      <tr><th>Title</th><th>Description</th><th>Attachment</th><th class="action-column">Action</th></tr>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?php echo htmlspecialchars($row['title']); ?></td>
          <td><?php echo htmlspecialchars($row['instructions']); ?></td>
          <td>
            <?php 
              $file_path = $row['image_path'];
              if (!empty($file_path)) {
                $file_extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
                if (in_array($file_extension, ['jpg', 'jpeg', 'png'])) {
                  echo '<a href="' . htmlspecialchars($file_path) . '" target="_blank">';
                  echo '<img src="' . htmlspecialchars($file_path) . '" alt="Preview" style="max-width: 100px; max-height: 100px;">';
                  echo '</a>';
                } elseif ($file_extension === 'pdf') {
                  echo '<div class="pdf-actions">';
                  echo '<i class="fas fa-file-pdf" style="color: #dc3545; font-size: 24px;"></i>';
                  echo '<div class="pdf-buttons">';
                  echo '<a href="' . htmlspecialchars($file_path) . '" target="_blank" class="btn-view">View</a>';
                  echo '<a href="' . htmlspecialchars($file_path) . '" download class="btn-download">Download</a>';
                  echo '</div></div>';
                } else {
                  echo '<a href="' . htmlspecialchars($file_path) . '" download>';
                  echo '<i class="fas fa-download"></i> Download File';
                  echo '</a>';
                }
              } else {
                echo 'No file attached';
              }
            ?>
          </td>
          <td>
            <button class="delete-btn" onclick="deleteItem('material', <?php echo $row['id']; ?>)">Delete</button>
          </td>
        </tr>
      <?php endwhile; ?>
    </table>
  <?php else: ?>
    <p>No materials.</p>
  <?php endif; 
  $conn->close();
  ?>
</section>

<script>
// Close the dropdown if clicked outside
document.addEventListener('click', function(event) {
  const dropdown = document.getElementById("myDropdown");
  const plusIcon = document.querySelector('.fa-plus');
  
  if (!event.target.matches('.fa-plus')) {
    if (dropdown.classList.contains('show')) {
      dropdown.classList.remove('show');
    }
  }
  
  if (event.target.classList.contains('modal')) {
    event.target.style.display = "none";
  }
});

function toggleDropdown(event) {
  event.stopPropagation();
  document.getElementById("myDropdown").classList.toggle("show");
}

function openModal(modalId) {
  document.getElementById(modalId).style.display = "block";
  document.getElementById("myDropdown").classList.remove('show');
}

function closeModal(modalId) {
  document.getElementById(modalId).style.display = "none";
}

// Handle form submissions
document.getElementById('assignmentForm').onsubmit = function(e) {
  e.preventDefault();
  submitForm('assignmentForm', 'assignment');
};

document.getElementById('materialForm').onsubmit = function(e) {
  e.preventDefault();
  submitForm('materialForm', 'material');
};

document.getElementById('announcementForm').onsubmit = function(e) {
  e.preventDefault();
  submitAnnouncement();
};

async function submitForm(formId, type) {
  try {
    const form = document.getElementById(formId);
    const formData = new FormData(form);
    formData.append('type', type);

    const response = await fetch('handle_upload.php', {
      method: 'POST',
      body: formData
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const result = await response.json();
    
    if (result.success) {
      alert(result.message);
      form.reset();
      closeModal(type + 'Modal');
      location.reload();
    } else {
      alert('Error: ' + result.message);
    }
  } catch (error) {
    console.error('Error:', error);
    alert('Error submitting form: ' + error.message);
  }
}

async function submitAnnouncement() {
  try {
    const form = document.getElementById('announcementForm');
    const formData = new FormData(form);

    const response = await fetch('handle_announcement.php', {
      method: 'POST',
      body: formData
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const result = await response.json();
    
    if (result.success) {
      alert(result.message);
      form.reset();
      closeModal('announcementModal');
      location.reload();
    } else {
      alert('Error: ' + result.message);
    }
  } catch (error) {
    console.error('Error:', error);
    alert('Error submitting announcement: ' + error.message);
  }
}

async function deleteItem(type, id) {
  if (!confirm('Are you sure you want to delete this ' + type + '?')) {
    return;
  }

  try {
    const response = await fetch('handle_delete.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ type, id })
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const result = await response.json();
    
    if (result.success) {
      alert(result.message);
      location.reload();
    } else {
      alert('Error: ' + result.message);
    }
  } catch (error) {
    console.error('Error:', error);
    alert('Error deleting item: ' + error.message);
  }
}

// File upload preview
document.querySelectorAll('input[type="file"]').forEach(input => {
  input.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
      const fileType = file.type;
      const validImageTypes = ['image/jpeg', 'image/png'];
      const previewDiv = document.createElement('div');
      previewDiv.className = 'file-preview';
      
      if (validImageTypes.includes(fileType)) {
        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        previewDiv.appendChild(img);
      } else if (fileType === 'application/pdf') {
        const icon = document.createElement('i');
        icon.className = 'fas fa-file-pdf pdf-icon';
        previewDiv.appendChild(icon);
      } else {
        const icon = document.createElement('i');
        icon.className = 'fas fa-file';
        previewDiv.appendChild(icon);
      }
      
      const fileName = document.createElement('span');
      fileName.textContent = file.name;
      previewDiv.appendChild(fileName);
      
      const container = input.closest('.form-group');
      const existingPreview = container.querySelector('.file-preview');
      if (existingPreview) {
        container.removeChild(existingPreview);
      }
      container.appendChild(previewDiv);
    }
  });
});
</script>

</body>
</html>
