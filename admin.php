<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin.css"/>
    <title>Admin Panel - Culinary Canvas</title>
    <style>
        body {
            margin: 0;
            font-family: 'Noto Serif', serif;
            background-color: #fffaf3;
        }

        .nav-bar {
            background-color: #f27457;
            padding: 15px 20px;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }

        .nav-bar span {
            font-size: 18px;
            color: #ffd1b3;
        }

        .together {
            display: flex;
        }

        .sidebar {
            width: 250px;
            background-color: #f8e8df;
            height: 100vh;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
            padding-top: 20px;
        }

        .sidebar-options {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .sidebar-option {
            padding: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .sidebar-option:hover {
            background-color: #f2a18c;
            color: white;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            background: #fffaf3;
        }

        .section {
            display: none;
        }

        .active {
            display: block;
        }

        form p, table th {
            font-weight: bold;
        }

        .add-form, .list-table, .order-details-table {
            width: 100%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        input, textarea, button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            background-color: #f27457;
            color: white;
            font-weight: bold;
            cursor: pointer;
            border: none;
        }

        button:hover {
            background-color: #d96349;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        img {
            max-width: 50px;
            max-height: 50px;
        }

        .action-buttons button {
            margin-right: 10px;
            padding: 5px 10px;
        }
    </style>
</head>
<body>
    <nav class="nav-bar">
        Culinary Canvas <span>Admin Panel</span>
    </nav>

    <div class="together">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-options">
                <div class="sidebar-option" onclick="showSection('add-item')">Add Items</div>
                <div class="sidebar-option" onclick="showSection('list')">List Items</div>
                <div class="sidebar-option" onclick="showSection('orders')">Orders</div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Add Items -->
            <div class="section" id="add-item">
                <form action="add_items_admin.php" method="POST" class="add-form" enctype="multipart/form-data">
                    <p>Upload Image</p>
                    <input type="file" name="image_url" required>

                    <p>Product Name</p>
                    <input type="text" name="name" placeholder="Enter product name" required>

                    <p>Product Description</p>
                    <textarea name="description" rows="4" placeholder="Enter product description" required></textarea>

                    <p>Product Price</p>
                    <input type="text" name="price" placeholder="Enter product price (Rs.)" required>

                    <button type="submit">Add Product</button>
                </form>
            </div>

            <!-- List Items -->
            <div class="section" id="list">
                <table class="list-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price (Rs.)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require 'database.php';
                        $query = "SELECT * FROM menu";
                        $result = $conn->query($query);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td><img src="' . htmlspecialchars($row['image_url']) . '" alt="Product Image"></td>';
                                echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['description']) . '</td>';
                                echo '<td>' . number_format($row['price'], 2) . '</td>';
                                echo '<td class="action-buttons">';
                                echo '<form action="edit_item.php" method="POST" style="display:inline;">
                                      <input type="hidden" name="id" value="' . $row['id'] . '">
                                      <button type="submit">Edit</button>
                                      </form>';
                                echo '<form action="delete_item.php" method="POST" style="display:inline;">
                                      <input type="hidden" name="id" value="' . $row['id'] . '">
                                      <button type="submit">Delete</button>
                                      </form>';
                                echo '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="5">No items found.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Orders -->
            <div class="section" id="orders">

            <p> 
                        Order No  :  00001 <br>
                        User Name :  Jhon silwa <br>
                        Address   :  No 20, Galle Rd, Dehiwala. <br>
                        Phn No    :  071203040 <br><br>
                    </p>

                <table class="order-details-table">


                    <thead>
                        <tr>
                            <th>Cart ID</th>
                            <th>Quantity</th>
                            <th>Item Name</th>
                            <th>Item Price (Rs.)</th>
                            <th>Total Price (Rs.)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "
                        SELECT c.id AS cart_id, c.quantity, m.name, m.price, (c.quantity * m.price) AS total_price
                        FROM cart c
                        JOIN menu m ON c.menu_item_id = m.id";
                        $result = $conn->query($query);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . $row['cart_id'] . '</td>';
                                echo '<td>' . $row['quantity'] . '</td>';
                                echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                                echo '<td>' . number_format($row['price'], 2) . '</td>';
                                echo '<td>' . number_format($row['total_price'], 2) . '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="5">No orders found.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>

<!-- Add Process Order button -->
<div style="text-align: right; margin-top: 20px;">
    <form action="process_orders.php" method="POST">
        <button type="submit" style="
            background-color: #f27457; 
            color: white; 
            padding: 10px 20px; 
            font-size: 16px; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer;">Process Orders</button>
    </form>
</div>
            </div>
        </div>
    </div>

    <script>
        // JavaScript function to display the selected section
        function showSection(sectionId) {
            const sections = document.querySelectorAll('.section');
            sections.forEach(section => {
                section.classList.remove('active');
            });
            document.getElementById(sectionId).classList.add('active');
        }

        // Show 'Add Items' by default
        document.addEventListener('DOMContentLoaded', () => {
            showSection('add-item');
        });
    </script>
</body>
</html>
