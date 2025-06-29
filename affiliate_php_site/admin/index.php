<?php
// Admin - Product List
session_start(); // For potential messages later

require_once '../includes/db_connect.php';
require_once '../includes/product_functions.php';

$products = getAllProductsDB();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Product Management</title>
    <link rel="stylesheet" href="../css/style.css"> <!-- Link to main site's CSS -->
    <style>
        /* Basic Admin Table Styling */
        .admin-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .admin-table th, .admin-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .admin-table th { background-color: #f2f2f2; }
        .admin-table td a { margin-right: 10px; }
        .admin-actions a { display: inline-block; margin-bottom:20px; padding:10px 15px; background-color:#007bff; color:white; text-decoration:none; border-radius:5px; }
        .admin-actions a:hover { background-color:#0056b3; }
        .admin-container { max-width: 1200px; margin: 20px auto; padding: 20px; background-color:#fff; border-radius:8px; box-shadow: 0 2px 15px rgba(0,0,0,0.08); }
        nav.admin-nav { background-color: #343a40; padding: 10px 0; text-align: center; margin-bottom: 20px;}
        nav.admin-nav a { color: white; margin: 0 15px; text-decoration: none; font-weight: bold; }
        nav.admin-nav a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="admin-container">
        <header>
            <h1>Admin - Product Management</h1>
        </header>
        <nav class="admin-nav">
            <a href="index.php">Manage Products</a>
            <a href="add_product.php">Add New Product</a>
            <a href="../index.php" target="_blank">View Live Site</a>
        </nav>
        <main>
            <h2>All Products</h2>
            <div class="admin-actions">
                <a href="add_product.php">Add New Product</a>
            </div>

            <?php if (isset($_SESSION['message'])): ?>
                <p style="color: green;"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <p style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
            <?php endif; ?>

            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($product['product_id']); ?></td>
                                <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                                <td><?php echo htmlspecialchars($product['category']); // Category name from JOIN ?></td>
                                <td><?php echo htmlspecialchars($product['currency'] . ' ' . $product['price']); ?></td>
                                <td>
                                    <a href="edit_product.php?id=<?php echo htmlspecialchars($product['product_id']); ?>">Edit</a>
                                    <a href="delete_product.php?id=<?php echo htmlspecialchars($product['product_id']); ?>" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No products found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>
