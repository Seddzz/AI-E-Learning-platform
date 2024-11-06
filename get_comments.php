<?php
include 'db_connect.php';

header('Content-Type: application/json');

$response = [];

if (isset($_GET['course_id'])) {
    $course_id = intval($_GET['course_id']);
    $query = "SELECT c.Id_lesson, c.Commentaire, e.Nom, e.Prenom FROM commentaire c 
              JOIN etudiant e ON c.Id_Etudiant = e.Id_Etudiant 
              WHERE c.Id_lesson IN (SELECT Id_lesson FROM lesson WHERE Id_Cours = ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $course_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $response[] = [
                'comment' => $row['Commentaire'],
                'student_name' => $row['Nom'] . ' ' . $row['Prenom'],
                'lesson_ID' => $row['Id_lesson']
            ];
        }
    } else {
        $response['error'] = 'Pas de commentaire pour ce cours.';
    }
} else {
    $response['error'] = 'Invalid course ID.';
}

echo json_encode($response);

$conn->close();
?>
