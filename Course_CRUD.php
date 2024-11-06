<?php
include("db_connect.php");


if (!isset($_SESSION['user_name'])) {
    // Redirect to login page if the session is not set
    header("Location: index.php");
    exit;
}

$course_to_edit = null;
$lessons_to_edit = [];

if (isset($_GET['edit_id'])) {
    $course_id = intval($_GET['edit_id']);
    $query = "SELECT * FROM cours WHERE Id_Cours = $course_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $course_to_edit = $result->fetch_assoc();

        $lessons_query = "SELECT * FROM lesson WHERE Id_Cours = $course_id";
        $lessons_result = $conn->query($lessons_query);
        while ($lesson = $lessons_result->fetch_assoc()) {
            $lessons_to_edit[] = [
                'id' => $lesson['Id_lesson'],
                'title' => $lesson['Titre_lesson'],
                'content' => $lesson['file_lesson']
            ];
        }
    }
}

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
            $lesson_id = isset($_POST["lesson_id_$i"]) ? intval($_POST["lesson_id_$i"]) : 0;
            $lesson_title = $_POST["lesson_title_$i"];
            $lesson_content = $_POST["lesson_content_$i"];

            if ($lesson_id > 0) {
                // La leçon existe, faites une mise à jour
                $lesson_update_query = $conn->prepare("UPDATE lesson SET Titre_lesson = ?, file_lesson = ? WHERE Id_Lesson = ?");
                $lesson_update_query->bind_param("ssi", $lesson_title, $lesson_content, $lesson_id);
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

?>

<!doctype html>
<ht lang="en">
<head>
    <style media="screen">
        /* Your existing CSS styles */
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap');
        /* Add your existing styles here */
        /* Additional styles for the statistics section and course cards */
        .statistics-section {
            margin-top: 50px;
            text-align: center;
            height: 500px;
        }

        .sous-titre {
      display: flex;
      width:90%;
            flex-direction: column;
            text-align: start;
            margin: 5%;
            background-color: rgba(142, 153, 162, 0.2); /* Gray with transparency */
            padding: 15px; /* Adjust padding to increase height */
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Light border shadow */
            display: inline-block; /* Ensures the container only wraps the content */
        }

        .bar-chart {
            display: flex;
            justify-content: space-around;
            align-items: flex-end;
            height: 100%;
            width: 80%;
            margin: 0 auto;
            background-color: #f0f0f0;
            border-radius: 5px;
            padding: 10px;
            text-align: center;
            bottom: 100%;
        }

        .bar {
            width: 50px;
            bottom: 100%;
            background: linear-gradient(to bottom, #607CB1, #1c394d);
            color: white;
            box-shadow: 0px 0px 10px  rgba(0, 0, 0, 0.1);
        }
        .TextCourses{
            display: flex;
            flex-direction: column;
            text-align: start;
            margin: 5%;
        }
        .CoursesContainer {
            width: 100%; /* Set a width */
            display: flex;
            flex-direction: row;
            justify-content: center; /* Adjust as needed */
            align-items: center;
            margin-top: 50px;
        }

        .course-card:hover {
            transition-timing-function: ease-in-out;
            transform: scale(1.01);
            transition-duration: 0.2s;
        }

        .course-card {
            width: 250px;
            border: 1px solid #ccc;
            border-radius: 3%;
            overflow: hidden;
            margin: 10px;
            transition-timing-function: ease-in-out;
            transition-duration: 0.2s;
            margin: 2%;
        
        }


        .course-image img {
            width: 100%;
            height: auto;
            background-color: white;
        }

        .course-details {
            display: flex;
            flex-direction: row;
        }
        .course-details-left-container{
            flex: 7.5;
            display: flex;
            flex-direction: column;
            margin: 5%;
            margin-right: 0%;


        }
        .course-details-right-container{
            flex: 2.5;
            justify-items: center;
            text-align: center;
            margin-top:5%;
            margin-bottom:5%;
            align-content: center;

        }

        .img_next{
            width: 30px;
            height: 30px;
            align-self: center;

            margin: 5%;
            transition-timing-function: ease-in-out;
            transition-duration: 0.2s;

        }
        .img_next:hover{
            transition-timing-function: ease-in-out;
            transform: scale(1.1);
            transition-duration: 0.2s;
        }

        .course-title {
            margin-bottom: 5px;
            color: #333;
            font-size: large;
            font-weight: medium;
        }
        .course-parts {
            margin-bottom: 0;
            color: #4d7d22;
            font-size:80%;
            font-weight:bold ;
        }
        .course-instructor {
            margin: 1%;
            font-weight:200;
            color:#6D6D6D;
            font-size: small;
        }
        /* Your existing CSS styles continued */
    </style>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Untree.co">
    <link rel="shortcut icon" href="images/Purple Blur Gradient Glass Effect Tweet Motivational Quote Instagram Post (4).png" sizes="64x64">
    <meta name="description" content="" />
    <meta name="keywords" content="bootstrap, bootstrap4" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="fonts/icomoon/style.css">
    <link rel="stylesheet" href="fonts/flaticon/font/flaticon.css">
    <link rel="stylesheet" href="css/jquery.fancybox.min.css">
    <link rel="stylesheet" href="css/aos.css">
    <link rel="stylesheet" href="css/style_CRUD.css">
    <link rel="stylesheet" href="css/style_enseignant_CRUD.css">
    <title>Edufso</title>
</head>
< data-aos-easing="slide" data-aos-duration="800" data-aos-delay="0">

<div class="container">
    <nav class="site-nav">
        <div class="logo">
            <a href="#"><img class="khdmi" src="./images/logo.png" alt="image-alterna"></a>
        </div>
        <div class="row align-items-center">
            <div class="col-12 col-sm-12 col-lg-12 site-navigation text-center">
                <ul class="js-clone-nav d-none d-lg-inline-block text-left site-menu">
                    <li class="active"><a href="enseignant.php">Accueil</a></li>
                    
                    <li><a href="logout.php">Se déconnecter</a></li>
                </ul>
            </div>
        </div>
    </nav> <!-- END nav -->
    <br>
    <br>
    <br>
    <br>
</div> 
<br>
<br>
<h1 style="text-align:center;position:relative;font-size:6vh;">Créer, Modifier et Supprimer vos cours</h1>


<div class="container">
    <div class="row align-items-center justify-content-center">
        <div class="col-md-12 mt-lg-5 text-center">
            <div class="TextCours">
                <div class="button-container">
                    <button id="createCourseButton" class="btn btn-primary mr-2 mb-2" onclick="showAddCourseForm()" style=" text-align: center; margin-top: 20px;">Ajoute un cours</button>
                    <button id="parcourirExercicesButton" class="btn btn-primary mr-2 mb-2" style=" text-align: center; margin-top: 20px;" onclick="window.location.href='exercices.php'">Parcourir les exercices</button>

                </div>
            </div>
            
    <div id="lessonContainer"></div>

    </div>


            <div class="TextCourses">
                <div class="CoursesContainer">
                    <?php
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "projet";

                    $conn = new mysqli($servername, $username, $password, $dbname);

                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $user_name = $_SESSION['user_name'];

                    $query = "SELECT Id_Enseignant, Nom, Prenom FROM enseignant WHERE Nom = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('s', $user_name);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $id_enseignant = $row['Id_Enseignant'];
                        $nom_enseignant = $row['Nom'];
                        $prenom_enseignant = $row['Prenom'];

                        $query = "SELECT cours.Id_Cours, cours.Titre_cours, cours.Course_Image, COUNT(lesson.Id_Lesson) AS Lesson_Count FROM cours LEFT JOIN lesson ON lesson.Id_Cours = cours.Id_Cours WHERE cours.Id_Enseignant = ? GROUP BY cours.Id_Cours";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param('i', $id_enseignant);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<div class="course-card" onclick="fetchComments(' . $row['Id_Cours'] . ')">';
                                echo '<div class="course-image">';
                                $imageData = base64_encode($row['Course_Image']);
                                $src = 'data:image/jpeg;base64,' . $imageData;
                                echo '<img src="' . $src . '" alt="Course Image" id="cours' . $row['Id_Cours'] . '">';
                                echo '</div>';
                                echo '<div class="course-details">';
                                echo '<div class="course-details-left-container">';
                                echo '<h3 class="course-title">' . $row['Titre_cours'] . '</h3>';
                                echo '<p class="course-instructor">Mr ' . $user_name . ' ' . $prenom_enseignant . '</p>';
                                echo '<p class="course-parts">Parts: '.$row['Lesson_Count'].'</p>';
                                echo '</div>';
                                echo '<div class="course-details-right-container">';
                                echo '<a href="?edit_id=' . $row['Id_Cours'] . '" class="edit-button"><img src="images/pen.png" alt="Pen" class="img_pen"></a>';
                                echo '<a href="#Supprimer" class="delete-button" onclick="deleteCourse(' . $row['Id_Cours'] . ')"><img src="images/garbage.png" alt="Garbage" class="img_garbage"></a>';
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                            }
                        } else {
                            echo '<p style="color: white">No courses found for this teacher.</p>';
                        }
                    } else {
                        echo "Teacher not found.";
                    }
                    $conn->close();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Course Form -->
