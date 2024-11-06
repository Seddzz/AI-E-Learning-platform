<?php
include 'db_connect.php';

if (!isset($_GET['exerciseId'])) {
    echo json_encode(["error" => "exerciseId parameter is missing."]);
    exit;
}

$exerciseId = $_GET['exerciseId'];

$exercise_query = $conn->prepare("SELECT Id_Exercice, Nom_Exercice FROM exercice WHERE Id_Exercice = ?");
$exercise_query->bind_param('i', $exerciseId);
$exercise_query->execute();
$exercise_result = $exercise_query->get_result();

if ($exercise = $exercise_result->fetch_assoc()) {
    echo json_encode($exercise);
} else {
    echo json_encode(["error" => "Exercise not found."]);
}
?>
