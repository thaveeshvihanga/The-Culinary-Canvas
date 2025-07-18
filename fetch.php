<?php
require "database.php"; // Ensure this points to your database connection file

$query = "SELECT id, name, description, price, image_url FROM menu";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $counter = 0; // Initialize a counter
    echo '<div class="menu-container">'; // Start container for all rows

    while ($row = $result->fetch_assoc()) {
        // Start a new row after every 4 products
        if ($counter % 4 == 0) {
            if ($counter > 0) echo '</div>'; // Close previous row
            echo '<div class="menu-row">'; // Start a new row
        }

        // Output each product
        echo '
        <div class="menu-item">
            <img src="' . htmlspecialchars($row['image_url']) . '" alt="Dish Image">
            <h3>' . htmlspecialchars($row['name']) . '</h3>
            <p>' . htmlspecialchars($row['description']) . '</p>
            <p class="price">Rs. ' . htmlspecialchars(number_format($row['price'], 2)) . '</p>
            <button onclick="addToCart(' . htmlspecialchars($row['id']) . ')">Add to Cart</button>
        </div>';

        $counter++;
    }

    echo '</div>'; // Close the last row
    echo '</div>'; // Close the container
} else {
    echo "No menu items found.";
}

$conn->close();
?>

<script>
    function addToCart(menuItemId) {
        const userId = 1; // Replace with actual user ID logic if required
        fetch('add_to_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ menu_item_id: menuItemId, user_id: userId }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Item added to cart successfully!');
                } else {
                    alert('Failed to add item to cart: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while adding to cart.');
            });
    }
</script>
