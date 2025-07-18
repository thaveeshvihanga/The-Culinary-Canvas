<!-- <?php

include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize form data
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check if fields are empty
    if (empty($email) || empty($password)) {
        echo "<script>
                alert('Both fields are required!');
                window.history.back();
              </script>";
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>
                alert('Invalid email format!');
                window.history.back();
              </script>";
        exit;
    }

    // Prepare SQL statement to fetch user by email
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Fetch user data
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            echo "<script>
                    alert('Login successful!');
                    window.location.href = 'C:\wamp\www\web programming\home.html'; // Redirect to dashboard or another page
                  </script>";
        } else {
            echo "<script>
                    alert('Incorrect password!');
                    window.history.back();
                  </script>";
        }
    } else {
        echo "<script>
                alert('No account found with this email!');
                window.history.back();
              </script>";
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?> -->



<?php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        echo "<script>alert('Email and password are required!');</script>";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format!');</script>";
        exit;
    }

    $sql = "SELECT password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];
        $user_id = $row['id']; 

        // Use password_verify or fallback if unavailable
        if (function_exists('password_verify')) {
            $isPasswordValid = password_verify($password, $hashed_password);
        } else {
            $isPasswordValid = crypt($password, $hashed_password) === $hashed_password;
        }

        if ($isPasswordValid) {
            session_start();
            $_SESSION['user_email'] = $email;  // Store email
            $_SESSION['user_id'] = $user_id;   // Store user ID
            header("Location: home.html");
            // Redirect or set session
        } else {
            echo "<script>alert('Invalid password!');</script>";
        }
    } else {
        echo "<script>alert('No user found with this email!');</script>";
    }

    $stmt->close();
}
$conn->close();
?>
