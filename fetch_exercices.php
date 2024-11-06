<?php
include("db_connect.php");

if (!isset($_GET['lesson_id'])) {
    echo "Lesson ID not provided.";
    exit;
}

$lesson_id = intval($_GET['lesson_id']);

// Fetch exercises for the lesson
$exercises_query = "SELECT * FROM exercice WHERE Id_lesson = $lesson_id";
$exercises_result = $conn->query($exercises_query);

$exercises = [];
while ($exercise = $exercises_result->fetch_assoc()) {
    // Fetch questions for each exercise
    $questions_query = "SELECT * FROM question WHERE Id_Exercice = {$exercise['Id_Exercice']}";
    $questions_result = $conn->query($questions_query);

    $questions = [];
    while ($question = $questions_result->fetch_assoc()) {
        // Fetch options for each question
        $options_query = "SELECT * FROM `option` WHERE Id_Question = {$question['Id_Question']}";
        $options_result = $conn->query($options_query);

        $options = [];
        while ($option = $options_result->fetch_assoc()) {
            $options[] = $option;
        }
        $question['options'] = $options;
        $questions[] = $question;
    }
    $exercise['questions'] = $questions;
    $exercises[] = $exercise;
}
?>