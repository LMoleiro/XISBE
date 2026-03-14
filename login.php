<?php
// Start session
session_start();

// Connect to database
$conn = new mysqli("db.tecnico.ulisboa.pt", "ist1106966", "ryhb6550", "ist1106966");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input to avoid SQL injection
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Use specific columns in SELECT query
    $sql = "SELECT id, email, password FROM utilizadores WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // Fetch user data
        $row = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $row['password'])) {
            // Password is correct, start session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['email'] = $row['email'];

            // Redirect to index.html after successful login
            header("Location: index.html");
            exit();  // Stop further code execution
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "No user found with that email!";
    }
}

$conn->close();
?>
