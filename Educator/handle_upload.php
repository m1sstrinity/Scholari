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

// Handle file upload
function handleFileUpload($file) {
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $new_filename = uniqid() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    // Check file size (5MB limit)
    if ($file["size"] > 5000000) {
        return ['success' => false, 'message' => "File is too large. Maximum size is 5MB."];
    }
    
    // Allow certain file formats
    $allowed_types = ['pdf', 'doc', 'docx', 'txt', 'jpg', 'jpeg', 'png'];
    if (!in_array($file_extension, $allowed_types)) {
        return ['success' => false, 'message' => "Sorry, only PDF, DOC, DOCX, TXT, JPG, JPEG & PNG files are allowed."];
    }
    
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return ['success' => true, 'path' => $target_file];
    } else {
        return ['success' => false, 'message' => "Sorry, there was an error uploading your file."];
    }
}

try {
    // Get POST data
    $type = isset($_POST['type']) ? $_POST['type'] : '';
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $instructions = isset($_POST['instructions']) ? $_POST['instructions'] : '';

    // Validate required fields
    if (empty($type) || empty($title) || empty($instructions)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit;
    }

    // Handle file upload if file is present
    $image_path = '';
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $upload_result = handleFileUpload($_FILES['file']);
        if (!$upload_result['success']) {
            echo json_encode($upload_result);
            exit;
        }
        $image_path = $upload_result['path'];
    }

    // Prepare and execute database insertion
    $table = ($type === 'assignment') ? 'assignments' : 'materials';
    $sql = "INSERT INTO $table (title, instructions, image_path, uploaded_at) VALUES (?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("sss", $title, $instructions, $image_path);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => ucfirst($type) . ' added successfully']);
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