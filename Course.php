<?php
include 'db_connect.php';
header("Cache-Control: no-cache, must-revalidate"); // HTTP 1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

// Check if course_id and lesson_id are provided in the URL
if (!isset($_GET['course_id']) || !isset($_GET['lesson_id'])) {
    echo "Course ID or Lesson ID not provided.";
    exit;
}

$course_id = intval($_GET['course_id']);
$lesson_id = intval($_GET['lesson_id']);

$course_query = $conn->prepare("SELECT Titre_cours FROM cours WHERE Id_Cours = ?");
$course_query->bind_param("i", $course_id);
$course_query->execute();
$course_result = $course_query->get_result();
if ($course_result->num_rows > 0) {
    $course = $course_result->fetch_assoc();
}

// Prepare the lesson query
$lesson_query = $conn->prepare("SELECT * FROM lesson WHERE Id_Cours = ? AND Id_lesson = ?");
$lesson_query->bind_param("ii", $course_id, $lesson_id);
$lesson_query->execute();
$lesson_result = $lesson_query->get_result();

if ($lesson_result->num_rows > 0) {
    $lesson = $lesson_result->fetch_assoc();
} else {
    echo "No lessons found for this course.";
    exit;
}

// Fetch user details from session
$user_name = $_SESSION['user_name']; // Assuming user_name is in session

