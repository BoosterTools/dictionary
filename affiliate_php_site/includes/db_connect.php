<?php
// Database connection logic

define('DB_SERVER', 'localhost'); // Replace with your DB server
define('DB_USERNAME', 'root');    // Replace with your DB username
define('DB_PASSWORD', '');        // Replace with your DB password
define('DB_NAME', 'affiliate_db'); // Replace with your DB name

// Attempt to connect to MySQL database using MySQLi
$db_connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if($db_connection === false){
    // In a real application, you might log this error or display a more user-friendly message.
    // For development, die() is okay to see the error immediately.
    die("ERROR: Could not connect to database. " . mysqli_connect_error());
}

// Optional: Set character set to utf8mb4 for full Unicode support
mysqli_set_charset($db_connection, "utf8mb4");

// The $db_connection variable will be used by other scripts to interact with the database.
// No need to echo messages here in a production script.
?>
