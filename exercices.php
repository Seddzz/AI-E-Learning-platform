
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Untree.co">
    <link rel="shortcut icon" href="images/Purple Blur Gradient Glass Effect Tweet Motivational Quote Instagram Post (4).png" sizes="64x64">
    <meta name="description" content="" />
    <meta name="keywords" content="bootstrap, bootstrap4" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style_CRUD.css">
    <title>Parcourir les exercices</title>
    
</head>
<body>
    
</body>
</html>


<?php
include("db_connect.php");
if (!isset($_SESSION['user_name'])) {
    // Redirect to login page if the session is not set
    header("Location: index.php");
    exit;
}

$user_name = $_SESSION['user_name'];

// Obtenez les cours de l'enseignant
$query = "SELECT c.Id_Cours, c.Titre_cours FROM cours c 
          JOIN enseignant e ON c.Id_Enseignant = e.Id_Enseignant 
          WHERE e.Nom = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $user_name);
$stmt->execute();
$courses_result = $stmt->get_result();

?>

<!doctype html>
<html lang="en">
<head>
    <style media="screen">
        /* Your existing CSS styles */
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap');

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
        .exercise-block{
            border-width: 2px;

            border-color: #1c394d;
            background-color: #e9ecef;
            margin:20px;
            padding: 10px; border-radius: 9px; box-shadow: 0px 0px 200px 10px rgba(5, 8, 8, 0.1);border: 1px solid gainsboro;
            
        }
        .question-block{
            text-align: start;
            margin:10px;
            border-width: 2px;
            background-color: #e9ecef; padding: 10px; border-radius: 9px; box-shadow: 0px 0px 200px 10px rgba(5, 8, 8, 0.1);border: 1px solid gainsboro;
        }
        .exercise-delete-button{
            background-color:#607CB1;
            border-color:#607CB1;
        }
        .exercise-edit-button{
            background-color:#607CB1;
            border-color:#607CB1;
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
<body data-aos-easing="slide" data-aos-duration="800" data-aos-delay="0">

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
<h1 style="text-align:center;position:relative;font-size:6vh;">Créer, Modifier, Supprimer et générér vos Exercices</h1>


<div class="container">
    <div class="row align-items-center justify-content-center">
        <div class="col-md-12 mt-lg-5 text-center">


            
        <div class="container">
    <select id="courseSelect" onchange="loadFirstLesson(this.value)">
        <option value="">Sélectionnez un cours</option>
        <?php while ($course = $courses_result->fetch_assoc()): ?>
            <option value="<?= $course['Id_Cours'] ?>"><?= $course['Titre_cours'] ?></option>
        <?php endwhile; ?>
    </select>
    <div id="lessonContainer"></div>
</div>

        </div>
    </div>
    <!-- Modal for Add/Edit Exercise -->
    <div class="modal" id="exerciseModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalTitle">Ajouter un exercice</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="exerciseForm">
                    <input type="hidden" id="lessonId" name="lessonId">
                    <input type="hidden" id="exerciseId" name="exerciseId">
                    
                    <div class="form-group">
                        <label for="exerciseName">Nom de l'exercice</label>
                        <input type="text" class="form-control" id="exerciseName" name="exerciseName" required>
                    </div>
                    
                    <div class="question-group">
                        <h5>Question 1</h5>
                        <div class="form-group">
                            <label for="question1">Question</label>
                            <input type="text" class="form-control" id="question1" name="question1" required>
                        </div>
                        <div class="form-group">
                            <label for="option1_1">Option 1</label>
                            <input type="text" class="form-control" id="option1_1" name="option1_1" required>
                            <input type="radio" name="correctOption1" value="option1_1" required> Correct
                        </div>
                        <div class="form-group">
                            <label for="option1_2">Option 2</label>
                            <input type="text" class="form-control" id="option1_2" name="option1_2" required>
                            <input type="radio" name="correctOption1" value="option1_2"> Correct
                        </div>
                        <div class="form-group">
                            <label for="option1_3">Option 3</label>
                            <input type="text" class="form-control" id="option1_3" name="option1_3" required>
                            <input type="radio" name="correctOption1" value="option1_3"> Correct
                        </div>
                    </div>
                    
                    <div class="question-group">
                        <h5>Question 2</h5>
                        <div class="form-group">
                            <label for="question2">Question</label>
                            <input type="text" class="form-control" id="question2" name="question2" required>
                        </div>
                        <div class="form-group">
                            <label for="option2_1">Option 1</label>
                            <input type="text" class="form-control" id="option2_1" name="option2_1" required>
                            <input type="radio" name="correctOption2" value="option2_1" required> Correct
                        </div>
                        <div class="form-group">
                            <label for="option2_2">Option 2</label>
                            <input type="text" class="form-control" id="option2_2" name="option2_2" required>
                            <input type="radio" name="correctOption2" value="option2_2"> Correct
                        </div>
                        <div class="form-group">
                            <label for="option2_3">Option 3</label>
                            <input type="text" class="form-control" id="option2_3" name="option2_3" required>
                            <input type="radio" name="correctOption2" value="option2_3"> Correct
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Sauvegarder</button>
                </form>
            </div>
        </div>
    </div>
</div>

</div>
<br><br>
<script>
    function loadFirstLesson(courseId) {
        if (courseId) {
            fetch(`loadFirstLesson.php?courseId=${courseId}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('lessonContainer').innerHTML = data;
                });
        } else {
            document.getElementById('lessonContainer').innerHTML = '';
        }
    }

    function loadNextLesson(courseId, currentLessonId) {
        fetch(`loadNextLesson.php?courseId=${courseId}&currentLessonId=${currentLessonId}`)
            .then(response => response.text())
            .then(data => {
                document.getElementById('lessonContainer').innerHTML = data;
            });
    }

    function loadPreviousLesson(courseId, currentLessonId) {
        fetch(`loadPreviousLesson.php?courseId=${courseId}&currentLessonId=${currentLessonId}`)
            .then(response => response.text())
            .then(data => {
                document.getElementById('lessonContainer').innerHTML = data;
            });
    }

    function openExerciseModal(lessonId, exerciseId = null) {
        document.getElementById('lessonId').value = lessonId;
        if (exerciseId) {
            // Edit existing exercise
            document.getElementById('modalTitle').innerText = "Modifier l'exercice";
            document.getElementById('exerciseId').value = exerciseId;
            fetch(`getExercise.php?exerciseId=${exerciseId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('exerciseName').value = data.Nom_Exercice;
                });
        } else {
            // Add new exercise
            document.getElementById('modalTitle').innerText = "Ajouter un exercice";
            document.getElementById('exerciseId').value = '';
    document.getElementById('exerciseName').value = '';
    document.getElementById('question1').value = '';
    document.getElementById('option1_1').value = '';
    document.getElementById('option1_2').value = '';
    document.getElementById('option1_3').value = '';
    document.getElementById('question2').value = '';
    document.getElementById('option2_1').value = '';
    document.getElementById('option2_2').value = '';
    document.getElementById('option2_3').value = '';
            document.getElementById('exerciseForm').reset();
        }
        $('#exerciseModal').modal('show');
    }

    document.getElementById('exerciseForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        fetch('saveExercise.php', {
            method: 'POST',
            body: formData
        }).then(response => response.text())
          .then(data => {
              $('#exerciseModal').modal('hide');
              const lessonId = document.getElementById('lessonId').value;
              loadFirstLesson(document.getElementById('courseSelect').value); // Reload the lesson
          });
    });

    function deleteExercise(exerciseId) {
        if (confirm('Are you sure you want to delete this exercise?')) {
            fetch(`deleteExercise.php?exerciseId=${exerciseId}`)
                .then(response => response.text())
                .then(data => {
                    loadFirstLesson(document.getElementById('courseSelect').value); // Reload the lesson
                });
        }
    }

    function generateExercise(lessonId) {
        alert('Générer un exercice pour la leçon ' + lessonId);
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
