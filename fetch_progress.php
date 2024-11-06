<?php
include "db_connect.php"; // Including the database connection file

if (isset($_GET['course_id']) && isset($_GET['student_id'])) {
    $course_id = $_GET['course_id'];
    $student_id = $_GET['student_id'];

    $progress_query = "SELECT cp.progress, c.Titre_cours, c.Id_Cours, e.Nom AS teacher_name, e.Prenom AS teacher_firstname, 
                       (SELECT COUNT(*) FROM lesson WHERE Id_Cours = c.Id_Cours) AS lesson_count
                       FROM course_progress cp
                       JOIN cours c ON cp.Id_Cours = c.Id_Cours
                       JOIN enseignant e ON c.Id_Enseignant = e.Id_Enseignant
                       LEFT JOIN lesson l ON c.Id_Cours = l.Id_Cours
                       WHERE cp.Id_Etudiant = '$student_id' AND cp.Id_Cours = '$course_id'
                       GROUP BY cp.Id_Cours";
    $progress_result = $conn->query($progress_query);

    while($progress_row = $progress_result->fetch_assoc()):
        $course_title = $progress_row['Titre_cours'];
        $teacher_name = $progress_row['teacher_firstname'] . " " . $progress_row['teacher_name'];
        $lesson_count = $progress_row['lesson_count'];
        $progress = $progress_row['progress'];
        $progress_percentage = min($progress, 100); // Cap progress at 100%
        ?>
        <div class="courses-container">
          <div class="courses-progress">
            <div class="progress" style="height: 30px;">
              <div class="progress-bar progress-bar-striped progress-bar-animated courses-color" role="progressbar" aria-valuenow="<?php echo $progress_percentage; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $progress_percentage; ?>%">
                <?php echo $progress_percentage; ?>%
              </div>
            </div>
          </div>
          <div class="courses-details">
            <p style="margin-bottom: 0px; font-size:18px;"><?php echo $course_title; ?></p>
            <p style="font-size:10px;"><?php echo $teacher_name; ?></p>
          </div>
        </div>
    <?php endwhile;
} else {
    echo "Invalid request";
}
?>
