

<?php
include("db_connect.php");

if (!isset($_SESSION['user_name'])) {
    header("Location: index.php");
    exit;
}

$courseTitle = $_POST['course-title'];
$lessonCount = $_POST['lesson-count'];
$courseImage = file_get_contents($_FILES['course-image']['tmp_name']);
$user_name = $_SESSION['user_name'];

// Fetch the Id_Enseignant for the current user
$query = "SELECT Id_Enseignant FROM enseignant WHERE Nom = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $user_name);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$id_enseignant = $row['Id_Enseignant'];

// Insert the new course into the cours table
$query = "INSERT INTO cours (Titre_cours, Id_Enseignant, Course_Image) VALUES (?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param('sis', $courseTitle, $id_enseignant, $courseImage);
$stmt->execute();
$courseId = $stmt->insert_id;

// Insert each lesson into the lesson table
for ($i = 1; $i <= $lessonCount; $i++) {
    $lessonTitle = $_POST["lesson_title_" . $i];
    $lessonContent = $_POST["lesson_content_" . $i];

    $query = "INSERT INTO lesson (Titre_lesson, file_lesson, Id_Cours) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssi', $lessonTitle, $lessonContent, $courseId);
    $stmt->execute();
}


// Execute the Python script after the course and lessons are added
$pythonScript = 'exercices.py';
exec("python3 $pythonScript");
//rediriger vers enseignant.php
header("Location: enseignant.php");
exit;
?>