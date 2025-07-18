<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Replace with your database password
$database = "culinary_canvas"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the `cart` table
$sql = "
    SELECT 
        cart.id, 
        menu.name AS menu_name, 
        menu.price, 
        cart.quantity, 
        (menu.price * cart.quantity) AS total_price 
    FROM cart 
    INNER JOIN menu ON cart.menu_item_id = menu.id"; // Adjust column names if needed
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link href="https://fonts.googleapis.com/css?family=Noto+Serif&display=swap" rel="stylesheet" />
    <style>
        body {
            margin: 0;
            font-family: 'Noto Serif', serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #fffaf3;
        }

        header {
            background-color: #f2a18c;
            width: 100%;
            padding: 15px 20px;
            position: fixed;
            top: 0;
            z-index: 10;
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        header nav {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        header nav a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
        }

        header nav a:hover {
            text-decoration: underline;
        }

        main {
            flex: 1;
            margin-top: 120px;
            padding: 20px;
        }

        .cart-container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            background: #f8e8df;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #f27457;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #f2a18c;
            color: white;
        }

        .quantity-controls {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .quantity-controls button {
            padding: 5px 10px;
            background-color: #f2a18c;
            border: none;
            color: white;
            font-weight: bold;
            cursor: pointer;
            border-radius: 5px;
        }

        .quantity-controls button:hover {
            background-color: #f27457;
        }

        .confirm-order-btn {
            display: block;
            width: 100%;
            text-align: center;
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #f2a18c;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
            border-radius: 5px;
        }

        .confirm-order-btn:hover {
            background-color: #f27457;
        }

        footer {
            text-align: center;
            background-color: #f2a18c;
            color: white;
            padding: 10px 0;
            margin-top: auto;
        }
    </style>
</head>
<body>
<header>
    <nav>
        <a href="index.php">Home</a>
        <a href="home.php">Menu</a>
        <a href="#">Contact Us</a>
    </nav>
</header>

<main>
    <div class="cart-container">
        <h2>Your Cart</h2>
        <table>
            <thead>
                <tr>
                    <th>Cart ID</th>
                    <th>Menu Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $grandTotal = 0;
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $grandTotal += $row['total_price'];
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['menu_name']}</td>
                                <td>{$row['price']}</td>
                                <td>
                                    <div class='quantity-controls'>
                                        <button onclick='decrementQuantity({$row['id']})'>-</button>
                                        <input type='text' id='quantity-{$row['id']}' value='{$row['quantity']}' readonly style='width: 40px; text-align: center;' />
                                        <button onclick='incrementQuantity({$row['id']})'>+</button>
                                    </div>
                                </td>
                                <td id='total-price-{$row['id']}'>{$row['total_price']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Your cart is empty.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <p class="total-price">Grand Total: <span id="grand-total"><?php echo $grandTotal; ?></span></p>
        <button class="confirm-order-btn" onclick="confirmOrder()"><a href="payment.html" >Confirm Order</a></button>
    </div>
</main>

<footer>
    <p>&copy; 2024 The Culinary Canvas. All Rights Reserved.</p>
</footer>

<script>
    function incrementQuantity(id) {
        const quantityInput = document.getElementById(`quantity-${id}`);
        const totalPriceCell = document.getElementById(`total-price-${id}`);
        const pricePerItem = parseFloat(totalPriceCell.innerText) / parseInt(quantityInput.value);
        quantityInput.value = parseInt(quantityInput.value) + 1;
        totalPriceCell.innerText = (pricePerItem * parseInt(quantityInput.value)).toFixed(2);
        updateGrandTotal();
    }

    function decrementQuantity(id) {
        const quantityInput = document.getElementById(`quantity-${id}`);
        const totalPriceCell = document.getElementById(`total-price-${id}`);
        const pricePerItem = parseFloat(totalPriceCell.innerText) / parseInt(quantityInput.value);
        if (parseInt(quantityInput.value) > 1) {
            quantityInput.value = parseInt(quantityInput.value) - 1;
            totalPriceCell.innerText = (pricePerItem * parseInt(quantityInput.value)).toFixed(2);
            updateGrandTotal();
        }
    }

    function updateGrandTotal() {
        let grandTotal = 0;
        const totalPrices = document.querySelectorAll('[id^="total-price-"]');
        totalPrices.forEach(price => {
            grandTotal += parseFloat(price.innerText);
        });
        document.getElementById("grand-total").innerText = grandTotal.toFixed(2);
    }

    function confirmOrder() {
        const grandTotal = document.getElementById("grand-total").innerText;
        alert(`Your order has been confirmed! Grand Total: $${grandTotal}`);
    }
</script>
</body>
</html>

<?php
$conn->close();
?>
