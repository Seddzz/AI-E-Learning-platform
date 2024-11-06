<?php
/*
include("db_connect.php");

if (!isset($_GET['course_id']) || !isset($_GET['current_lesson_id'])) {
    echo json_encode(["error" => "Invalid parameters"]);
    exit;
}

$course_id = intval($_GET['course_id']);
$current_lesson_id = intval($_GET['current_lesson_id']);

$next_lesson_query = "SELECT * FROM lesson WHERE Id_Cours = $course_id AND Id_lesson > $current_lesson_id ORDER BY Id_lesson ASC LIMIT 1";
$next_lesson_result = $conn->query($next_lesson_query);

if ($next_lesson_result->num_rows > 0) {
    $next_lesson = $next_lesson_result->fetch_assoc();
    echo json_encode([
        "lesson_title" => htmlspecialchars($next_lesson['Titre_lesson']),
        "lesson_content" => nl2br(htmlspecialchars($next_lesson['file_lesson'])),
        "next_lesson_id" => $next_lesson['Id_lesson'],
        "has_next_lesson" => true
    ]);
} else {
    echo json_encode(["has_next_lesson" => false]);
}
?>*/
