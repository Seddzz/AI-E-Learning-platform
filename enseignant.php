<?php
include("db_connect.php");
if (!isset($_SESSION['user_name'])) {
    // Redirect to login page if the session is not set
    header("Location: index.php");
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>

        .sous-titre {
            background-color: rgba(142, 153, 162, 0.2); /* Gray with transparency */
            padding: 15px; /* Adjust padding to increase height */
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Light border shadow */
            display: inline-block; /* Ensures the container only wraps the content */
        }
        

        /* Additional styles for the statistics section and course cards */

        .statistics-section {
            display: flex;
            flex-direction: column;
            margin-top: 10px;
            text-align: start;
            margin: 5%;
            margin-bottom: 15%;
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
    <link rel="stylesheet" href="css/style.css">
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

</div> <!-- END container -->

<div class="site-blocks-cover overlay" data-aos="fade" id="home-section">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-md-12 mt-lg-5 text-center">
                <h1 data-aos="fade-up" class="mb-4">Tableau de bord de l'enseignant</h1>
                <div class="row justify-content-around">
                    <div class="col-lg-8">
                        <p class="mb-5"  data-aos="fade-up" data-aos-delay="100">Bonjour Mr <?php echo $_SESSION['user_name']?><br><br>
                            Vous pouvez partager vos nouveaux cours, partager des nouveaux exercices et voir le rendement de vos cours.
                        </p>
                    </div>
                </div>
                <div data-aos="fade-up" data-aos-delay="100">
                <a href="#courses-section" class="btn btn-primary mr-2 mb-2">Explorer vos cours</a>
                <a href="#enseignant-section" class="btn btn-primary mr-2 mb-2">Statistiques</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="courses-section" class="TextCourses">
    <div class="sous-titre">
    <h3><a>Explorer vos Cours</a></h3>
</div>
    <div class="CoursesContainer">
    <?php

if (!isset($_SESSION['user_name'])) {

    header("Location: index.php");
    exit;
}

// Assurez-vous que ce fichier contient la connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "projet";

// Crée une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifie la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Récupère le nom de l'utilisateur connecté
$user_name = $_SESSION['user_name'];

// Préparez la requête SQL pour récupérer l'ID de l'enseignant connecté
$query = "SELECT Id_Enseignant, Nom, Prenom FROM enseignant WHERE Nom = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $user_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $id_enseignant = $row['Id_Enseignant'];
    $nom_enseignant=$row['Nom'];
    $prenom_enseignant=$row['Prenom'];
    
    //Requete pour recuperer le cours du prof
    $query = "SELECT cours.Id_Cours, cours.Titre_cours, cours.Course_Image, COUNT(lesson.Id_Lesson) AS Lesson_Count FROM cours LEFT JOIN lesson ON lesson.Id_Cours = cours.Id_Cours WHERE cours.Id_Enseignant = ? GROUP BY cours.Id_Cours";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id_enseignant);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // Affichez les cours dans une boucle
        while ($row = $result->fetch_assoc()) {
            echo '<div class="course-card">';
            echo '<a href="Course_CRUD.php">';
            echo     '<div class="course-image">';
            $imageData = base64_encode($row['Course_Image']);
            // Create the image source using base64 encoding
            $src = 'data:image/jpeg;base64,' . $imageData;
            echo         '<img src="'.$src.'" alt="Course Image" id="cours'.$row['Id_Cours'].'">';
            echo     '</div>';
            echo     '<div class="course-details">';
            echo         '<div class="course-details-left-container">';
            echo             '<h3 class="course-title">'.$row['Titre_cours'].'</h3>';
            echo             '<p class="course-instructor">Mr '.$user_name.' '.$prenom_enseignant.'</p>';
            echo             '<p class="course-parts">Parties: '. $row['Lesson_Count'].' </p>'; // Use the lesson count from the query
            echo         '</div>';
            echo         '<div class="course-details-right-container">';
            echo         '</div>';
            echo     '</div>';
            echo     '</a>';
            echo '</div>';
        }
    } else {
        echo '<a href="Course_CRUD.php"> Creer votre premier cours!</a>';


    }
} else {
    echo 'Aucun enseignant trouvé avec ce nom.';
}
if (!isset($_SESSION['user_name'])) {
    header("Location: index.php");
    exit;
}

// Assurez-vous que ce fichier contient la connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "projet";

// Crée une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifie la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Récupère le nom de l'utilisateur connecté
$user_name = $_SESSION['user_name'];

// Préparez la requête SQL pour récupérer l'ID de l'enseignant connecté
$query = "SELECT Id_Enseignant FROM enseignant WHERE Nom = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $user_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $id_enseignant = $row['Id_Enseignant'];

    // Requête pour récupérer les statistiques des cours de cet enseignant
    $query_stats = "SELECT c.Titre_cours, COUNT(cp.Id_Progress) AS submissions_count 
                   FROM cours c
                   LEFT JOIN course_progress cp ON c.Id_Cours = cp.Id_Cours
                   WHERE c.Id_Enseignant = ?
                   GROUP BY c.Titre_cours";
    $stmt_stats = $conn->prepare($query_stats);
    $stmt_stats->bind_param('i', $id_enseignant);
    $stmt_stats->execute();
    $result_stats = $stmt_stats->get_result();

    $courses = [];
    $submissions = [];

    if ($result_stats->num_rows > 0) {
        while ($row_stats = $result_stats->fetch_assoc()) {
            $courses[] = $row_stats['Titre_cours'];
            $submissions[] = $row_stats['submissions_count'];
        }
    } else {
        echo 'Aucun cours trouvé pour cet enseignant.';
    }
} else {
    echo 'Aucun enseignant trouvé avec ce nom.';
}

$stmt->close();
$stmt_stats->close();
$conn->close();

?>


    </div>
</div>

<br>
<br>





<div id="enseignant-section" class="statistics-section">
<div class="sous-titre">
    <h3>Statistiques</h3></div>
    <br><br><br>
    <canvas id="coursePopularityChart"></canvas>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('coursePopularityChart').getContext('2d');
    var coursePopularityChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($courses); ?>,
            datasets: [{
                label: 'Nombre de soumissions',
                data: <?php echo json_encode($submissions); ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
</div>




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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


</body>
</html>