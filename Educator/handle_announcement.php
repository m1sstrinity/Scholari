<?php
header('Content-Type: application/json');

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "scholari_db";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => "Connection failed: " . $conn->connect_error]);
    exit;
}

try {
    // Get POST data
    $content = isset($_POST['content']) ? trim($_POST['content']) : '';

    // Validate required fields
    if (empty($content)) {
        echo json_encode(['success' => false, 'message' => 'Announcement content is required']);
        exit;
    }

    // Prepare and execute database insertion
    $sql = "INSERT INTO announcements (content, posted_at) VALUES (?, NOW())";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("s", $content);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Announcement added successfully']);
    } else {
        throw new Exception("Error executing statement: " . $stmt->error);
    }

    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    $conn->close();
}
?>