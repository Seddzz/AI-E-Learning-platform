<?php
include 'db_connect.php';

if (!isset($_GET['courseId'])) {
    echo "courseId parameter is missing.";
    exit;
}

$courseId = $_GET['courseId'];

// Fetch the first lesson for the course
$lesson_query = $conn->prepare("SELECT Id_lesson, Titre_lesson FROM lesson WHERE Id_Cours = ? ORDER BY Id_lesson ASC LIMIT 1");
$lesson_query->bind_param('i', $courseId);
$lesson_query->execute();
$lesson_result = $lesson_query->get_result();

if ($lesson = $lesson_result->fetch_assoc()) {
    $lessonId = $lesson['Id_lesson'];

    // Check if there's a next lesson
    $next_query = $conn->prepare("SELECT Id_lesson FROM lesson WHERE Id_Cours = ? AND Id_lesson > ? ORDER BY Id_lesson ASC LIMIT 1");
    $next_query->bind_param('ii', $courseId, $lessonId);
    $next_query->execute();
    $next_result = $next_query->get_result();
    $next_exists = $next_result->num_rows > 0;

    // Check if there's a previous lesson
    $prev_query = $conn->prepare("SELECT Id_lesson FROM lesson WHERE Id_Cours = ? AND Id_lesson < ? ORDER BY Id_lesson DESC LIMIT 1");
    $prev_query->bind_param('ii', $courseId, $lessonId);
    $prev_query->execute();
    $prev_result = $prev_query->get_result();
    $prev_exists = $prev_result->num_rows > 0;

    echo '<div class="lesson-container">';
    echo '<h3 class="lesson-title">' . $lesson['Titre_lesson'] . '</h3>';
    echo '<form>';

    // Add a button to open the modal for adding a new exercise
    echo '<button class="btn btn-primary" type="button" onclick="openExerciseModal(' . $lessonId . ')">Ajouter un exercice</button>';

    // Fetch exercises for the lesson
    $exercises_query = $conn->prepare("SELECT Id_Exercice, Nom_Exercice FROM exercice WHERE Id_lesson = ?");
    $exercises_query->bind_param('i', $lessonId);
    $exercises_query->execute();
    $exercises_result = $exercises_query->get_result();

    while ($exercise = $exercises_result->fetch_assoc()) {
        echo '<div class="exercise-block">';
        echo '<h4>' . $exercise['Nom_Exercice'] . '</h4>';
        echo '<div class="exercise-buttons">';
        echo '<button class="exercise-button edit-button" type="button" onclick="openExerciseModal(' . $lessonId . ', ' . $exercise['Id_Exercice'] . ')">Modifier</button>';
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
        echo '<button class="btn btn-primary" type="button" onclick="loadPreviousLesson(' . $courseId . ', ' . $lessonId . ')">Lesson precedente</button>';
    }
    if ($next_exists) {
        echo '<button class="btn btn-primary" type="button" onclick="loadNextLesson(' . $courseId . ', ' . $lessonId . ')">Lesson suivante</button>';
    }
    echo '</form>';
    echo '</div>';
} else {
    echo 'No lessons found for this course.';
}
?>
