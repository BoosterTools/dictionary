<?php
session_start();
require_once 'includes/db_connect.php';

$username = "";
$email = "";
$errors = [];
$success_message = "";

// If user is already logged in, redirect them from register page
if (isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Or a user dashboard page
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    // Basic Validation
    if (empty($username)) { $errors[] = "Username is required."; }
    if (empty($email)) { $errors[] = "Email is required."; }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = "Invalid email format."; }
    if (empty($password)) { $errors[] = "Password is required."; }
    if (strlen($password) < 8) { $errors[] = "Password must be at least 8 characters long."; }
    if ($password !== $password_confirm) { $errors[] = "Passwords do not match."; }

    // Check if username or email already exists
    if (empty($errors)) {
        $sql_check = "SELECT user_id FROM users WHERE username = ? OR email = ?";
        $stmt_check = mysqli_prepare($db_connection, $sql_check);
        if ($stmt_check) {
            mysqli_stmt_bind_param($stmt_check, "ss", $username, $email);
            mysqli_stmt_execute($stmt_check);
            mysqli_stmt_store_result($stmt_check);
            if (mysqli_stmt_num_rows($stmt_check) > 0) {
                $errors[] = "Username or email already taken.";
            }
            mysqli_stmt_close($stmt_check);
        } else {
            $errors[] = "Database error (check existing user): " . mysqli_error($db_connection);
        }
    }

    // If no errors, proceed to hash password and insert user
    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $sql_insert = "INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, 'user')"; // Default role 'user'
        $stmt_insert = mysqli_prepare($db_connection, $sql_insert);
        if ($stmt_insert) {
            mysqli_stmt_bind_param($stmt_insert, "sss", $username, $email, $password_hash);
            if (mysqli_stmt_execute($stmt_insert)) {
                $_SESSION['success_message'] = "Registration successful! Please login.";
                header("Location: login.php");
                exit();
            } else {
                $errors[] = "Registration failed: " . mysqli_stmt_error($stmt_insert);
            }
            mysqli_stmt_close($stmt_insert);
        } else {
            $errors[] = "Database error (insert user): " . mysqli_error($db_connection);
        }
    }
}

// Include header
include 'includes/header.php';
?>

<section class="content-area auth-form">
    <h2>User Registration</h2>

    <?php if (!empty($errors)): ?>
        <div class="errors-container">
            <h4>Please correct the following errors:</h4>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="register.php" method="POST">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <small>Must be at least 8 characters long.</small>
        </div>
        <div class="form-group">
            <label for="password_confirm">Confirm Password</label>
            <input type="password" id="password_confirm" name="password_confirm" required>
        </div>
        <button type="submit" class="btn-submit">Register</button>
    </form>
    <p class="auth-switch">Already have an account? <a href="login.php">Login here</a>.</p>
</section>

<?php
// Include footer
include 'includes/footer.php';
?>
<style>
    .auth-form { max-width: 500px; margin: 20px auto; }
    .errors-container { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
    .errors-container h4 { margin-top: 0; }
    .errors-container ul { padding-left: 20px; margin-bottom: 0; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group input[type="password"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
    .form-group small { font-size: 0.85em; color: #6c757d; }
    .btn-submit { display: inline-block; padding:10px 20px; background-color:#007bff; color:white; text-decoration:none; border-radius:5px; border:none; cursor:pointer; font-size: 1rem; }
    .btn-submit:hover { background-color:#0056b3; }
    .auth-switch { margin-top: 20px; text-align: center; }
</style>
