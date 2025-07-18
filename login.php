<?php
session_start();
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form input values
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($email) || empty($password)) {
        echo "<script>alert('Email and password are required!');</script>";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format!');</script>";
        exit;
    }

    // Prepare SQL to fetch the user's hashed password
    $sql = "SELECT user_id, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('MySQL prepare error: ' . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $hashed_password_from_db = $row['password'];
        $user_id = $row['user_id'];

        // Compare the entered password with the stored hashed password
        if (crypt($password, $hashed_password_from_db) === $hashed_password_from_db) {
            // Password matches
            $_SESSION['user_email'] = $email;
            $_SESSION['user_id'] = $user_id;

            // Redirect to the home page or dashboard
            header("Location: home.php");
            exit;
        } else {
            // Invalid password
            echo "<script>alert('Invalid password!');</script>";
        }
    } else {
        // User not found
        echo "<script>alert('No user found with this email!');</script>";
    }

    $stmt->close();
}

$conn->close();
?>
