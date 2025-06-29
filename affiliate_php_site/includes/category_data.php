<?php
// Category data functions (MySQL version)

require_once 'db_connect.php'; // For $db_connection

// Function to get all categories from the database
function getAllCategories() {
    global $db_connection;
    $categories = [];

    $sql = "SELECT category_id, category_name FROM categories ORDER BY category_name ASC";

    $result = mysqli_query($db_connection, $sql);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Storing as an associative array might be useful if we need category_id later
            $categories[] = $row;
        }
        mysqli_free_result($result);
    } else {
        // Handle query error - in a real app, log this
        // For now, returning an empty array or could echo an error for development
        error_log("MySQL Error: " . mysqli_error($db_connection));
        return []; // Return empty array on failure
    }

    return $categories;
}

// Example of how you might get just category names if that's all that's needed often:
function getAllCategoryNames() {
    global $db_connection;
    $categoryNames = [];

    $sql = "SELECT category_name FROM categories ORDER BY category_name ASC";

    $result = mysqli_query($db_connection, $sql);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $categoryNames[] = $row['category_name'];
        }
        mysqli_free_result($result);
    } else {
        error_log("MySQL Error: " . mysqli_error($db_connection));
        return [];
    }
    return $categoryNames;
}

// $siteCategories variable might not be needed globally anymore if pages fetch directly.
// However, if used by multiple non-page scripts, it could be populated:
// $siteCategories = getAllCategories(); // now returns array of assoc arrays
?>
