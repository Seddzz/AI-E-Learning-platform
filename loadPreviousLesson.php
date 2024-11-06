<?php
include 'db_connect.php';

if (!isset($_GET['courseId']) || !isset($_GET['currentLessonId'])) {
    echo "Missing parameters.";
    exit;
}

$courseId = $_GET['courseId'];
$currentLessonId = $_GET['currentLessonId'];

// Fetch the previous lesson for the course
$prev_query = $conn->prepare("SELECT Id_lesson, Titre_lesson FROM lesson WHERE Id_Cours = ? AND Id_lesson < ? ORDER BY Id_lesson DESC LIMIT 1");
$prev_query->bind_param('ii', $courseId, $currentLessonId);
$prev_query->execute();
$prev_result = $prev_query->get_result();

if ($prev_lesson = $prev_result->fetch_assoc()) {
    $prevLessonId = $prev_lesson['Id_lesson'];

    // Check if there's a previous lesson before this one
    $check_prev_query = $conn->prepare("SELECT Id_lesson FROM lesson WHERE Id_Cours = ? AND Id_lesson < ? ORDER BY Id_lesson DESC LIMIT 1");
    $check_prev_query->bind_param('ii', $courseId, $prevLessonId);
    $check_prev_query->execute();
    $check_prev_result = $check_prev_query->get_result();
    $prev_exists = $check_prev_result->num_rows > 0;

    echo '<div class="lesson-container">';
    echo '<h3 class="lesson-title">' . $prev_lesson['Titre_lesson'] . '</h3>';
    echo '<form>';

    // Add a button to open the modal for adding a new exercise
    echo '<button class="btn btn-primary" type="button" onclick="openExerciseModal(' . $prevLessonId . ')">Ajouter un exercice</button>';

    // Fetch exercises for the previous lesson
    $exercises_query = $conn->prepare("SELECT Id_Exercice, Nom_Exercice FROM exercice WHERE Id_lesson = ?");
    $exercises_query->bind_param('i', $prevLessonId);
    $exercises_query->execute();
    $exercises_result = $exercises_query->get_result();

    while ($exercise = $exercises_result->fetch_assoc()) {
        echo '<div class="exercise-block">';
        echo '<h4>' . $exercise['Nom_Exercice'] . '</h4>';
        echo '<div class="exercise-buttons">';
        echo '<button class="exercise-button edit-button" type="button" onclick="openExerciseModal(' . $prevLessonId . ', ' . $exercise['Id_Exercice'] . ')">Modifier</button>';
        echo '<button class="exercise-button delete-button" type="button" onclick="deleteExercise(' . $exercise['Id_Exercice'] . ')">Supprimer</button>';
        echo '</div>';

        // Fetch questions for each exercise
        $questions_query = $conn->prepare("SELECT Id_Question, Question FROM question WHERE Id_Exercice = ?");
        $questions_query->bind_param('i', $exercise['Id_Exercice']);
        $questions_query->execute();
        $questions_result = $questions_query->get_result();

        while ($question = $questions_result->fetch_assoc()) {
            echo '<div class="question-block">';
            echo '<h5>' . $question['Question'] . '</h5>';

            // Fetch options for each question
            $options_query = $conn->prepare("SELECT Id_Option, Text, Is_Correct FROM `option` WHERE Id_Question = ?");
            $options_query->bind_param('i', $question['Id_Question']);
            $options_query->execute();
            $options_result = $options_query->get_result();

            while ($option = $options_result->fetch_assoc()) {
                echo '<div class="option-block">';
                echo '<input type="radio" name="q' . $question['Id_Question'] . '" value="' . $option['Id_Option'] . '">';
                echo '<label>' . $option['Text'] . '</label>';
                echo '</div>';
            }

            echo '</div>';
        }

        echo '</div>';
    }

    if ($prev_exists) {
        echo '<button class="btn btn-primary" type="button" onclick="loadPreviousLesson(' . $courseId . ', ' . $prevLessonId . ')">Lesson precedente</button>';
    }
    echo '</form>';
    echo '</div>';
} else {
    echo 'No next lesson found.';
}
?>