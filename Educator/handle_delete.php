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
    // Get JSON data
    $data = json_decode(file_get_contents('php://input'), true);
    $type = $data['type'] ?? '';
    $id = $data['id'] ?? 0;

    // Validate required fields
    if (empty($type) || empty($id)) {
        echo json_encode(['success' => false, 'message' => 'Type and ID are required']);
        exit;
    }

    // Map type to table name
    $table = '';
    switch ($type) {
        case 'announcement':
            $table = 'announcements';
            break;
        case 'assignment':
            $table = 'assignments';
            break;
        case 'material':
            $table = 'materials';
            break;
        default:
            throw new Exception("Invalid type specified");
    }

    // If it's an assignment or material, get the file path first to delete the file
    if ($type === 'assignment' || $type === 'material') {
        $sql = "SELECT image_path FROM $table WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error preparing select statement: " . $conn->error);
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $file_path = $row['image_path'];
            if (!empty($file_path) && file_exists($file_path)) {
                unlink($file_path);
            }
        }
        $stmt->close();
    }

    // Delete the record
    $sql = "DELETE FROM $table WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Error preparing delete statement: " . $conn->error);
    }

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => ucfirst($type) . ' deleted successfully']);
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