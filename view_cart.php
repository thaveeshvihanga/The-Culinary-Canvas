<?php
session_start();
require_once 'database.php';
$userId = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT menu.name, menu.price, cart.quantity FROM cart JOIN menu ON cart.menu_item_id = menu.id WHERE cart.user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
</head>
<body>
    <h2>Your Cart</h2>
    <table>
        <tr>
            <th>Item</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) : ?>
        <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= number_format($row['price'], 2) ?></td>
            <td><?= htmlspecialchars($row['quantity']) ?></td>
            <td><?= number_format($row['price'] * $row['quantity'], 2) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

