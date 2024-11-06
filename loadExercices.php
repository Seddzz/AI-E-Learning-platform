<?php
include 'db_connect.php';

if (!isset($_GET['lessonId'])) {
    echo "lessonId parameter is missing.";
    exit;
}

$lessonId = $_GET['lessonId'];

$exercises = $conn->prepare("SELECT Id_Exercice, Nom_Exercice FROM exercice WHERE Id_lesson = ?");
$exercises->bind_param('i', $lessonId);
$exercises->execute();
$exercises_result = $exercises->get_result();

while ($exercise = $exercises_result->fetch_assoc()) {
    echo '<div class="exercise-block">';
    echo '<div class="exercise-buttons">';
    echo '<button class="exercise-edit-button" onclick="editExercise(' . $exercise['Id_Exercice'] . ')" style="
    margin: 5px;
    border-color:#607CB1;
    border-radius: 5px;
    background-color: #607CB1;
">Modifier</button>';
    echo '<button class="exercise-delete-button" onclick="deleteExercise(' . $exercise['Id_Exercice'] . ')" style="
    margin: 5px;
    border-color:#607CB1;
    border-radius: 5px;
    background-color: #607CB1;
">Supprimer</button>';
    echo '</div>';

    // Fetch questions for each exercise
    $questions_query = "SELECT Id_Question, Question FROM question WHERE Id_Exercice = ?";
    $questions_stmt = $conn->prepare($questions_query);
    $questions_stmt->bind_param('i', $exercise['Id_Exercice']);
    $questions_stmt->execute();
    $questions_result = $questions_stmt->get_result();

    while ($question = $questions_result->fetch_assoc()) {
        echo '<div class="question-block">';
        echo '<h4>' . $question['Question'] . '</h4>';

        // Fetch options for each question
        $options_query = "SELECT Id_Option, Text, Is_Correct FROM `option` WHERE Id_Question = ?";
        $options_stmt = $conn->prepare($options_query);
        $options_stmt->bind_param('i', $question['Id_Question']);
        $options_stmt->execute();
        $options_result = $options_stmt->get_result();

        while ($option = $options_result->fetch_assoc()) {
            echo '<div class="option-block">';
            
            // Ajouter la condition pour cocher l'input radio si Is_Correct = 1
            $checked = $option['Is_Correct'] == 1 ? 'checked' : '';
            
            echo '<input type="radio" name="q' . $question['Id_Question'] . '" value="' . $option['Id_Option'] . '" ' . $checked . '>';
            echo '<label>' . $option['Text'] . '</label>';
            echo '</div>';
        }

        echo '</div>';
    }

    echo '</div>';
}
?>
