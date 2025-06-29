<?php
// Header content
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Affiliate Site</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="site-wrap"> <!-- Flex container for sticky footer -->
    <header>
        <h1><a href="index.php">My Affiliate Site</a></h1>
        <nav>
            <ul>
                <li><a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">Home</a></li>
                <li><a href="products.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : ''; ?>">Products</a></li>
                <li><a href="categories.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'categories.php' ? 'active' : ''; ?>">Categories</a></li>
            </ul>
        </nav>
    </header>
    <main>
