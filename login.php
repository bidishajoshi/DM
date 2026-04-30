<?php
session_start();
include 'config.php';

$msg = "";

if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $pass  = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "❌ Invalid email format";
    } else {

        $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

        if (mysqli_num_rows($query) == 1) {
            $user = mysqli_fetch_assoc($query);

            if (password_verify($pass, $user['password'])) {

                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user']    = $user['name'];

                header("Location: dashboard.php");
                exit();
            } else {
                $msg = "❌ Incorrect password";
            }
        } else {
            $msg = "❌ Email not registered";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Digital Mandir</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<div class="navbar">
    <h2>🛕 Digital Mandir</h2>
    <div>
        <a href="index.php">Home</a>
        <a href="register.php">Register</a>
        <button class="theme-toggle" title="Switch to Dark Mode"></button>
    </div>
</div>

<script src="theme-toggle.js"></script>



<div class="form-box">
    <h2>User Login</h2>
    <p style="text-align: center; color: var(--text-light); margin-top: -1.5rem; margin-bottom: 2rem;">Welcome back, please login</p>

    <?php if($msg!=""){ ?>
        <div class="message" style="background: #fdecea; color: #e74c3c; border-left-color: #e74c3c;"><?php echo $msg; ?></div>
    <?php } ?>

    <form method="post">
        <label style="font-size: 0.9rem; font-weight: 500; color: var(--text-color);">Email Address</label>
        <input type="email" name="email" placeholder="email@example.com" required>

        <label style="font-size: 0.9rem; font-weight: 500; color: var(--text-color);">Password</label>
        <div class="show-pass">
            <input type="password" name="password" id="password" placeholder="••••••••" required>
        </div>

        <button class="btn" name="login" style="width: 100%;">Login</button>

        <div class="form-footer" style="text-align: center; margin-top: 2rem; font-size: 0.9rem;">
            Don’t have an account?
            <a href="register.php" style="color: var(--primary); font-weight: 600; text-decoration: none;">Register</a><br><br>
            <a href="forgot_password.php" style="color: var(--text-light); text-decoration: none;">Forgot Password?</a>
        </div>
    </form>
</div>

<div class="footer">
    © 2025 Digital Mandir | Final Year BCA Project
</div>

</body>
</html>
