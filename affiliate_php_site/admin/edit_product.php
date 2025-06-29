<?php
session_start();
require_once '../includes/db_connect.php';
require_once '../includes/product_functions.php'; // To get product by ID
require_once '../includes/category_data.php';   // To get categories for dropdown

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = null;
$errors = [];

if ($product_id <= 0) {
    $_SESSION['error'] = "Invalid product ID.";
    header("Location: index.php");
    exit();
}

// Fetch existing product
$product = getProductByIdDB($product_id);
if (!$product) {
    $_SESSION['error'] = "Product not found.";
    header("Location: index.php");
    exit();
}

// Initialize form variables with existing product data
$product_name = $product['product_name'];
$description = $product['description'];
$price = $product['price'];
$currency = $product['currency'];
$image_url = $product['image_url'];
$affiliate_link = $product['affiliate_link'];
$category_id = $product['category_id']; // This is the ID from the products table
$product_type = $product['product_type'];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $product_name = trim($_POST['product_name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $currency = trim($_POST['currency']);
    $image_url = trim($_POST['image_url']);
    $affiliate_link = trim($_POST['affiliate_link']);
    $category_id = trim($_POST['category_id']);
    $product_type = trim($_POST['product_type']);

    if (empty($product_name)) { $errors[] = "Product name is required."; }
    if (empty($description)) { $errors[] = "Description is required."; }
    if (empty($price) || !is_numeric($price) || $price < 0) { $errors[] = "Valid price is required."; }
    if (empty($affiliate_link)) { $errors[] = "Affiliate link is required."; }
    if (empty($category_id) || !is_numeric($category_id)) { $errors[] = "Category is required."; }
    if (empty($product_type)) { $errors[] = "Product type is required."; }
    if (empty($currency)) { $currency = 'USD'; }

    if (empty($errors)) {
        $sql = "UPDATE products SET
                    product_name = ?,
                    description = ?,
                    price = ?,
                    currency = ?,
                    image_url = ?,
                    affiliate_link = ?,
                    category_id = ?,
                    product_type = ?
                WHERE product_id = ?";

        $stmt = mysqli_prepare($db_connection, $sql);
        if ($stmt) {
            // Correct type string: "ssdsssisi" (s,s,d,s,s,s,i,s,i)
            mysqli_stmt_bind_param($stmt, "ssdsssisi",
                $product_name,
                $description,
                $price,
                $currency,
                $image_url,
                $affiliate_link,
                $category_id,
                $product_type,
                $product_id
            );

            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['message'] = "Product updated successfully!";
                header("Location: index.php");
                exit();
            } else {
                $errors[] = "Failed to update product: " . mysqli_stmt_error($stmt);
            }
            mysqli_stmt_close($stmt);
        } else {
            $errors[] = "Database error (prepare): " . mysqli_error($db_connection);
        }
    }
}

$allCategories = getAllCategories(); // Fetch categories for the dropdown
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Edit Product</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .admin-container { max-width: 800px; margin: 20px auto; padding: 20px; background-color:#fff; border-radius:8px; box-shadow: 0 2px 15px rgba(0,0,0,0.08); }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group input[type="url"],
        .form-group textarea,
        .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .form-group textarea { min-height: 100px; }
        .btn-submit { display: inline-block; padding:10px 20px; background-color:#28a745; color:white; text-decoration:none; border-radius:5px; border:none; cursor:pointer; }
        .btn-submit:hover { background-color:#218838; }
        .errors { color: red; margin-bottom: 15px; list-style: none; padding:0; }
        nav.admin-nav { background-color: #343a40; padding: 10px 0; text-align: center; margin-bottom: 20px;}
        nav.admin-nav a { color: white; margin: 0 15px; text-decoration: none; font-weight: bold; }
        nav.admin-nav a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="admin-container">
        <header>
            <h1>Admin - Edit Product (ID: <?php echo htmlspecialchars($product_id); ?>)</h1>
        </header>
        <nav class="admin-nav">
            <a href="index.php">Manage Products</a>
            <a href="add_product.php">Add New Product</a>
            <a href="../index.php" target="_blank">View Live Site</a>
        </nav>
        <main>
            <?php if (!empty($errors)): ?>
                <ul class="errors">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <form action="edit_product.php?id=<?php echo htmlspecialchars($product_id); ?>" method="POST">
                <div class="form-group">
                    <label for="product_name">Product Name</label>
                    <input type="text" id="product_name" name="product_name" value="<?php echo htmlspecialchars($product_name); ?>" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" required><?php echo htmlspecialchars($description); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" id="price" name="price" step="0.01" value="<?php echo htmlspecialchars($price); ?>" required>
                </div>
                <div class="form-group">
                    <label for="currency">Currency</label>
                    <input type="text" id="currency" name="currency" value="<?php echo htmlspecialchars($currency); ?>" placeholder="e.g., USD, EUR" required>
                </div>
                <div class="form-group">
                    <label for="image_url">Image URL (optional)</label>
                    <input type="url" id="image_url" name="image_url" value="<?php echo htmlspecialchars($image_url); ?>" placeholder="https://example.com/image.jpg">
                </div>
                <div class="form-group">
                    <label for="affiliate_link">Affiliate Link</label>
                    <input type="url" id="affiliate_link" name="affiliate_link" value="<?php echo htmlspecialchars($affiliate_link); ?>" placeholder="https://example.com/product-link" required>
                </div>
                <div class="form-group">
                    <label for="category_id">Category</label>
                    <select id="category_id" name="category_id" required>
                        <option value="">-- Select Category --</option>
                        <?php foreach ($allCategories as $category): // $category is an assoc array ?>
                            <option value="<?php echo htmlspecialchars($category['category_id']); ?>" <?php echo ($category_id == $category['category_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['category_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="product_type">Product Type</label>
                    <select id="product_type" name="product_type" required>
                        <option value="">-- Select Type --</option>
                        <option value="Physical" <?php echo ($product_type == 'Physical') ? 'selected' : ''; ?>>Physical</option>
                        <option value="Digital" <?php echo ($product_type == 'Digital') ? 'selected' : ''; ?>>Digital</option>
                        <option value="Service" <?php echo ($product_type == 'Service') ? 'selected' : ''; ?>>Service</option>
                    </select>
                </div>
                <button type="submit" class="btn-submit">Update Product</button>
            </form>
        </main>
    </div>
</body>
</html>
