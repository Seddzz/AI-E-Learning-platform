<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connect.php';

// Fetch user details from session
$user_name = $_SESSION['user_name']; // Assuming user_name is in session

// Get student ID from the session username
$student_query = $conn->prepare("SELECT Id_Etudiant FROM etudiant WHERE Nom = ?");
$student_query->bind_param("s", $user_name);
$student_query->execute();
$student_result = $student_query->get_result();
if (!$student_result) {
    echo json_encode(['error' => 'Error fetching student ID: ' . $conn->error]);
    exit;
}
$student = $student_result->fetch_assoc();
$student_id = $student['Id_Etudiant'];
$student_query->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lesson_id = intval($_POST['lessonId']);
    $comment = trim($_POST['comment']);

    if (!empty($lesson_id) && !empty($comment)) {
        $stmt = $conn->prepare("INSERT INTO commentaire (Id_lesson, Id_Etudiant, Commentaire) VALUES (?, ?, ?)");
        if (!$stmt) {
            echo json_encode(['error' => 'Prepare failed: ' . $conn->error]);
            exit;
        }
        $stmt->bind_param("iis", $lesson_id, $student_id, $comment);

        if ($stmt->execute()) {
            echo json_encode(['user_name' => $user_name, 'comment' => $comment]);
        } else {
            echo json_encode(['error' => 'Execute failed: ' . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['error' => 'Invalid input.']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method.']);
}
$conn->close();
?>
