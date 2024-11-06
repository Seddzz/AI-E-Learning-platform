<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connect.php';

if (isset($_GET['lessonId'])) {
    $lesson_id = intval($_GET['lessonId']);

    $stmt = $conn->prepare("SELECT e.Nom as user_name, c.Commentaire as comment FROM commentaire c JOIN etudiant e ON c.Id_Etudiant = e.Id_Etudiant WHERE c.Id_lesson = ? ORDER BY c.Id_Commentaire DESC");
    if (!$stmt) {
        echo json_encode(['error' => 'Prepare failed: ' . $conn->error]);
        exit;
    }
    $stmt->bind_param("i", $lesson_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        echo json_encode(['error' => 'Execute failed: ' . $stmt->error]);
        exit;
    }

    $comments = [];
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }

    echo json_encode($comments);
    $stmt->close();
} else {
    echo json_encode(['error' => 'Lesson ID not provided.']);
}
$conn->close();
?>
