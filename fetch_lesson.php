<?php
include("db_connect.php");

if (!isset($_GET['lesson_id'])) {
    echo json_encode(['error' => 'Lesson ID not provided.']);
    exit;
}

$lesson_id = intval($_GET['lesson_id']);

// Fetch lesson details
$lesson_query = "SELECT * FROM lesson WHERE Id_lesson = $lesson_id";
$lesson_result = $conn->query($lesson_query);
if ($lesson_result->num_rows > 0) {
    $lesson = $lesson_result->fetch_assoc();
} else {
    echo json_encode(['error' => 'Lesson not found.']);
    exit;
}

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

$response = [
    'lesson_content' => nl2br($lesson['file_lesson']),
    'exercise_content' => generateExerciseHTML($exercises)
];

echo json_encode($response);

function generateExerciseHTML($exercises) {
    $html = '<h2>Exercises</h2><form id="exercise-form">';
    foreach ($exercises as $exercise) {
        $html .= '<div class="exercise-block">';
        $html .= '<h3>' . $exercise['Nom_Exercice'] . '</h3>';
        foreach ($exercise['questions'] as $question) {
            $html .= '<div class="form-group">';
            $html .= '<label>' . $question['Question'] . '</label>';
            foreach ($question['options'] as $option) {
                $html .= '<div class="form-check">';
                $html .= '<input type="radio" class="form-check-input" name="q' . $question['Id_Question'] . '" value="' . $option['Id_Option'] . '">';
                $html .= '<label class="form-check-label">' . $option['Text'] . '</label>';
                $html .= '</div>';
            }
            $html .= '</div>';
        }
        $html .= '</div>';
    }
    $html .= '<button type="submit" class="btn btn-primary">Submit Exercises</button></form>';
    return $html;
}
?>
