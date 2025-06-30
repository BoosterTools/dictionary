<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php?redirect_url=" . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

// User is logged in, display dashboard content
include 'includes/header.php';
?>

<section class="content-area">
    <h2>User Dashboard</h2>
    <p>Hello, <strong><?php echo htmlspecialchars($_SESSION["username"]); ?></strong>!</p>
    <p>Welcome to your dashboard. This page is only accessible to logged-in users.</p>

    <h3>Your Details:</h3>
    <ul>
        <li>User ID: <?php echo htmlspecialchars($_SESSION["user_id"]); ?></li>
        <li>Username: <?php echo htmlspecialchars($_SESSION["username"]); ?></li>
        <li>Role: <?php echo htmlspecialchars($_SESSION["user_role"]); ?></li>
    </ul>

    <p><a href="index.php">Go to Homepage</a></p>
    <p><a href="logout.php">Logout</a></p>

    <?php
    // Add more dashboard-specific content here later, e.g.:
    // - List of saved/bookmarked products
    // - Affiliate link click history (if implemented)
    // - Comment and review history (if implemented)
    ?>
</section>

<?php
include 'includes/footer.php';
?>
