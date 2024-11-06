<?php
include "db_connect.php"; // Including the database connection file

if (isset($_GET['course_id']) && isset($_GET['student_id'])) {
    $course_id = intval($_GET['course_id']);
    $student_id = intval($_GET['student_id']);

    // Check if progress entry already exists
    $check_query = "SELECT * FROM course_progress WHERE Id_Cours = $course_id AND Id_Etudiant = $student_id";
    $check_result = $conn->query($check_query);

    if ($check_result->num_rows > 0) {
        // Update progress
        $sql = "UPDATE course_progress SET progress = progress WHERE Id_Cours = $course_id AND Id_Etudiant = $student_id";
    } else {
        // Insert new progress
        $sql = "INSERT INTO course_progress (Id_Cours, Id_Etudiant, progress) VALUES ($course_id, $student_id, 10)";
    }

    if ($conn->query($sql) === TRUE) {
        echo "Progress updated successfully";
    } else {
        error_log("Error updating progress: " . $conn->error);
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Invalid request";
}
?>