$student_query = $conn->prepare("SELECT Id_Etudiant FROM etudiant WHERE Nom = ?");
$student_query->bind_param("s", $user_name);
$student_query->execute();
$student_result = $student_query->get_result();
$student = $student_result->fetch_assoc();
$student_id = $student['Id_Etudiant'];
// Check if the student has a note for this lesson
$note_query = $conn->prepare("SELECT note.Id_Note FROM note
                              JOIN exercice ON note.Id_Exercice = exercice.Id_Exercice
                              JOIN lesson ON exercice.Id_Lesson = lesson.Id_Lesson
                              WHERE note.Id_Etudiant = ? AND lesson.Id_Lesson = ?");
$note_query->bind_param("ii", $student_id, $lesson_id);
$note_query->execute();
$note_result = $note_query->get_result();
$has_note = $note_result->num_rows > 0;

// Fetch the total number of lessons in the course
$total_lessons_query = $conn->prepare("SELECT COUNT(*) as total_lessons FROM lesson WHERE Id_Cours = ?");
$total_lessons_query->bind_param("i", $course_id);
$total_lessons_query->execute();
$total_lessons_result = $total_lessons_query->get_result();
$total_lessons = $total_lessons_result->fetch_assoc()['total_lessons'];
$total_lessons_query->close();

// Fetch the number of notes the student has in the course
$notes_count_query = $conn->prepare("SELECT COUNT(note.Id_Note) as notes_count FROM note
                                     JOIN exercice ON note.Id_Exercice = exercice.Id_Exercice
                                     JOIN lesson ON exercice.Id_Lesson = lesson.Id_Lesson
                                     WHERE note.Id_Etudiant = ? AND lesson.Id_Cours = ?");
$notes_count_query->bind_param("ii", $student_id, $course_id);
$notes_count_query->execute();
$notes_count_result = $notes_count_query->get_result();
$notes_count = $notes_count_result->fetch_assoc()['notes_count'];
$notes_count_query->close();

// Calculate the progress
$progress = ($notes_count / $total_lessons) * 100;
if($progress==0){
    $progress=10;
}

// Update the progress table
$update_progress_query = $conn->prepare("INSERT INTO course_progress (Id_Cours, Id_Etudiant, progress) VALUES (?, ?, ?)
                                         ON DUPLICATE KEY UPDATE progress = VALUES(progress)");
$update_progress_query->bind_param("iii", $course_id, $student_id, $progress);
if (!$update_progress_query->execute()) {
    echo "Error updating progress: " . $update_progress_query->error;
    exit;
}
$update_progress_query->close();





// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $answers = $_POST['answers']; // assuming answers is an associative array with question ids as keys
    $total_questions = count($answers);
    $correct_answers = 0;

    

    foreach ($answers as $question_id => $student_answer) {
        // Fetch the correct answer option ID
        $query = $conn->prepare("SELECT Reponse FROM question WHERE Id_Question = ?");
        $query->bind_param("i", $question_id);
        $query->execute();
        $result = $query->get_result();
        $correct_answer = $result->fetch_assoc()['Reponse'];
        $query->close();

        // Fetch the string value of the student's selected option
        $option_query = $conn->prepare("SELECT Text FROM `option` WHERE Id_Option = ?");
        $option_query->bind_param("i", $student_answer);
        $option_query->execute();
        $option_result = $option_query->get_result();
        $student_answer_text = $option_result->fetch_assoc()['Text'];
        $option_query->close();

        // Store the student's response as the option string
        $insert_query = $conn->prepare("INSERT INTO reponse_etudiant (Id_Question, Reponse, Id_Etudiant) VALUES (?, ?, ?)
                        ON DUPLICATE KEY UPDATE Reponse = ?");
        $insert_query->bind_param("isss", $question_id, $student_answer_text, $student_id, $student_answer_text);
        if (!$insert_query->execute()) {
            echo "Error inserting response: " . $insert_query->error;
            exit;
        }

        // Compare the student's response to the correct answer (using IDs)
        if ($student_answer_text == $correct_answer) {
            $correct_answers++;
        }
    }

    // Calculate the score
    $score = $correct_answers;

    // Insert the note into the database, considering Id_Exercice
    foreach ($answers as $question_id => $student_answer) {
        // Fetch the exercise ID for each question
        $exercise_query = $conn->prepare("SELECT Id_Exercice FROM question WHERE Id_Question = ?");
        $exercise_query->bind_param("i", $question_id);
        $exercise_query->execute();
        $exercise_result = $exercise_query->get_result();
        $exercise_id = $exercise_result->fetch_assoc()['Id_Exercice'];
        $exercise_query->close();

        // Insert the note into the note table
        $insert_note_query = $conn->prepare("INSERT INTO note (Id_Etudiant, Id_Exercice, Note) VALUES (?, ?, ?)
                            ON DUPLICATE KEY UPDATE Note = VALUES(Note)");
        $insert_note_query->bind_param("iii", $student_id, $exercise_id, $score);
        if (!$insert_note_query->execute()) {
            echo "Error inserting note: " . $insert_note_query->error;
            exit;
        }
        $insert_note_query->close();
    }

    $response_data = [];

    // Populate response data with the correct answers
    foreach ($answers as $question_id => $student_answer) {
        $query = $conn->prepare("SELECT Reponse FROM question WHERE Id_Question = ?");
        $query->bind_param("i", $question_id);
        $query->execute();
        $result = $query->get_result();
        $correct_answer = $result->fetch_assoc()['Reponse'];
        $response_data[$question_id] = $correct_answer;
        $query->close();
    }

    // Prepare data for the Python script
    $data = [
        'lesson_id' => $lesson_id,
        'student_id' => $student_id,
        'answers' => $answers
    ];
    file_put_contents('feedback_data.json', json_encode($data));


    // Execute the Python script and capture the output
    $command = escapeshellcmd('python3 feedback.py');
    $output = shell_exec($command);
    $feedback = trim($output);
    $feedback = mb_convert_encoding($feedback, 'UTF-8', 'UTF-8');

// Encode special characters to avoid display issues
$feedback = htmlspecialchars($feedback, ENT_QUOTES, 'UTF-8');

    // Display the feedback

}

// Fetch exercises
$exercises_query = $conn->prepare("SELECT * FROM exercice WHERE Id_Lesson = ?");
$exercises_query->bind_param("i", $lesson['Id_lesson']);
$exercises_query->execute();
$exercises_result = $exercises_query->get_result();

$exercises = [];
while ($exercise = $exercises_result->fetch_assoc()) {
    $questions_query = $conn->prepare("SELECT * FROM question WHERE Id_Exercice = ?");
    $questions_query->bind_param("i", $exercise['Id_Exercice']);
    $questions_query->execute();
    $questions_result = $questions_query->get_result();

    $questions = [];
    while ($question = $questions_result->fetch_assoc()) {
        $options_query = $conn->prepare("SELECT * FROM `option` WHERE Id_Question = ?");
        $options_query->bind_param("i", $question['Id_Question']);
        $options_query->execute();
        $options_result = $options_query->get_result();

        $options = [];
        while ($option = $options_result->fetch_assoc()) {
            $options[] = $option;
        }
        $question['options'] = $options;
        $questions[] = $question;

        $options_query->close();
    }
    $exercise['questions'] = $questions;
    $exercises[] = $exercise;

    $questions_query->close();
}
$exercises_query->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduFSO</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: "Montserrat", sans-serif;
            line-height: 1.8;
            font-size: 1em;
            background: #fff;
            overflow-x: hidden;
        }

        header{
            position: fixed;
        }
        h1, h2, h3, h4, h5 {
            color: #000839;
        }
        .text-primary {
            color: #3369e7 !important;
        }
        .btn-primary {
            background-color: #3369e7;
            border-color: #3369e7;
        }
        .btn-primary:hover {
            background-color: #4576e9;
            border-color: #4576e9;
        }
        .course-content {
            margin-top: 100px;
        }
        .course-title {
            margin-bottom: 30px;
        }
        .exercise-section {
            margin-top: 30px;
        }
        .comments-section {
            margin-top: 30px;
        }
        .sidebar {
            position: fixed;
            top: 100;
            border-radius: 2%;
            right: 0;
            width: 300px;
            height: 100%;
            background-color: #f8f9fa;
            padding: 20px;
            overflow-y: hidden;
            border: 1px solid #ddd;
            margin-right: 20px;
             margin-top: 50px; /* Adjust this value to match the height of your header */
        }
        .content {
            margin-right: 320px;
            padding: 20px;
        }
    </style>
</head>
<body>
<header class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="etudiant.php">
        <img src="./images/logo.png" width="30" height="30" class="d-inline-block align-top" alt="" loading="lazy">
        EduFSO
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="etudiant.php">Accueil</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Cours</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Se déconnecter</a>
            </li>
        </ul>
    </div>
</header>

    <div class="sidebar">
        <h4>lessons</h4>
        <ul class="list-unstyled">
            <?php
            $lessons_query = $conn->prepare("SELECT Id_lesson, Titre_Lesson FROM lesson WHERE Id_Cours = ?");
            $lessons_query->bind_param("i", $course_id);
            $lessons_query->execute();
            $lessons_result = $lessons_query->get_result();
            while ($lesson_nav = $lessons_result->fetch_assoc()) {
                echo '<li><a href="course.php?course_id=' . $course_id . '&lesson_id=' . $lesson_nav['Id_lesson'] . '">' . $lesson_nav['Titre_Lesson'] . '</a></li>';
            }
            $lessons_query->close();
            ?>
        </ul>
    </div>

    <div class="content">
        <div class="container course-content">
        <ul class="tree">
  <li>
   
      <h1 class="course-title text-secondary"><?php echo htmlspecialchars($course['Titre_cours']); ?></h1>
      <ul>
        <li>
            <h3 class="course-title text-primary" style="padding-left:55px;">
        
        <?php echo htmlspecialchars($lesson['Titre_lesson']); ?>
        <?php if ($has_note): ?>
            <span style="color: green; margin-left: 10px;"><i class="fas fa-check-circle"></i></span>
        <?php endif; ?>
    </h3>
           
        </li>
       
      </ul>
   
  </li>
</ul>
<div style="background-color: #e9ecef; padding: 50px; border-radius: 9px; box-shadow: 0px 0px 200px 10px rgba(5, 8, 8, 0.1);border: 1px solid #ddd;">
<?php echo "<p style='font-family: Arial, sans-serif;'>" . nl2br(htmlspecialchars_decode($lesson['file_lesson'])) . "</p>"; ?>
</div>
<br><br>


            <?php if ($_SERVER['REQUEST_METHOD'] == 'POST') : ?>
                <div class="alert alert-info">
                    <?php
                    echo "Vous aves repondu a " . $correct_answers . " parmi " . $total_questions . " questions. Note : ". $correct_answers. " / ".$total_questions;
                    ?>
                </div>
                <div class="alert alert-info">
                    <h3>Reponses correctes</h3>
                    <ul>
                        <?php foreach ($response_data as $question_id => $correct_answer) : ?>
                            <li>Question ID: <?php echo $question_id; ?> - Reponse: <?php echo htmlspecialchars($correct_answer); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

<div class="alert alert-info" style="background-color: #f9f9f9; border-color: #bee5eb;">
    <?php
    echo $feedback;
    ?>
</div>
            <?php else : ?>
                <div class="exercise-section">
                <button class="btn btn-primary" id="start-quiz-btn">Commencer Quizz</button>
                <br><br>
                    <div id="exercise-container" style="display: none;">
                        <?php foreach ($exercises as $exercise) : ?>
                            <div class="card mb-4">
                                <div class="card-header">
                                <?php echo htmlspecialchars($exercise['Nom_Exercice']); ?>
                            </div>
                            <div class="card-body">
                                <form method="post" action="">
                                    <?php foreach ($exercise['questions'] as $question) : ?>
                                        <div class="mb-3">
                                            <label><?php echo htmlspecialchars($question['Question']); ?></label>
                                            <?php foreach ($question['options'] as $option) : ?>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="answers[<?php echo $question['Id_Question']; ?>]" id="option-<?php echo $option['Id_Option']; ?>" value="<?php echo $option['Id_Option']; ?>">
                                                    <label class="form-check-label" for="option-<?php echo $option['Id_Option']; ?>">
                                                        <?php echo htmlspecialchars($option['Text']); ?>
                                                    </label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endforeach; ?>
                                    <input type="hidden" name="lesson_id" value="<?php echo $lesson_id; ?>">
                                    <button type="submit" class="btn btn-primary">Soumettre les réponses</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="comments-section">
    <h2>Commentaires ou questions :</h2>
    <form id="commentForm" method="post" action="">
        <input type="hidden" id="lessonId" name="lessonId" value="<?php echo $lesson['Id_lesson']; ?>">
        <div class="form-group">
            <label for="comment"></label>
            <textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Envoyer</button>
    </form>
    <ul id="commentList" class="list-unstyled mt-4">
        <?php
        // PHP code to fetch and display comments
        $comments_query = $conn->prepare("SELECT e.Nom, c.Commentaire FROM commentaire c JOIN etudiant e ON c.Id_Etudiant = e.Id_Etudiant WHERE c.Id_lesson = ?");
        $comments_query->bind_param("i", $lesson['Id_lesson']);
        $comments_query->execute();
        $comments_result = $comments_query->get_result();

        while ($comment = $comments_result->fetch_assoc()) {
            echo '<li><strong>' . htmlspecialchars($comment['Nom']) . ':</strong> ' . htmlspecialchars($comment['Commentaire']) . '</li>';
        }

        $comments_query->close();
        ?>
    </ul>
</div>

        </div>
    </div>

    <script>
$(document).ready(function() {
    console.log("Document is ready.");
    $('#start-quiz-btn').click(function() {
                $('#exercise-container').slideToggle();
            });
    

    $('#commentForm').submit(function(e) {
        e.preventDefault();
        console.log("Form submitted.");

        var lessonId = $('#lessonId').val();
        var comment = $('#comment').val();

        console.log("Lesson ID:", lessonId);
        console.log("Comment:", comment);

        $.ajax({
            url: 'submit_comment.php',
            type: 'POST',
            data: { lessonId: lessonId, comment: comment },
            success: function(response) {
                console.log("Response received:", response);
                try {
                    var commentData = JSON.parse(response);
                    if (commentData.error) {
                        alert(commentData.error);
                    } else {
                        var newComment = '<li><strong>' + commentData.user_name + ':</strong> ' + commentData.comment + '</li>';
                        $('#commentList').append(newComment);
                        $('#comment').val('');
                    }
                } catch (e) {
                    console.error("JSON parse error:", e);
                    console.error("Response text:", response);
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX error:", status, error);
            }
        });
    });
});



    </script>
</body>
</html>
