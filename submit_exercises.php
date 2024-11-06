<?php
include("db_connect.php");

$studentId = $_SESSION['user_name']; // Assuming you have student ID stored in session
$responses = $_POST;

$responseData = [];

foreach ($responses as $response) {
    $questionId = intval(explode('_', $response['name'])[1]);
    $selectedOptionId = intval($response['value']);

    // Get the correct answer from the database
    $query = "SELECT Reponse FROM question WHERE Id_Question = $questionId";
    $result = $conn->query($query);
    $correctAnswer = $result->fetch_assoc()['Reponse'];

    // Store the student's response
    $insertQuery = "INSERT INTO reponse_etudiant (Id_Question, Reponse, Id_Etudiant) VALUES ($questionId, '$selectedOptionId', $studentId)";
    $conn->query($insertQuery);

    // Compare the student's response to the correct answer
    $responseData[$questionId] = ($selectedOptionId == $correctAnswer);
}

echo json_encode($responseData);
?>