<form id="addCourseForm" style="display:none;" enctype="multipart/form-data" method="post" action="add_course.php">
    <h2>Créer un cours</h2>
    <label for="course-title">Titre du cours</label>
    <input type="text" id="course-title" name="course-title">

    <label for="lesson-count">Nombre de lessons</label>
    <input type="number" id="lesson-count" name="lesson-count" min="1"  oninput="generateLessonFields()">

    <label for="course-image">Cours Image</label>
    <input type="file" id="course-image" name="course-image" accept="image/*" >

    <div id="dynamicFieldsContainer"></div>

    <button class="btn btn-primary mr-2 mb-2" type="submit">Add</button>
</form>

<!-- Edit Course Form -->
<form id="editCourseForm" style="display:<?php echo isset($course_to_edit) ? 'block' : 'none'; ?>;" enctype="multipart/form-data" method="post" action="course_CRUD.php">
    <h2>Modifier cours</h2>
    <input type="hidden" id="course-id" name="course-id" value="<?php echo isset($course_to_edit) ? $course_to_edit['Id_Cours'] : ''; ?>">
    <label for="course-title">Titre du Cours</label>
    <input type="text" id="course-title" name="course-title" value="<?php echo isset($course_to_edit) ? $course_to_edit['Titre_cours'] : ''; ?>" required>

    <label for="lesson-count">Nombre de lessons</label>
    <input type="number" id="lesson-count" name="lesson-count" min="1" value="<?php echo count($lessons_to_edit); ?>" required oninput="generateLessonFields1(this.value)">

    <div id="dynamicFieldsContainer">
        <?php
        if (isset($lessons_to_edit)) {
            foreach ($lessons_to_edit as $index => $lesson) {
                echo '<input type="hidden" name="lesson_id_' . ($index + 1) . '" value="' . $lesson['id'] . '">';
                echo '<input type="text" name="lesson_title_' . ($index + 1) . '" value="' . $lesson['title'] . '" required>';
                echo '<textarea name="lesson_content_' . ($index + 1) . '" required>' . $lesson['content'] . '</textarea>';
            }
        }
        ?>
    </div>

    <button class="btn btn-primary mr-2 mb-2" type="submit">Mettre à jour</button>
