<?php
include 'db_connect.php';

$user_name = $_SESSION['user_name']; // Assuming user_name is in session
$student_query = "SELECT Id_Etudiant FROM etudiant WHERE Nom = ?";
$student_stmt = $conn->prepare($student_query);
$student_stmt->bind_param("s", $user_name);
$student_stmt->execute();
$student_result = $student_stmt->get_result();
$student = $student_result->fetch_assoc();
$student_id = $student['Id_Etudiant'];

// Fetch the latest note for the student (example logic, adjust as needed)
$fetchNoteQuery = "SELECT Note FROM note WHERE Id_Etudiant = ? ORDER BY Id_Exercice DESC LIMIT 1";
$fetchNoteStmt = $conn->prepare($fetchNoteQuery);
$fetchNoteStmt->bind_param("i", $student_id);
$fetchNoteStmt->execute();
$noteResult = $fetchNoteStmt->get_result();
$noteData = $noteResult->fetch_assoc();
$note = $noteData['Note'];

echo json_encode(['note' => $note]);
?>
