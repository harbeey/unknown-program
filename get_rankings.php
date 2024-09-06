<?php
include 'db_config.php';

$conn = getDbConnection();

$sql = "SELECT username, correct_answers, total_questions FROM users ORDER BY correct_answers DESC";
$result = $conn->query($sql);

$rankings = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rankings[] = $row;
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($rankings);
?>
