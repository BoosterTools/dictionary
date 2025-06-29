<?php
// Product data functions (MySQL version)

require_once 'db_connect.php'; // For $db_connection

// Function to get all products, joining with categories table
function getAllProductsDB() {
    global $db_connection;
    $products = [];

    $sql = "SELECT p.*, c.category_name
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.category_id
            ORDER BY p.date_added DESC"; // Or p.product_name ASC, etc.

    $result = mysqli_query($db_connection, $sql);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            // The 'category' key will now hold the category name from the JOIN
            // The original product_data.php used 'category' for the name.
            // If there's no category_id or no match, category_name will be NULL.
            $row['category'] = $row['category_name'];
            unset($row['category_name']); // Avoid confusion if 'category_name' key is directly used anywhere else
            $products[] = $row;
        }
        mysqli_free_result($result);
    } else {
        error_log("MySQL Error in getAllProductsDB: " . mysqli_error($db_connection));
        return [];
    }
    return $products;
}

// Function to get a single product by ID, joining with categories table
function getProductByIdDB($productId) {
    global $db_connection;

    if (!is_numeric($productId) || $productId <= 0) {
        return null;
    }

    $sql = "SELECT p.*, c.category_name
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.category_id
            WHERE p.product_id = ?";

    $stmt = mysqli_prepare($db_connection, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $productId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $product = mysqli_fetch_assoc($result);
            $product['category'] = $product['category_name'];
            unset($product['category_name']);
            mysqli_free_result($result);
            mysqli_stmt_close($stmt);
            return $product;
        }
        mysqli_stmt_close($stmt);
    } else {
        error_log("MySQL Error in getProductByIdDB (prepare): " . mysqli_error($db_connection));
    }
    return null;
}

// Function to get products by category name
function getProductsByCategoryNameDB($categoryName) {
    global $db_connection;
    $products = [];

    $sql = "SELECT p.*, c.category_name
            FROM products p
            JOIN categories c ON p.category_id = c.category_id
            WHERE c.category_name = ?
            ORDER BY p.date_added DESC";

    $stmt = mysqli_prepare($db_connection, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $categoryName);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            $row['category'] = $row['category_name'];
            unset($row['category_name']);
            $products[] = $row;
        }
        mysqli_free_result($result);
        mysqli_stmt_close($stmt);
    } else {
        error_log("MySQL Error in getProductsByCategoryNameDB (prepare): " . mysqli_error($db_connection));
    }
    return $products;
}
?>
