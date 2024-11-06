<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_id = intval($_POST['course-id']);
    $course_title = $_POST['course-title'];
    $lesson_count = intval($_POST['lesson-count']);

    // Vérifiez si l'ID du cours est valide
    if ($course_id <= 0) {
        die("ID de cours invalide.");
    }

    // Mettez à jour les détails du cours en utilisant une requête préparée
    $update_query = $conn->prepare("UPDATE cours SET Titre_cours = ? WHERE Id_Cours = ?");
    $update_query->bind_param("si", $course_title, $course_id);

    if ($update_query->execute()) {
        // Mettez à jour les détails des leçons
        for ($i = 1; $i <= $lesson_count; $i++) {
            $lesson_title = $_POST["lesson_title_$i"];
            $lesson_content = $_POST["lesson_content_$i"];

            // Vérifiez si la leçon existe déjà
            $check_lesson_query = $conn->prepare("SELECT Id_Lesson FROM lesson WHERE Id_Cours = ? AND Titre_lesson = ?");
            $check_lesson_query->bind_param("is", $course_id, $lesson_title);
            $check_lesson_query->execute();
            $check_lesson_query->store_result();

            if ($check_lesson_query->num_rows > 0) {
                // La leçon existe, faites une mise à jour
                $lesson_update_query = $conn->prepare("UPDATE lesson SET file_lesson = ? WHERE Id_Cours = ? AND Titre_lesson = ?");
                $lesson_update_query->bind_param("sis", $lesson_content, $course_id, $lesson_title);
                $lesson_update_query->execute();
            } else {
                // La leçon n'existe pas, insérez-la
                $lesson_insert_query = $conn->prepare("INSERT INTO lesson (Id_Cours, Titre_lesson, file_lesson) VALUES (?, ?, ?)");
                $lesson_insert_query->bind_param("iss", $course_id, $lesson_title, $lesson_content);
                $lesson_insert_query->execute();
            }
        }

        echo "Le cours a été mis à jour avec succès.";
    } else {
        echo "Erreur lors de la mise à jour du cours : " . $conn->error;
    }
}

$conn->close();
?>
