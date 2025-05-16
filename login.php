<?php
session_start();
include_once 'includes/config.php';
include_once 'includes/functions.php';

$errors = [];

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Process login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // Validate input
    if (empty($email)) {
        $errors[] = 'Email is required';
    }
    
    if (empty($password)) {
        $errors[] = 'Password is required';
    }
    
    // If no validation errors, attempt login
    if (empty($errors)) {
        $query = "SELECT id, name, email, password, is_admin FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['is_admin'] = $user['is_admin'];
                
                // Redirect based on user type
                if ($user['is_admin'] == 1) {
                    header('Location: admin/index.php');
                    exit();
                } else {
                    // Redirect to intended page or home
                    $redirect = isset($_SESSION['redirect_url']) ? $_SESSION['redirect_url'] : 'index.php';
                    unset($_SESSION['redirect_url']);
                    header('Location: ' . $redirect);
                    exit();
                }
            } else {
                $errors[] = 'Invalid email or password';
            }
        } else {
            $errors[] = 'Invalid email or password';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - 88 HOTEL</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include_once 'includes/header.php'; ?>
    
    <main>
        <section class="auth-section">
            <div class="container">
                <div class="auth-container">
                    <div class="auth-image">
                        <img src="assets/images/hotel-lobby.jpg" alt="88 HOTEL Lobby">
                    </div>
                    <div class="auth-form">
                        <h1>Login to Your Account</h1>
                        
                        <?php if (!empty($errors)): ?>
                        <div class="error-container">
                            <?php foreach ($errors as $error): ?>
                            <p class="error-message"><?php echo $error; ?></p>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                        
                        <form action="login.php" method="POST" id="loginForm">
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" placeholder="your@email.com" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" id="password" name="password" placeholder="Your password" required>
                            </div>
                            
                            <div class="form-group remember-me">
                                <label class="checkbox-container">
                                    <input type="checkbox" name="remember">
                                    <span class="checkmark"></span>
                                    Remember me
                                </label>
                                <a href="forgot-password.php" class="forgot-password">Forgot Password?</a>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                            
                            <div class="auth-links">
                                <p>Don't have an account? <a href="register.php">Register now</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    <?php include_once 'includes/footer.php'; ?>
    <script src="assets/js/validation.js"></script>
</body>
</html>