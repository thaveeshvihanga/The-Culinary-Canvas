<?php
include 'database.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $productName = trim($_POST['fname']);
    $productDescription = trim($_POST['desc']);
    $productPrice = trim($_POST['price']);

    // Handle the image upload
    $uploadDirectory = "uploads/"; // Folder to store uploaded images
    $imageName = $_FILES['id']['name']; // File name
    $imageTmpName = $_FILES['id']['tmp_name']; // Temporary file path
    $imagePath = $uploadDirectory . basename($imageName); // Full path

    // Validate the form data
    if (empty($productName) || empty($productPrice) || empty($imageName)) {
        echo "<script>alert('Please fill in all required fields!');</script>";
        exit;
    }

    // Move uploaded image to the desired directory
    if (move_uploaded_file($imageTmpName, $imagePath)) {
        // Insert data into the database
        $sql = "INSERT INTO menu (name, description, price, image) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssds", $productName, $productDescription, $productPrice, $imagePath);

        if ($stmt->execute()) {
            echo "<script>alert('Product added successfully!');</script>";
        } else {
            echo "<script>alert('Error adding product: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Failed to upload the image!');</script>";
    }
}

$conn->close();
?>
