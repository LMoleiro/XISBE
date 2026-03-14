<?php
session_start();
include 'assets/database/database.php';
header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Validate activity_id
$activity_id = isset($_POST['activity_id']) ? (int)$_POST['activity_id'] : 0;
if ($activity_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid activity ID']);
    exit;
}

// 1. Check if the activity exists
$stmt = $conn->prepare("SELECT id FROM atividades WHERE id = ?");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Query preparation failed: ' . $conn->error]);
    exit;
}
$stmt->bind_param("i", $activity_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Activity not found']);
    $stmt->close();
    exit;
}
$stmt->close();

// 2. Check if the user is already signed up (using "ii" if both are integers)
$stmt = $conn->prepare("SELECT 1 FROM Inscricoes WHERE id_utilizador = ? AND atividade = ?");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Query preparation failed: ' . $conn->error]);
    exit;
}
$stmt->bind_param("ii", $user_id, $activity_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // User is already signed up; proceed to unsubscribe
    $stmt->close();
    $stmt = $conn->prepare("DELETE FROM Inscricoes WHERE id_utilizador = ? AND atividade = ?");
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Query preparation failed: ' . $conn->error]);
        exit;
    }
    $stmt->bind_param("ii", $user_id, $activity_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Unsubscribed successfully', 'action' => 'unsubscribed']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error unsubscribing']);
    }
} else {
    // User is not signed up; proceed to subscribe
    $stmt->close();
    $stmt = $conn->prepare("INSERT INTO Inscricoes (id_utilizador, atividade) VALUES (?, ?)");
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Query preparation failed: ' . $conn->error]);
        exit;
    }
    $stmt->bind_param("ii", $user_id, $activity_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Signed up successfully', 'action' => 'signed_up']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error signing up']);
    }
}

$stmt->close();
$conn->close();
?>
