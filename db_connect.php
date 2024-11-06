<?php
session_start();
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "projet";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = isset($_POST["user_id"]) ? $_POST["user_id"] : null;
    $email = isset($_POST["email"]) ? $_POST["email"] : null;
    $password = isset($_POST["password"]) ? $_POST["password"] : null;

    if ($userId && $email && $password) {
        $userType = ($userId === "enseignant") ? "enseignant" : "etudiant";
        
        $sql = "SELECT * FROM $userType WHERE Email = ? AND Mot_de_Passe = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // User found, login successful
            $user = $result->fetch_assoc(); // Fetch data of the user
            $_SESSION['user_name'] = $user['Nom']; // Store user name in session
            $_SESSION['user_type'] = $userType; // Store user type in session
            if ($userType === "enseignant") {
                header("Location: enseignant.php");
            } else {
                header("Location: etudiant.php");
            }
            exit;
        } else {
            echo "Invalid email or password for $userType.";
        }
    } 
}
?>
