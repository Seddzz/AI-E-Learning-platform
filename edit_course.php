<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $course_id = intval($_GET['id']);
    $query = "SELECT * FROM cours WHERE Id_Cours = $course_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $course = $result->fetch_assoc();

        // Assuming there's a lessons table or column in the courses table
        $lessons_query = "SELECT * FROM lesson WHERE Id_Cours = $course_id";
        $lessons_result = $conn->query($lessons_query);
        $lessons = [];
        while ($lesson = $lessons_result->fetch_assoc()) {
            $lessons[] = [
                'title' => $lesson['Titre_lesson'],
                'content' => $lesson['file_lesson']
            ];
        }
    } else {
        echo "Course not found.";
        exit;
    }
} else {
    echo "Invalid course ID.";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Course</title>
</head>
<body>
    <h2>Edit Course</h2>
    <form method="post" action="update_course.php">
        <input type="text" name="course_id" value="<?php echo $course['Id_Cours']; ?>">
        <label for="course-title">Course Title</label>
        <input type="text" id="course-title" name="course-title" value="<?php echo $course['Titre_cours']; ?>" required>

        <label for="lesson-count">Number of Lessons</label>
        <input type="number" id="lesson-count" name="lesson-count" value="<?php echo count($lessons); ?>" required>

        <div id="dynamicFieldsContainer">
            <?php foreach ($lessons as $index => $lesson) { ?>
                <input type="text" name="lesson_title_<?php echo $index + 1; ?>" value="<?php echo $lesson['title']; ?>" required>
                <textarea name="lesson_content_<?php echo $index + 1; ?>" required><?php echo $lesson['content']; ?></textarea>
            <?php } ?>
        </div>

        <button type="submit">Update Course</button>
    </form>
</body>
</html>
