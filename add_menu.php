<?php

require "database.php";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    // Handle file upload (optional)
    $image_url = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image_url = 'uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image_url);
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO menu (item_name, description, price, category, image_url) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdss", $item_name, $description, $price, $category, $image_url);

    if ($stmt->execute()) {
        echo "Menu item added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
