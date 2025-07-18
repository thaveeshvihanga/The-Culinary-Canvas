<?php
// Include the database connection file
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize form data
    $username = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check if fields are empty
    if (empty($username) || empty($email) || empty($password)) {
        echo "<script>
                alert('All fields are required!');
                window.history.back(); // Go back to the previous page
              </script>";
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>
                alert('Invalid email format!');
                window.history.back(); // Go back to the previous page
              </script>";
        exit;
    }

    function custom_password_hash($password) {
      return crypt($password, '$2y$10$' . substr(md5(mt_rand()), 0, 22));
  }

    // Hash the password
    $hashed_password = custom_password_hash($password);

    // Prepare SQL statement
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    // Execute statement and check success
    if ($stmt->execute()) {
        echo "<script>
                alert('Registration successful!');
                window.location.href = 'login.html'; // Redirect to the login page or another page
              </script>";
    } else {
        echo "<script>
                alert('Error: " . $stmt->error . "');
                window.history.back(); // Go back to the previous page
              </script>";
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