</form>
<div class="sous-titre"> <h3 style="margin-left: 50px;">Commentaires : </h3></div>
<div id="commentsContainer" style="margin-left: 150px;margin-top:-70px;">
        
        <!-- Comments will be dynamically inserted here -->
</div>

<script>
    function fetchComments(courseId) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'get_comments.php?course_id=' + courseId, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var commentsContainer = document.getElementById('commentsContainer');
            commentsContainer.innerHTML = ''; // Clear existing comments
            var response = JSON.parse(xhr.responseText);
            if (response.error) {
                commentsContainer.innerHTML = '<p>' + response.error + '</p>';
            } else {
                response.forEach(function(comment) {
                    var commentDiv1 = document.createElement('div');
                    var commentDiv = document.createElement('div');
                    
                    commentDiv1.classList.add('lesson');
                    commentDiv.classList.add('comment');
                    commentDiv.innerHTML = '<p><strong>' + comment.student_name + ':</strong> ' + comment.comment + '</p>';
                    commentsContainer.appendChild(commentDiv1);
                    commentsContainer.appendChild(commentDiv);
                    
                });
            }
        }
    };
    xhr.send();
}
    function showAddCourseForm() {
        document.getElementById('addCourseForm').style.display = 'block';
    }

    function generateLessonFields() {
        const lessonCount = document.getElementById('lesson-count').value;
        const container = document.getElementById('dynamicFieldsContainer');
        container.innerHTML = ''; // Clear any existing fields

        for (let i = 0; i < lessonCount; i++) {
            const lessonTitle = document.createElement('input');
            lessonTitle.type = 'text';
            lessonTitle.name = `lesson_title_${i + 1}`;
            lessonTitle.placeholder = `Lesson Title ${i + 1}`;
            lessonTitle.required = true;

            const lessonContent = document.createElement('textarea');
            lessonContent.name = `lesson_content_${i + 1}`;
            lessonContent.placeholder = `Lesson Content ${i + 1}`;
            lessonContent.required = true;

            container.appendChild(lessonTitle);
            container.appendChild(lessonContent);
        }
    }
    function generateLessonFields1(count) {
        const container = document.getElementById('dynamicFieldsContainer');
        container.innerHTML = ''; // Clear any existing fields

        for (let i = 0; i < count; i++) {
            const lessonTitle = document.createElement('input');
            lessonTitle.type = 'text';
            lessonTitle.name = `lesson_title_${i + 1}`;
            lessonTitle.placeholder = `Lesson Title ${i + 1}`;
            lessonTitle.required = true;

            const lessonContent = document.createElement('textarea');
            lessonContent.name = `lesson_content_${i + 1}`;
            lessonContent.placeholder = `Lesson Content ${i + 1}`;
            lessonContent.required = true;

            container.appendChild(lessonTitle);
            container.appendChild(lessonContent);
        }
    }

    function deleteCourse(courseId) {
    if (confirm('Are you sure you want to delete this course?')) {
        fetch('delete_course.php?course_id=' + courseId)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Course deleted successfully');
                    location.reload();
                } else {
                    alert('Error deleting course: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error deleting course:', error);
                alert('Network error: ' + error.message);
            });
    }
}

    // Populate dynamic fields container on page load if editing
    if (document.getElementById('lesson-count').value > 0) {
        generateLessonFields1(document.getElementById('lesson-count').value);
    }
    function showExercises() {
    document.getElementById('exerciseContainer').style.display = 'block';
}

