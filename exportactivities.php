<?php
// Start session
session_start();
include 'assets/database/database.php'; // Your DB connection

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="activities_enrollments.xls"');
header('Cache-Control: max-age=0');

// Query to get the data
$sql = "SELECT a.nome AS activity_name, u.email AS user_email
        FROM atividades a
        JOIN Inscricoes i ON a.id = i.atividade
        JOIN utilizadores u ON i.id_utilizador = u.id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output column headers
    echo "Activity Name\tUser Email\n"; // Tab-separated headers

    // Output rows from the result set
    while ($row = $result->fetch_assoc()) {
        echo $row['activity_name'] . "\t" . $row['user_email'] . "\n";
    }
} else {
    echo "No data found.";
}

// Close the connection
$conn->close();
?>
