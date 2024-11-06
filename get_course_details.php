<?php
include 'db_connect.php';

header('Content-Type: application/json'); // Ensure the response is JSON

$response = [];

if (isset($_GET['id'])) {
    $course_id = intval($_GET['id']);
    $query = "SELECT * FROM cours WHERE Id_Cours = $course_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $course = $result->fetch_assoc();

        // Assuming there's a lessons table or column in the courses table
        $lessons_query = "SELECT * FROM lesson WHERE Id_Cours = $course_id";
        $lessons_result = $conn->query($lessons_query);
        $lessons = [];
        while ($lesson = $lessons_result->fetch_assoc()) {
            $lessons[] = [
                'title' => $lesson['Titre_lesson'],
                'content' => $lesson['file_lesson']
            ];
        }
        $course['lessons'] = $lessons;

        $response = $course;
    } else {
        $response = ['error' => 'Course not found'];
    }
} else {
    $response = ['error' => 'Invalid course ID'];
}

echo json_encode($response);

$conn->close();
?>
