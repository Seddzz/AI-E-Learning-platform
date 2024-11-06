<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $answers = $_POST['answers'];
    $total_questions = count($answers);
    $correct_answers = 0;

    foreach ($answers as $question_id => $student_answer) {
        $query = $conn->prepare("SELECT Reponse FROM question WHERE Id_Question = ?");
        $query->bind_param("i", $question_id);
        $query->execute();
        $result = $query->get_result();
        $correct_answer = $result->fetch_assoc()['Reponse'];

        if ($student_answer == $correct_answer) {
            $correct_answers++;
        }
    }

    // Run the Python script to generate feedback
    $command = escapeshellcmd('python3 feedback.py');
    $output = shell_exec($command);

    // Include the feedback PHP file
    include 'feedback.php';

    // Return the results as a JSON response
    $response = [
        'correct_answers' => $correct_answers,
        'total_questions' => $total_questions,
        'feedback' => $feedback
    ];
    echo json_encode($response);
}
?>
