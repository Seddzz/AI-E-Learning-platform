<?php
include("db_connect.php");

header('Content-Type: application/json');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if course_id is set
if (!isset($_GET['course_id'])) {
    echo json_encode(['success' => false, 'error' => 'No course ID provided']);
    exit;
}

$courseId = $_GET['course_id'];

// Use a transaction to ensure both deletions succeed
$conn->begin_transaction();

try {
    // Delete from lesson table
    $query = "DELETE FROM lesson WHERE Id_Cours = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $courseId);
    $stmt->execute();
    $stmt->close();

    // Delete from cours table
    $query = "DELETE FROM cours WHERE Id_Cours = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $courseId);
    $stmt->execute();
    $stmt->close();

    // Commit transaction
    $conn->commit();

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    // Rollback transaction if any error occurs
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

// Close connection
$conn->close();
?>
