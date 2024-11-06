<?php
include 'db_connect.php';

if (!isset($_GET['courseId'])) {
    echo "courseId parameter is missing.";
    exit;
}

$courseId = $_GET['courseId'];

$lessons_query = $conn->prepare("SELECT Id_lesson, Titre_lesson FROM lesson WHERE Id_Cours = ?");
$lessons_query->bind_param('i', $courseId);
$lessons_query->execute();
$lessons_result = $lessons_query->get_result();

while ($lesson = $lessons_result->fetch_assoc()) {
    echo '<div class="lesson-container">';

    // Fetch exercises for each lesson
    $exercises_query = $conn->prepare("SELECT Id_Exercice, Nom_Exercice FROM exercice WHERE Id_lesson = ?");
    $exercises_query->bind_param('i', $lesson['Id_lesson']);
    $exercises_query->execute();
    $exercises_result = $exercises_query->get_result();

    while ($exercise = $exercises_result->fetch_assoc()) {
        echo '<div class="exercise-block">';
        echo '<h4>' . $exercise['Nom_Exercice'] . '</h4>';
        echo '<div class="exercise-buttons">';
        echo '<button class="exercise-button edit-button" onclick="editExercise(' . $exercise['Id_Exercice'] . ')">Modifier</button>';
        echo '<button class="exercise-button delete-button" onclick="deleteExercise(' . $exercise['Id_Exercice'] . ')">Supprimer</button>';
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

    echo '</div>';
}
?>
