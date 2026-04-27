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
<html lang="en">
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
    </div>
</div>


<body>

<div class="form-box">
    <h2>User Login</h2>
    <p class="subtitle">Welcome back, please login</p>

    <?php if($msg!=""){ ?>
        <div class="error"><?php echo $msg; ?></div>
    <?php } ?>

    <form method="post">
        <input type="email" name="email" placeholder="Email" required>

        <div class="show-pass">
            <input type="password" name="password" id="password" placeholder="Password" required>
            
        </div>

        <button class="btn" name="login">Login</button>

        <div class="form-footer">
            Don’t have an account?
            <a href="register.php">Register</a><br><br>
            <a href="forgot_password.php">Forgot Password?</a>
        </div>
    </form>
</div>

<script>
function togglePass() {
    const pass = document.getElementById("password");
    pass.type = pass.type === "password" ? "text" : "password";
}
</script>

</body>
</html>
