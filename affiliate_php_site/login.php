<?php
session_start();
require_once 'includes/db_connect.php';

$identifier = ""; // Can be username or email
$errors = [];
$success_message = "";

// If user is already logged in, redirect them
if (isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Or a user dashboard page
    exit();
}

// Check for success message from registration
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']); // Clear message after displaying
}
// Check for logged_out message
if (isset($_GET['logged_out']) && $_GET['logged_out'] == 'true') {
    $success_message = "You have been successfully logged out.";
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifier = trim($_POST['identifier']); // Username or Email
    $password = $_POST['password'];

    if (empty($identifier)) { $errors[] = "Username or Email is required."; }
    if (empty($password)) { $errors[] = "Password is required."; }

    if (empty($errors)) {
        // Check if identifier is email or username
        $field_type = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $sql = "SELECT user_id, username, password_hash, role FROM users WHERE $field_type = ?";
        $stmt = mysqli_prepare($db_connection, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $identifier);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);

            if ($user && password_verify($password, $user['password_hash'])) {
                // Password is correct, start session
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_role'] = $user['role'];

                // Redirect to a logged-in page (e.g., homepage or dashboard)
                header("Location: index.php");
                exit();
            } else {
                $errors[] = "Invalid username/email or password.";
            }
        } else {
            $errors[] = "Database error (login attempt): " . mysqli_error($db_connection);
        }
    }
}

include 'includes/header.php'; // Include header
?>

<section class="content-area auth-form">
    <h2>User Login</h2>

    <?php if (!empty($success_message)): ?>
        <div class="success-message">
            <?php echo htmlspecialchars($success_message); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="errors-container">
            <h4>Login failed:</h4>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="form-group">
            <label for="identifier">Username or Email</label>
            <input type="text" id="identifier" name="identifier" value="<?php echo htmlspecialchars($identifier); ?>" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn-submit">Login</button>
    </form>
    <p class="auth-switch">Don't have an account? <a href="register.php">Register here</a>.</p>
    <?php // Later: Add a "Forgot Password?" link ?>
</section>

<?php
include 'includes/footer.php'; // Include footer
?>
<style>
    /* Reusing some styles from register.php for consistency */
    .auth-form { max-width: 500px; margin: 20px auto; }
    .errors-container { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
    .errors-container h4 { margin-top: 0; }
    .errors-container ul { padding-left: 20px; margin-bottom: 0; }
    .success-message { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group input[type="password"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
    .btn-submit { display: inline-block; padding:10px 20px; background-color:#007bff; color:white; text-decoration:none; border-radius:5px; border:none; cursor:pointer; font-size: 1rem; }
    .btn-submit:hover { background-color:#0056b3; }
    .auth-switch { margin-top: 20px; text-align: center; }
</style>
