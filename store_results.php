<?php
session_start();
include 'db_config.php';

if (!isset($_SESSION['username'])) {
    die("User not logged in.");
}

$username = $_SESSION['username'];
$data = json_decode(file_get_contents('php://input'), true);
$correct_answers = isset($data['correct_answers']) ? intval($data['correct_answers']) : 0;
$total_questions = isset($data['total_questions']) ? intval($data['total_questions']) : 0;

$conn = getDbConnection();

$sql = "INSERT INTO users (username, correct_answers, total_questions) VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE correct_answers = VALUES(correct_answers), total_questions = VALUES(total_questions)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sii", $username, $correct_answers, $total_questions);

if ($stmt->execute() === TRUE) {
    echo "Quiz results stored successfully.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
