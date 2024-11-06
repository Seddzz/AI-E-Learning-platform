<?php
include 'db_connect.php';

if (!isset($_POST['lessonId']) || !isset($_POST['exerciseName'])) {
    echo "Missing parameters.";
    exit;
}

$lessonId = $_POST['lessonId'];
$exerciseName = $_POST['exerciseName'];
$exerciseId = isset($_POST['exerciseId']) ? $_POST['exerciseId'] : null;

if ($exerciseId) {
    // Update existing exercise
    $update_query = $conn->prepare("UPDATE exercice SET Nom_Exercice = ? WHERE Id_Exercice = ?");
    $update_query->bind_param('si', $exerciseName, $exerciseId);
    $update_query->execute();
} else {
    // Add new exercise
    $insert_query = $conn->prepare("INSERT INTO exercice (Nom_Exercice, Id_lesson) VALUES (?, ?)");
    $insert_query->bind_param('si', $exerciseName, $lessonId);
    $insert_query->execute();
    $exerciseId = $conn->insert_id;  // Get the last inserted ID

    for ($i = 1; $i <= 2; $i++) {
        $questionText = $_POST["question$i"];
        $insertQuestion = $conn->prepare("INSERT INTO question (Question, Id_Exercice) VALUES (?, ?)");
        $insertQuestion->bind_param('si', $questionText, $exerciseId);
        $insertQuestion->execute();
        $questionId = $insertQuestion->insert_id;  // Get the last inserted ID for the question

        $correctAnswerText = "";  // Variable to store the correct answer text

        for ($j = 1; $j <= 3; $j++) {
            $optionText = $_POST["option{$i}_$j"];
            $isCorrect = $_POST["correctOption$i"] === "option{$i}_$j" ? 1 : 0;

            if ($isCorrect) {
                $correctAnswerText = $optionText;  // Store the correct answer text
            }

            $insertOption = $conn->prepare("INSERT INTO `option` (Id_Question, Text, Is_Correct) VALUES (?, ?, ?)");
            $insertOption->bind_param('isi', $questionId, $optionText, $isCorrect);
            $insertOption->execute();
        }

        // Update the question with the correct answer
        $updateQuestion = $conn->prepare("UPDATE question SET Reponse = ? WHERE Id_Question = ?");
        $updateQuestion->bind_param('si', $correctAnswerText, $questionId);
        $updateQuestion->execute();
    }
}

echo "Exercise saved successfully.";
?>
