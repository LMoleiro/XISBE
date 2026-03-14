<?php
// Start session
session_start();

// Connect to database
$conn = new mysqli("db.tecnico.ulisboa.pt", "ist1106966", "ryhb6550", "ist1106966");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if the email already exists
    $sql = "SELECT * FROM utilizadores WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "Email already registered!";
    } else {
        // If email doesn't exist, insert new user into the database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $insert_sql = "INSERT INTO utilizadores (email, password) VALUES ('$email', '$hashed_password')";

        if ($conn->query($insert_sql) === TRUE) {
            // Start session for the newly registered user
            $_SESSION['email'] = $email;

            // Redirect to successtemplate.html after successful signup
            header("Location: success.html");
            exit(); // Don't forget to stop further code execution after the redirect
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

$conn->close();
?>


