<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
header("Content-Type: application/json");
include 'assets/database/database.php'; 

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "You need to log in to view activities."]);
    exit;
}

// Testa se a conexão existe
if (!$conn) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

$sql = "SELECT id, nome, tipo FROM atividades";
$result = $conn->query($sql);

if (!$result) {
    echo json_encode(["error" => "Query failed: " . $conn->error]);
    exit;
}

$activities = [];
while ($row = $result->fetch_assoc()) {
    $activities[] = $row;
}

echo json_encode($activities);
$conn->close();
?>

