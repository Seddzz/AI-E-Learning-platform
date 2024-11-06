<?php
include "db_connect.php"; // Including the database connection file

if (isset($_POST['course_id']) && isset($_POST['student_id'])) {
    $course_id = intval($_POST['course_id']);
    $student_id = intval($_POST['student_id']);

    // Delete notes related to the course for the student
    $delete_notes_query = "DELETE note FROM note 
                           JOIN exercice ON note.Id_Exercice = exercice.Id_Exercice
                           JOIN lesson ON exercice.Id_Lesson = lesson.Id_Lesson
                           WHERE lesson.Id_Cours = $course_id AND note.Id_Etudiant = $student_id";

    if ($conn->query($delete_notes_query) === TRUE) {
        // Delete progress related to the course for the student
        $delete_progress_query = "DELETE FROM course_progress WHERE Id_Cours = $course_id AND Id_Etudiant = $student_id";
        if ($conn->query($delete_progress_query) === TRUE) {
            echo 'success';
        } else {
            echo "Error deleting progress: " . $conn->error;
        }
    } else {
        echo "Error deleting notes: " . $conn->error;
    }
} else {
    echo "Invalid request";
}
?>