function loadLessons(courseId) {
    if (courseId) {
        fetch(`loadLessons.php?courseId=${courseId}`)
            .then(response => response.text())
            .then(data => {
                document.getElementById('lessonContainer').innerHTML = data;
            });
    } else {
        document.getElementById('lessonContainer').innerHTML = '';
    }
}

function loadExercises(lessonId) {
    fetch(`loadExercises.php?lessonId=${lessonId}`)
        .then(response => response.text())
        .then(data => {
            document.getElementById(`exercises_${lessonId}`).innerHTML = data;
        });
}

function addExercise(lessonId) {
    // Implémenter la logique pour ajouter un exercice
}

function deleteExercise(exerciseId) {
    // Implémenter la logique pour supprimer un exercice
}

function editExercise(exerciseId) {
    // Implémenter la logique pour modifier un exercice
}

function generateExercise(lessonId) {
    // Implémenter la logique pour générer un exercice via API
}

</script>

<div id="overlayer"></div>
<div class="loader">
    <div class="spinner-border" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>

<script src="js/jquery-3.4.1.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/owl.carousel.min.js"></script>
<script src="js/aos.js"></script>
<script src="js/imagesloaded.pkgd.js"></script>
<script src="js/isotope.pkgd.min.js"></script>
<script src="js/jquery.animateNumber.min.js"></script>
<script src="js/jquery.waypoints.min.js"></script>
<script src="js/jquery.fancybox.min.js"></script>
<script src="js/custom.js"></script>

</body>
</html>
