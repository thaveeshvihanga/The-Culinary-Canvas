<?php
require "database.php"; // Ensure this points to your database connection file

$query = "SELECT id,name, description, price, image_url FROM menu";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '
        <div class="menu-item">
            <img src="' . htmlspecialchars($row['image_url']) . '" alt="Dish Image">
            <h3>' . htmlspecialchars($row['name']) . '</h3>
            <p>' . htmlspecialchars($row['description']) . '</p>
            <p class="price">Rs. ' . htmlspecialchars(number_format($row['price'], 2)) . '</p>
            
        </div>';
    }
} else {
    echo "No menu items found.";
}

$conn->close();
?>
