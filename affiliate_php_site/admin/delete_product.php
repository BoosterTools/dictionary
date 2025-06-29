<?php
session_start();
require_once '../includes/db_connect.php';

// Check if product ID is provided
if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "Invalid product ID or ID not provided.";
    header("Location: index.php");
    exit();
}

$product_id = (int)$_GET['id'];

// In a real application, add CSRF token validation here for security.

if ($product_id > 0) {
    $sql = "DELETE FROM products WHERE product_id = ?";
    $stmt = mysqli_prepare($db_connection, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $product_id);

        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $_SESSION['message'] = "Product (ID: $product_id) deleted successfully.";
            } else {
                $_SESSION['error'] = "Product (ID: $product_id) not found or already deleted.";
            }
        } else {
            $_SESSION['error'] = "Error deleting product: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['error'] = "Database error (prepare): " . mysqli_error($db_connection);
    }
} else {
    $_SESSION['error'] = "Invalid product ID specified.";
}

header("Location: index.php");
exit();
?>
