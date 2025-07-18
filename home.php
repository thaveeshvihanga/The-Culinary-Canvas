<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Noto+Serif&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Lovers+Quarrel&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Arial&display=swap" rel="stylesheet" />
    <link href="main1.css" rel="stylesheet" />
    <title>Menu</title>
    <style>
        .menu-item { margin: 20px; border: 1px solid #ddd; padding: 10px; }
        img { max-width: 100px; height: auto; }
        h3 { margin: 5px 0; }
        .price { font-weight: bold; color: green; }

        header {
            background-color: #f2a18c;
            width: 100%;
            padding: 15px 20px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 10;
            display: flex;
            justify-content:flex-start;
            align-items: center;
        }

        header .logo {
            font-family: 'Noto Serif';
            font-size: 25px;
            color: white;
        }

        header nav {
            display: flex;
            gap: 20px;
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


        footer {
            text-align: center;
            background-color: #f2a18c;
            color: white;
            padding: 10px 0;
        }

        footer a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
            font-weight: bold;
        }

        footer a:hover {
            text-decoration: underline;
        }


    </style>
    <title>The Culinary Canvas</title>
</head>
<body>

    
    <header>
        
            <nav>
                <a href="index.html">Home</a>
                <a href="self.aboutus-title">About Us</a>
                <a href="home.php">Menu</a>
                <a href="login1.html">Cart</a>
            </nav>
    </header>

    <div class="main-container">
        <!-- Decorative Elements -->
        <div class="background-layer"></div>
        <div class="image-overlay"></div>
        <div class="featured-image"></div>
        <div class="highlight-section"></div>

        <!-- Page Title -->
        <span class="title">The Culinary Canvas</span>

        <!-- Menu Section -->
        <span class="menu-title">The Menu</span>
        <div id="menu-container">
            <div id="menu">
                <?php include 'fetch.php'; ?> <!-- Include the PHP Script -->
            </div>
        </div>



    </div>

    <footer>
    <p>&copy; 2024 The Culinary Canvas. All Rights Reserved.</p>

 </footer>

    <script>
        function addToCart(id) {
            alert(`Item with ID ${id} added to cart!`);
        }
    </script>
</body>
</html>

