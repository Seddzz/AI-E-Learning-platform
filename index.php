<?php
include("db_connect.php");
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="author" content="Untree.co">
  <link rel="shortcut icon" href="images/Purple Blur Gradient Glass Effect Tweet Motivational Quote Instagram Post (4).png" sizes="64x64">

  <meta name="description" content="" />
  <meta name="keywords" content="bootstrap, bootstrap4" />

  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
        <a href="index.php"><img class="khdmi" src="./images/logo.png" alt="image-alterna"></a>
      </div>
      <div class="row align-items-center">
        
        
        <div class="col-12 col-sm-12 col-lg-12 site-navigation text-center">
          <ul class="js-clone-nav d-none d-lg-inline-block text-left site-menu">
            <li class="active"><a href="#">Accueil</a></li>
            
            <li><a href="#outer-container">Etudiant</a></li>
            <li><a href="#outer-container" >Enseignant</a></li>
            <li><a href="#site-footer">A propos</a></li>
          </ul>

        </div>

      </div>  
    </nav> <!-- END nav -->

  </div> <!-- END container -->


  <div class="site-blocks-cover overlay aos-init aos-animate" data-aos="fade" id="home-section">

    <div class="container">
      <div class="row align-items-center justify-content-center">

        
        <div class="col-md-12 mt-lg-5 text-center">
          <h1 data-aos="fade-up" class="mb-4 aos-init aos-animate">Apprendre est facile, EduFSO le rend plus facile et plus amusant.</h1>

          <div class="row justify-content-around">
            <div class="col-lg-8">
            <p class="mb-5 aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">Libérez un Savoir Illimité : Lancez-vous dans votre Aventure d'Apprentissage avec Notre Plateforme E-Learning Complète et Excellez dans vos Projets Académiques et Professionnels !</p>
            </div>
          </div>
          <div data-aos="fade-up" data-aos-delay="100" class="aos-init aos-animate">
            <a id="scrollButton" class="btn btn-primary mr-2 mb-2">Explorez des cours</a>
          </div>
        </div>
          
      </div>
    </div>
    <!-- HTML code for the outer container -->
<div id="outer-container" class="outer-container">
  <!-- HTML code for the inner container -->
  
  <div class="inner-container">
    <!-- HTML code for the left circular image -->
    <div id="teacher" onclick="showLogin('prof')" class="left-image">
      
        <img src="./images/Professeur.jpeg" alt="Left Image" class="circle-image">
        <h1 class="image-text">Enseignant</h1>
    </div>
    <!-- HTML code for the right circular image -->
    <div id="student" onclick="showLogin('etudiant')" class="right-image">
      <a>
        <img src="./images/Etudiant.jpg" alt="Right Image" class="circle-image">
        <h1 class="image-text">Etudiant</h1>
      </a>
    </div>
    
    </div>
    <div class="form-container" style="display: none;" id="loginProf">
      <form method="post">
        <div class="back-button-div">
          <div class="back-button" onclick="goBack()">
                &lt;
          </div>
       </div>
        <div class="form-group">
          <input type="hidden" name="user_id" value="enseignant">
          <label for="email">Email:</label>
          <input type="email" placeholder="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
          <label for="password">Mot de passe:</label>
          <input type="password" placeholder="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    </div>
    <div class="form-container" style="display: none;" id="loginEtudiant">
      <form method="post">
      <div class="back-button-div">
          <div class="back-button" onclick="goBack()">
                &lt;
          </div>
       </div>
        <div class="form-group">
          <input type="hidden" name="user_id" value="etudiant">
          <label for="email">Email:</label>
          <input type="email" placeholder="email"  class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
          <label for="password">Mot de passe:</label>
          <input type="password" placeholder="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>

  </div>
  
  
</div>
<hr>

<div class="site-footer" id="site-footer">
  <div class="container">
    <div class="row justify-content-between">
      <div class="col-lg-4">
        <div class="widget">
          <h3>A propos</h3>
          <p class="About-text">Sur EduFSO, nous visons une meilleure expérience d'apprentissage. </p>
          
        </div>
        <div class="widget">
          <h3>se connecter avec nous</h3>
          <ul class="social list-unstyled">
            <li><a href="#"><span class="icon-facebook"></span></a></li>
            <li><a href="#"><span class="icon-twitter"></span></a></li>
            <li><a href="#"><span class="icon-instagram"></span></a></li>
            <li><a href="#"><span class="icon-dribbble"></span></a></li>
            <li><a href="#"><span class="icon-linkedin"></span></a></li>
          </ul>
        </div>
      </div>
      <div class="col-lg-6">
        
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
       function showLogin(type) {
            if (type === 'prof') {
                document.getElementById('loginProf').style.display = 'block';
                document.getElementById('loginEtudiant').style.display = 'none';
            } else if (type === 'etudiant') {
                document.getElementById('loginProf').style.display = 'none';
                document.getElementById('loginEtudiant').style.display = 'block';
            }
            document.querySelector('.inner-container').style.display = 'none';
        }

        function goBack() {
            document.querySelector('.inner-container').style.display = 'flex';
            document.getElementById('loginProf').style.display = 'none';
            document.getElementById('loginEtudiant').style.display = 'none';
        }

  document.getElementById('scrollButton').addEventListener('click', function() {
    // Scroll to the target section with an ID of 'section2' smoothly
    document.getElementById('outer-container').scrollIntoView({
        behavior: 'smooth'
    });
});
    </script>

    
    