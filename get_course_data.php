<?php
session_start();
include 'db_connect.php';

$student_id = $_SESSION['user_id']; // Ensure the user ID is stored in the session during login
$course_id = $_POST['course_id'];

// Check if the course is already in progress for this student
$query = "SELECT * FROM course_progress WHERE Id_Cours = ? AND Id_Etudiant = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $course_id, $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // Insert new course progress
    $insert_query = "INSERT INTO course_progress (Id_Cours, Id_Etudiant, progress) VALUES (?, ?, 0)";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param("ii", $course_id, $student_id);

    if ($insert_stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to insert']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Course already in progress']);
}
?>
