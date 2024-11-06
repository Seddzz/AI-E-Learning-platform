<?php
include 'db_connect.php';

header('Content-Type: application/json');

if (!isset($_GET['exerciseId'])) {
    echo json_encode(['success' => false, 'error' => 'exerciseId parameter is missing.']);
    exit;
}

$exerciseId = $_GET['exerciseId'];

$delete_query = $conn->prepare("DELETE FROM exercice WHERE Id_Exercice = ?");
if (!$delete_query) {
    echo json_encode(['success' => false, 'error' => $conn->error]);
    exit;
}

$delete_query->bind_param('i', $exerciseId);

if ($delete_query->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $delete_query->error]);
}

$delete_query->close();
$conn->close();
?>
